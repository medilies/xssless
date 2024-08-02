<?php

namespace Medilies\Xssless;

interface CliInterface
{
    /** @param array<string, mixed> $config */
    public function configure(array $config): static;

    public function exec(string $html): string;
}
