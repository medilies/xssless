<?php

namespace Medilies\Xssless;

interface ServiceInterface
{
    /** @param ?array<string, mixed> $config */
    public function configure(?array $config): static;

    public function send(string $html): string;

    /** @param ?array<string, mixed> $config */
    public function start(?array $config = null): static;

    public function stop(): static;

    public function isRunning(): bool;

    public function getIncrementalOutput(): string;

    public function getIncrementalErrorOutput(): string;

    public function throwIfFailed(): void;
}
