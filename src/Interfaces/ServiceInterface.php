<?php

namespace Medilies\Xssless\Interfaces;

interface ServiceInterface extends ConfigurableInterface
{
    public function send(string $html): string;

    public function start(): static;

    public function stop(): static;

    public function isRunning(): bool;

    public function getIncrementalOutput(): string;

    public function getIncrementalErrorOutput(): string;
}
