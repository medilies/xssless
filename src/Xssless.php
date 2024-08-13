<?php

namespace Medilies\Xssless;

use Medilies\Xssless\Exceptions\XsslessException;
use Medilies\Xssless\Interfaces\CliInterface;
use Medilies\Xssless\Interfaces\ConfigInterface;
use Medilies\Xssless\Interfaces\HasSetupInterface;
use Medilies\Xssless\Interfaces\ServiceInterface;

class Xssless
{
    private ConfigInterface $config;

    // TODO: policy builder

    public function clean(string $html, ?ConfigInterface $tempConfig = null): string
    {
        $cleaner = $this->makeCleaner($tempConfig);

        return match (true) {
            $cleaner instanceof CliInterface => $this->exec($cleaner, $html),
            $cleaner instanceof ServiceInterface => $this->send($cleaner, $html),
        };
    }

    public function start(?ConfigInterface $tempConfig = null): ?ServiceInterface
    {
        $service = $this->makeCleaner($tempConfig);

        if (! $service instanceof ServiceInterface) {
            return null;
        }

        return $service->start();
    }

    public function setup(?ConfigInterface $tempConfig = null): bool
    {
        $cleaner = $this->makeCleaner($tempConfig);

        if (! $cleaner instanceof HasSetupInterface) {
            return false;
        }

        $cleaner->setup();

        return true;
    }

    public function usingLaravelConfig(): static
    {
        $driver = config('xssless.default');

        if (! is_string($driver)) {
            throw new XsslessException('xssless.default must be a string.');
        }

        $config = config("xssless.drivers.{$driver}");

        if (! $config instanceof ConfigInterface) {
            throw new XsslessException("xssless.drivers.{$driver} must implement: ".ConfigInterface::class);
        }

        $this->config = $config;

        return $this;
    }

    public function using(ConfigInterface $config): static
    {
        $this->config = $config;

        return $this;
    }

    private function makeCleaner(?ConfigInterface $tempConfig = null): CliInterface|ServiceInterface
    {
        $config = $tempConfig ?? $this->config ?? null;

        if (is_null($config)) {
            throw new XsslessException('A config must be provided.');
        }

        $class = $config->getClass();

        $cleaner = new $class;

        if (! $cleaner instanceof ServiceInterface && ! $cleaner instanceof CliInterface) {
            throw new XsslessException("'$class' must implement one of the interfaces: '".ServiceInterface::class."' or '".CliInterface::class."'.");
        }

        return $cleaner->configure($config);
    }

    private function exec(CliInterface $cleaner, string $html): string
    {
        return $cleaner->exec($html);
    }

    private function send(ServiceInterface $cleaner, string $html): string
    {
        return $cleaner->send($html);
    }
}
