<?php

namespace Medilies\Xssless;

use Exception;

class Xssless
{
    /** @param ?array<string, mixed> $config */
    public function clean(string $html, ?array $config = null): string
    {
        $cleaner = $this->makeCleaner($config);

        return match (true) {
            $cleaner instanceof CliInterface => $this->exec($cleaner, $html),
            $cleaner instanceof ServiceInterface => $this->send($cleaner, $html),
        };
    }

    /** @param ?array<string, mixed> $config */
    public function start(?array $config = null): ServiceInterface
    {
        $service = $this->makeCleaner($config);

        if (! $service instanceof ServiceInterface) {
            throw new Exception('Must implement one of the interfaces.'); // TODO
        }

        return $service->start($config);
    }

    /** @param ?array<string, mixed> $config */
    public function setup(?array $config = null): void
    {
        $service = $this->makeCleaner($config);

        $service->setup($config);
    }

    /** @param ?array<string, mixed> $config */
    private function makeCleaner(?array $config = null): CliInterface|ServiceInterface
    {
        $config ??= $this->config();

        $class = $config['class'];

        $cleaner = new $class($config);

        if (! $cleaner instanceof ServiceInterface && ! $cleaner instanceof CliInterface) {
            throw new Exception('Must implement one of the interfaces.'); // TODO
        }

        return $cleaner;
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

        if (! is_string($driver)) {
            throw new Exception('xssless.default must be a string.');
        }

        $config = config("xssless.{$driver}");

        if (! is_array($config)) {
            throw new Exception("xssless.{$driver} must be an array.");
        }

        return $config;
    }
}
