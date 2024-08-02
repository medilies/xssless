<?php

namespace Medilies\Xssless;

interface ServiceInterface
{
    /** @param array<string, mixed> $config */
    public function configure(array $config): static;

    public function start(): void;

    public function send(string $html): string;
}
