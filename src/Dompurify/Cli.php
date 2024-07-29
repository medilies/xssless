<?php

namespace Medilies\Xssless\Dompurify;

use Exception;

class Cli
{
    public function clean(string $html): string
    {
        $htmlPath = $this->saveHtml($html);

        $cleanHtmlPath = $this->exec($htmlPath);

        $clean = file_get_contents($cleanHtmlPath);

        // finally
        unlink($htmlPath) ?: throw new Exception('Failed to delete');
        unlink($cleanHtmlPath) ?: throw new Exception('Failed to delete');

        return $clean;
    }

    private function exec(string $htmlFile): string
    {
        $binPath = __DIR__.'/cli.js';

        $binAbsPath = realpath($binPath);

        if ($binAbsPath === false) {
            throw new Exception("Cannot locate '$binPath'");
        }

        $command = '/home/medilies/.nvm/versions/node/v20.15.1/bin/node'.' '. // TODO
            escapeshellarg($binAbsPath).' '.
            escapeshellarg($htmlFile).' '.
            '2>&1';

        exec($command, $output, $statusCode);

        if ($statusCode !== 0) {
            throw new Exception("[$statusCode]: ".implode("\n", $output));
        }

        return $output[0];
    }

    private function saveHtml(string $value): string
    {
        $path = __DIR__.'/'.microtime().'.xss';

        file_put_contents($path, $value);

        return $path;
    }
}
