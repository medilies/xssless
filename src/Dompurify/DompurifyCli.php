<?php

namespace Medilies\Xssless\Dompurify;

use Exception;
use Medilies\Xssless\CliInterface;

class DompurifyCli implements CliInterface
{
    private string $node;

    /** @param ?array<string, mixed> $config */
    public function __construct(?array $config = null)
    {
        $this->configure($config);
    }

    /** @param ?array<string, mixed> $config */
    public function exec(string $html, ?array $config = null): string
    {
        $this->configure($config);

        $htmlFile = $this->saveHtml($html);

        $binPath = __DIR__.'/cli.js';

        $binAbsPath = realpath($binPath);

        if ($binAbsPath === false) {
            throw new Exception("Cannot locate '$binPath'");
        }

        // TODO: check node bin
        $command = $this->node.' '.
            escapeshellarg($binAbsPath).' '.
            escapeshellarg($htmlFile).' '.
            '2>&1';

        exec($command, $output, $statusCode);

        if ($statusCode !== 0) {
            throw new Exception("[Exited with code $statusCode]: ".implode("\n", $output));
        }

        $cleanHtmlPath = $output[0];

        $clean = file_get_contents($cleanHtmlPath);

        if ($clean === false) {
            throw new Exception("Could not read the file '{$cleanHtmlPath}'");
        }

        // finally
        unlink($htmlFile) ?: throw new Exception('Failed to delete');
        unlink($cleanHtmlPath) ?: throw new Exception('Failed to delete');

        return $clean;
    }

    /** @param ?array<string, mixed> $config */
    public function configure(?array $config): static
    {
        if (is_null($config)) {
            return $this;
        }

        // TODO: validate
        $this->node = $config['node_path'];

        return $this;
    }

    private function saveHtml(string $value): string
    {
        // TODO: take from config
        $tempDir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
        $dir = $tempDir.DIRECTORY_SEPARATOR.'xssless';

        if (! file_exists($dir)) {
            if (mkdir($dir, 0777, true) === false) {
                throw new Exception("Could not create directory '{$dir}'");
            }
        }

        $fileName = mt_rand().'-'.str_replace([' ', '.'], '', microtime()).'.xss';

        $path = $dir.DIRECTORY_SEPARATOR.$fileName;

        if (file_put_contents($path, $value) === false) {
            throw new Exception("Could not create file '{$path}'");
        }

        return $path;
    }
}
