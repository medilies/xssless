<?php

namespace Medilies\Xssless\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Medilies\Xssless\Interfaces\ConfigInterface;
use Medilies\Xssless\Interfaces\ServiceInterface;

/**
 * @method static string clean(string $html, ?ConfigInterface $config = null)
 * @method static ?ServiceInterface start(?ConfigInterface $config = null)
 * @method static bool setup(?ConfigInterface $config = null)
 * @method static static usingLaravelConfig()
 * @method static static using(ConfigInterface $config)
 *
 * @see \Medilies\Xssless\Xssless
 */
class Xssless extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        // TODO: model cast
        return \Medilies\Xssless\Xssless::class;
    }
}
