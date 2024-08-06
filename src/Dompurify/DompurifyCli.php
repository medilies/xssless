<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\CliInterface;
use Medilies\Xssless\ConfigInterface;
use Medilies\Xssless\XsslessException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DompurifyCli implements CliInterface
{
    protected DompurifyCliConfig $config;

    /** @param DompurifyCliConfig $config */
    public function configure(ConfigInterface $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function setup(): void
    {
        $process = new Process([$this->config->getNpmPath(), 'i'], __DIR__);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    public function exec(string $html): string
    {
        $htmlFile = $this->saveHtml($html);

        $binPath = __DIR__.'/cli.js';

        $binAbsPath = realpath($binPath);

        if ($binAbsPath === false) {
            throw new XsslessException("Cannot locate '$binPath'");
        }

        $process = new Process([$this->config->getNodePath(), $binAbsPath, $htmlFile]);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

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

    private function saveHtml(string $value): string
    {
        // TODO: take path from config
        $tempDir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
        $dir = $tempDir.DIRECTORY_SEPARATOR.'xssless';

        if (! file_exists($dir)) {
            if (mkdir($dir, 0777, true) === false) {
                throw new XsslessException("Could not create directory '{$dir}'");
            }
        }

        $fileName = mt_rand().'-'.str_replace([' ', '.'], '', microtime()).'.xss';

        $path = $dir.DIRECTORY_SEPARATOR.$fileName;

        if (file_put_contents($path, $value) === false) {
            throw new XsslessException("Could not create file '{$path}'");
        }

        return $path;
    }
}
