<?php

namespace Medilies\Xssless;

class Xssless
{
    private ConfigInterface $config;

    // TODO: policy builder

    public function clean(string $html, ?ConfigInterface $config = null): string
    {
        $cleaner = $this->makeCleaner($config);

        return match (true) {
            $cleaner instanceof CliInterface => $this->exec($cleaner, $html),
            $cleaner instanceof ServiceInterface => $this->send($cleaner, $html),
        };
    }

    public function start(?ConfigInterface $config = null): ServiceInterface
    {
        $service = $this->makeCleaner($config);

        if (! $service instanceof ServiceInterface) {
            throw new XsslessException("'".$service::class."' must implement: '".ServiceInterface::class."'.");
        }

        return $service->start();
    }

    public function setup(?ConfigInterface $config = null): void
    {
        $service = $this->makeCleaner($config);

        $service->setup();
    }

    public function usingLaravelConfig(): static
    {
        $driver = config('xssless.default');

        if (! is_string($driver)) {
            throw new XsslessException('xssless.default must be a string.');
        }

        $config = config("xssless.{$driver}");

        if (! $config instanceof ConfigInterface) {
            throw new XsslessException("xssless.{$driver} must implement: ".ConfigInterface::class);
        }

        $this->config = $config;

        return $this;
    }

    public function using(ConfigInterface $config): static
    {
        $this->config = $config;

        return $this;
    }

    private function makeCleaner(?ConfigInterface $config = null): CliInterface|ServiceInterface
    {
        $config ??= $this->config ?? null;

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
