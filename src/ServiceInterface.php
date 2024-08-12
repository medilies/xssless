<?php

namespace Medilies\Xssless;

interface ServiceInterface extends ConfigurableInterface
{
    public function setup(): void;

    public function send(string $html): string;

    public function start(): static;

    public function stop(): static;

    public function isRunning(): bool;

    public function getIncrementalOutput(): string;

    public function getIncrementalErrorOutput(): string;
}
