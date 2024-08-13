<?php

use Medilies\Xssless\Exceptions\XsslessException;
use Medilies\Xssless\Laravel\Facades\Xssless;

test('clean()', function () {
    expect(Xssless::setup())->toBeTrue();

    expect(Xssless::clean('foo'))->toBe('foo');
});

it('throws when usingLaravelConfig() with no default driver', function () {
    config([
        'xssless.default' => null,
    ]);

    expect(fn () => Xssless::usingLaravelConfig())->toThrow(XsslessException::class);
});

it('throws when usingLaravelConfig() with bad default driver', function () {
    config([
        'xssless.default' => 'x',
        'xssless.drivers.x' => new stdClass,
    ]);

    expect(fn () => Xssless::usingLaravelConfig())->toThrow(XsslessException::class);
});
