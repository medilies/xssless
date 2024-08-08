<?php

namespace Medilies\Xssless\Dompurify;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Medilies\Xssless\ConfigInterface;
use Medilies\Xssless\HasSetupInterface;
use Medilies\Xssless\ServiceInterface;
use Medilies\Xssless\XsslessException;
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
        $process = new Process([$this->config->npmPath, 'i'], __DIR__);
        $process->mustRun();
    }

    public function send(string $html): string
    {
        $url = "http://{$this->config->getHost()}:{$this->config->getPort()}";

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
            $this->config->nodePath,
            __DIR__.DIRECTORY_SEPARATOR.'http.js',
            $this->config->host,
            $this->config->port,
        ]);

        $this->serviceProcess->start();

        $this->serviceProcess->waitUntil(function (string $type, string $buffer) {
            // ? timeout
            // ! ensure service always returns output
            return strlen($buffer) > 5;
        });

        if (! $this->serviceProcess->isRunning()) {
            throw new ProcessFailedException($this->serviceProcess);
        }

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

    public function throwIfFailedOnTerm(): void
    {
        if ($this->serviceProcess->isRunning()) {
            // ? stop it
            throw new XsslessException('The service is still running');
        }

        // TODO: fix windows check
        // https://github.com/medilies/xssless/actions/runs/10288495452/job/28474063301#step:7:26
        if ($this->isSigTerm() || $this->isWindows()) {
            return;
        }

        throw new ProcessFailedException($this->serviceProcess);
    }

    // ? interface
    public function waitForTermination(int $timeout): void
    {
        $elapsed = 0;
        $sleepInterval = 100; // Sleep for 100 milliseconds

        while ($this->serviceProcess->isRunning() && $elapsed < $timeout) {
            usleep($sleepInterval * 1000);
            $elapsed += $sleepInterval;
        }

        if ($this->serviceProcess->isRunning()) {
            throw new XsslessException('Process did not terminate within the given timeout');
        }
    }

    // ========================================================================

    private function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    private function isSigTerm(): bool
    {
        return $this->serviceProcess->getTermSignal() === 15;
    }

    // private function isSigHup(): bool
    // {
    //     return $this->serviceProcess->getTermSignal() === 1;
    // }
}
