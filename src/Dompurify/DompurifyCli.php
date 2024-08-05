<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\CliInterface;
use Medilies\Xssless\XsslessException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DompurifyCli extends Dompurify implements CliInterface
{
    /** @param ?array<string, mixed> $config */
    public function __construct(?array $config = null)
    {
        $this->configure($config);
    }

    /** @param ?array<string, mixed> $config */
    public function configure(?array $config): static
    {
        if (is_null($config)) {
            return $this;
        }

        // TODO: validate
        $this->node = $config['node_path'];
        $this->npm = $config['npm_path'];

        return $this;
    }

    /** @param ?array<string, mixed> $config */
    public function exec(string $html, ?array $config = null): string
    {
        $this->configure($config);

        $htmlFile = $this->saveHtml($html);

        $binPath = __DIR__.'/cli.js';

        $binAbsPath = realpath($binPath);

        if ($binAbsPath === false) {
            throw new XsslessException("Cannot locate '$binPath'");
        }

        $process = new Process([$this->node, $binAbsPath, $htmlFile]);
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
        // TODO: take from config
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
