<?php

namespace Medilies\Xssless;

interface CliInterface
{
    /** @param ?array<string, mixed> $config */
    public function setup(?array $config): void;

    public function exec(string $html): string;
}
