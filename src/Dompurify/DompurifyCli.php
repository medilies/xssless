<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\Exceptions\XsslessException;
use Medilies\Xssless\Interfaces\CliInterface;
use Medilies\Xssless\Interfaces\ConfigInterface;
use Medilies\Xssless\Interfaces\HasSetupInterface;
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

        $process = new Process([
            $this->config->node,
            $this->config->binary ?? __DIR__.DIRECTORY_SEPARATOR.'cli.js', // ? check file explicitly
            $htmlFile,
        ]);

        $process->mustRun();

        $output = $process->getOutput();
        $cleanHtmlPath = trim($output);

        if (! file_exists($cleanHtmlPath)) {
            throw new XsslessException("Could not locate the file '{$cleanHtmlPath}'");
        }

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
        $dir = $this->tempDir();

        // ? use tempnam
        $fileName = mt_rand().'-'.str_replace([' ', '.'], '', microtime()).'.xss';

        $path = $dir.DIRECTORY_SEPARATOR.$fileName;

        if (file_put_contents($path, $value) === false) {
            throw new XsslessException("Could not create file '{$path}'");
        }

        return $path;
    }

    private function tempDir(): string
    {
        if (is_null($this->config->tempFolder)) {
            $dir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'xssless';

            if (! file_exists($dir) && mkdir($dir, 0777, true) === false) {
                throw new XsslessException("Could not create temporary directory '{$dir}'");
            }

            return $dir;
        }

        if (! file_exists($this->config->tempFolder)) {
            throw new XsslessException("Could not locate temporary directory '{$this->config->tempFolder}'");
        }

        return $this->config->tempFolder;
    }
}
