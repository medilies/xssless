<?php

namespace Medilies\Xssless;

interface CliInterface extends ConfigurableInterface
{
    public function exec(string $html): string;
}
