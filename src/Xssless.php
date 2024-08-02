<?php

namespace Medilies\Xssless;

use Exception;

class Xssless
{
    /** @param ?array<string, mixed> $config */
    public function clean(string $html, ?array $config = null): string
    {
        $config ??= $this->config();

        $class = $config['class'];

        $cleaner = new $class($config);

        return match (true) {
            $cleaner instanceof CliInterface => $this->exec($cleaner, $html),
            $cleaner instanceof ServiceInterface => $this->send($cleaner, $html),
            default => throw new Exception('Must implement one of the interfaces.'), // TODO
        };
    }

    private function exec(CliInterface $cleaner, string $html): string
    {
        return $cleaner->exec($html);
    }

    private function send(ServiceInterface $cleaner, string $html): string
    {
        return $cleaner->send($html);
    }

    /** @return array<string, mixed> $config */
    private function config(): array
    {
        // ! Laravel specific
        $driver = config('xssless.default');

        // TODO: validate driver

        $config = config("xssless.{$driver}");

        // TODO: validate array

        return $config;
    }
}
