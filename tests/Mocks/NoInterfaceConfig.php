<?php

namespace Tests\Mocks;

use Medilies\Xssless\Interfaces\ConfigInterface;
use stdClass;

class NoInterfaceConfig implements ConfigInterface
{
    public function getClass(): string
    {
        return stdClass::class;
    }
}
