<?php

namespace Medilies\Xssless;

interface CliInterface extends ConfigurableInterface
{
    public function setup(): void;

    public function exec(string $html): string;
}
