<?php

namespace Medilies\Xssless;

interface CliInterface
{
    public function configure(ConfigInterface $config): static;

    public function setup(): void;

    public function exec(string $html): string;
}
