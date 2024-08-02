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
        // TODO: use system tmp
        $path = __DIR__.'/'.microtime().'.xss';

        file_put_contents($path, $value);

        return $path;
    }
}
