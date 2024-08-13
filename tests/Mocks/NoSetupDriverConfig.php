<?php

namespace Tests\Mocks;

use Medilies\Xssless\Interfaces\ConfigInterface;

class NoSetupDriverConfig implements ConfigInterface
{
    public function getClass(): string
    {
        return NoSetupDriver::class;
    }
}
