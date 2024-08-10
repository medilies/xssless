<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\CliInterface;
use Medilies\Xssless\ConfigInterface;
use Medilies\Xssless\HasSetupInterface;
use Medilies\Xssless\XsslessException;
use Symfony\Component\Process\Process;

class DompurifyCli implements CliInterface, HasSetupInterface
{
    protected DompurifyCliConfig $config;

    // TODO: better injection (fs and process)

    /** @param DompurifyCliConfig $config */
    public function configure(ConfigInterface $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function setup(): void
    {
        $process = new Process([$this->config->npm, 'i'], __DIR__);
        $process->mustRun();
    }

    public function exec(string $html): string
    {
        $htmlFile = $this->saveHtml($html);

        $process = new Process([$this->config->node, $this->binPath(), $htmlFile]);
        $process->mustRun();

        $output = $process->getOutput();
        $cleanHtmlPath = trim($output);

        $clean = file_get_contents($cleanHtmlPath);

        if ($clean === false) {
            throw new XsslessException("Could not read the file '{$cleanHtmlPath}'");
        }

        // ? finally
        unlink($htmlFile) ?: throw new XsslessException("Failed to delete '$htmlFile'");
        unlink($cleanHtmlPath) ?: throw new XsslessException("Failed to delete '$cleanHtmlPath'");

        return $clean;
    }

    private function binPath(): string
    {
        // TODO: allow config to override
        $binPath = __DIR__.DIRECTORY_SEPARATOR.'cli.js';

        $binAbsPath = realpath($binPath);

        if ($binAbsPath === false) {
            throw new XsslessException("Cannot locate '$binPath'");
        }

        return $binAbsPath;
    }

    private function saveHtml(string $value): string
    {
        $dir = $this->tempDir();

        $fileName = mt_rand().'-'.str_replace([' ', '.'], '', microtime()).'.xss';

        $path = $dir.DIRECTORY_SEPARATOR.$fileName;

        if (file_put_contents($path, $value) === false) {
            throw new XsslessException("Could not create file '{$path}'");
        }

        return $path;
    }

    private function tempDir(): string
    {
        // TODO: take path from config
        $tempDir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
        $dir = $tempDir.DIRECTORY_SEPARATOR.'xssless';

        if (! file_exists($dir)) {
            if (mkdir($dir, 0777, true) === false) {
                throw new XsslessException("Could not create temporary directory '{$dir}'");
            }
        }

        return $dir;
    }
}
