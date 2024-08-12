<?php

namespace Medilies\Xssless\Dompurify;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Medilies\Xssless\Interfaces\ConfigInterface;
use Medilies\Xssless\Interfaces\HasSetupInterface;
use Medilies\Xssless\Interfaces\ServiceInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DompurifyService implements HasSetupInterface, ServiceInterface
{
    private DompurifyServiceConfig $config;

    // TODO: better injection (fs and process http)

    // TODO: private
    public Process $serviceProcess;
    // ? add static array for all processes

    /** @param DompurifyServiceConfig $config */
    public function configure(ConfigInterface $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function setup(): void
    {
        $process = new Process([$this->config->npm, 'i'], __DIR__);
        $process->mustRun();
    }

    public function send(string $html): string
    {
        $url = "http://{$this->config->host}:{$this->config->port}";

        $client = new Client;
        $res = $client->post($url, [
            RequestOptions::JSON => [
                'html' => $html,
            ],
        ]);

        return $res->getBody();
    }

    public function start(): static
    {
        $this->serviceProcess = new Process([
            $this->config->node,
            $this->config->binary ?? __DIR__.DIRECTORY_SEPARATOR.'http.js',
            $this->config->host,
            $this->config->port,
        ]);

        $this->serviceProcess->setIdleTimeout($this->config->startupTimeoutMs / 1000);

        $this->serviceProcess->start();

        // ? is there a possibility to miss output before running the waitUntil
        $this->serviceProcess->waitUntil(function (string $type, string $buffer) {
            if ($type === Process::ERR) {
                throw new ProcessFailedException($this->serviceProcess);
            }

            // Must output when service is listening
            return strlen($buffer) > 4;
        });

        $this->serviceProcess->setIdleTimeout(null);

        // ? rm check
        if (! $this->isRunning()) {
            throw new ProcessFailedException($this->serviceProcess);
        }

        return $this;
    }

    public function stop(): static
    {
        $this->serviceProcess->stop();

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
}
