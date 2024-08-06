<?php

namespace Medilies\Xssless\Facades;

use Illuminate\Support\Facades\Facade;
use Medilies\Xssless\ServiceInterface;

/**
 * @method static string clean(string $html, ?ConfigInterface $config = null)
 * @method static ServiceInterface start(?ConfigInterface $config = null)
 * @method static void setup(?ConfigInterface $config = null)
 * @method static static usingLaravelConfig()
 *
 * @see \Medilies\Xssless\Xssless
 */
class Xssless extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Medilies\Xssless\Xssless::class;
    }
}
