<?php

namespace Medilies\Xssless\Dompurify;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Medilies\Xssless\ServiceInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DompurifyService extends Dompurify implements ServiceInterface
{
    private string $host;

    private int $port;

    public Process $serviceProcess;
    // ? add static array for all processes

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

        // TODO validate
        $this->host = $config['host'];
        $this->port = $config['port'];

        return $this;
    }

    // ========================================================================

    /** @param ?array<string, mixed> $config */
    public function send(string $html, ?array $config = null): string
    {
        $this->configure($config);

        $url = "http://{$this->host}:{$this->port}";

        $client = new Client;
        $res = $client->post($url, [
            RequestOptions::JSON => [
                'html' => $html,
            ],
        ]);

        return $res->getBody();
    }

    // ========================================================================

    /** @param ?array<string, mixed> $config */
    public function start(?array $config = null): static
    {
        $this->configure($config);

        $this->serviceProcess = new Process(['node', __DIR__.'/http.js', $this->host, $this->port]);
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

        $this->serviceProcess->stop();
        throw new ProcessFailedException($this->serviceProcess);
    }

    // ========================================================================
}
