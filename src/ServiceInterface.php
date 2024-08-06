<?php

namespace Medilies\Xssless;

interface ServiceInterface
{
    public function configure(?ConfigInterface $config): static;

    public function setup(?ConfigInterface $config = null): void;

    public function send(string $html, ?ConfigInterface $config = null): string;

    public function start(?ConfigInterface $config = null): static;

    public function stop(): static;

    public function isRunning(): bool;

    public function getIncrementalOutput(): string;

    public function getIncrementalErrorOutput(): string;

    public function throwIfFailedOnExit(): void;
}
