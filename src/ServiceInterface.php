<?php

namespace Medilies\Xssless;

interface ServiceInterface
{
    /** @param ?array<string, mixed> $config */
    public function setup(?array $config): void;

    public function send(string $html): string;

    /** @param ?array<string, mixed> $config */
    public function start(?array $config = null): static;

    public function stop(): static;

    public function isRunning(): bool;

    public function getIncrementalOutput(): string;

    public function getIncrementalErrorOutput(): string;

    public function throwIfFailedOnExit(): void;
}
