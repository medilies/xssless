<?php

namespace Tests\Mocks;

use Medilies\Xssless\Interfaces\CliInterface;
use Medilies\Xssless\Interfaces\ConfigInterface;

class NoSetupDriver implements CliInterface
{
    public function configure(ConfigInterface $config): static
    {
        return $this;
    }

    public function exec(string $html): string
    {
        return '';
    }
}
