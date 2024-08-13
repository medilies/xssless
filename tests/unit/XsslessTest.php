<?php

use Medilies\Xssless\Dompurify\DompurifyCliConfig;
use Medilies\Xssless\Exceptions\XsslessException;
use Medilies\Xssless\Xssless;
use Tests\Mocks\NoInterfaceConfig;
use Tests\Mocks\NoSetupDriverConfig;

// ----------------------------------------------------------------------------
// makeCleaner()
// ----------------------------------------------------------------------------

it('throws when makeCleaner() with NoInterfaceConfig', function () {
    $cleaner = (new Xssless)
        ->using(new NoInterfaceConfig);

    expect(fn () => $cleaner->clean('foo'))->toThrow(XsslessException::class);
});

it('throws when makeCleaner() with no config', function () {
    $cleaner = new Xssless;

    expect(fn () => $cleaner->clean('foo'))->toThrow(XsslessException::class);
});

// ----------------------------------------------------------------------------
// return gracefully when interface not implemented
// ----------------------------------------------------------------------------

it('returns null when start() without ServiceInterface', function () {
    $cleaner = new Xssless;
    $cleaner->using(new DompurifyCliConfig);

    expect($cleaner->start())->toBeNull();
});

it('returns false when setup() without HasSetupInterface', function () {
    $cleaner = (new Xssless)
        ->using(new NoSetupDriverConfig);

    expect($cleaner->setup())->toBeFalse();
});
