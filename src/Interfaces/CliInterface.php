<?php

namespace Medilies\Xssless\Interfaces;

interface CliInterface extends ConfigurableInterface
{
    public function exec(string $html): string;
}
