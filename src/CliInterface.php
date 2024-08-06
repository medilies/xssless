<?php

namespace Medilies\Xssless;

interface CliInterface
{
    public function configure(?ConfigInterface $config): static;

    public function setup(?ConfigInterface $config = null): void;

    public function exec(string $html, ?ConfigInterface $config = null): string;
}
