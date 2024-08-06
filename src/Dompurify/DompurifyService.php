<?php

namespace Medilies\Xssless\Dompurify;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Medilies\Xssless\ConfigInterface;
use Medilies\Xssless\ServiceInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DompurifyService implements ServiceInterface
{
    private DompurifyServiceConfig $config;

    // TODO: private
    public Process $serviceProcess;
    // ? add static array for all processes

    public function __construct(?DompurifyServiceConfig $config = null)
    {
        $this->configure($config);
    }

    /** @param ?DompurifyServiceConfig $config */
    public function configure(?ConfigInterface $config): static
    {
        // TODO: recheck this behavior
        if (is_null($config)) {
            return $this;
        }

        // TODO: validate
        $this->config = $config;

        return $this;
    }

    /** @param ?DompurifyServiceConfig $config */
    public function setup(?ConfigInterface $config = null): void
    {
        $this->configure($config);

        $process = new Process([$this->config->npmPath, 'i'], __DIR__);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    // ========================================================================

    /** @param ?DompurifyServiceConfig $config */
    public function send(string $html, ?ConfigInterface $config = null): string
    {
        $this->configure($config);

        $url = "http://{$this->config->getHost()}:{$this->config->getPort()}";

        $client = new Client;
        $res = $client->post($url, [
            RequestOptions::JSON => [
                'html' => $html,
            ],
        ]);

        return $res->getBody();
    }

    // ========================================================================

    /** @param ?DompurifyServiceConfig $config */
    public function start(?ConfigInterface $config = null): static
    {
        $this->configure($config);

        $this->serviceProcess = new Process([
            $this->config->nodePath,
            __DIR__.'/http.js',
            $this->config->host,
            $this->config->port,
        ]);
        $this->serviceProcess->start();

        return $this;
    }

    public function stop(): static
    {
        if ($this->isRunning()) {
            $this->serviceProcess->stop();
        }

        return $this;
    }

    public function isRunning(): bool
    {
        return $this->serviceProcess->isRunning();
    }

    public function getIncrementalOutput(): string
    {
        return $this->serviceProcess->getIncrementalOutput();
    }

    public function getIncrementalErrorOutput(): string
    {
        return $this->serviceProcess->getIncrementalErrorOutput();
    }

    public function throwIfFailedOnExit(): void
    {
        // TODO: throw if still running

        if ($this->serviceProcess->isSuccessful()) {
            return;
        }

        $this->serviceProcess->stop(); // ? not necessary
        throw new ProcessFailedException($this->serviceProcess);
    }

    // ========================================================================
}
