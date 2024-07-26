<?php

namespace Medilies\Xssless\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Medilies\Xssless\Xssless
 */
class Xssless extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Medilies\Xssless\Xssless::class;
    }
}
