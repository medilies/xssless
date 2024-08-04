<?php

namespace Medilies\Xssless\Dompurify;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class Dompurify
{
    protected string $node;

    /** @param ?array<string, mixed> $config */
    abstract public function configure(?array $config): static;

    /** @param ?array<string, mixed> $config */
    public function setup(?array $config = null): void
    {
        $this->configure($config);

        $process = new Process([$this->node, 'i'], __DIR__);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    // TODO: check node bin
}
