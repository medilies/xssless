<?php

use Medilies\Xssless\Laravel\Facades\Xssless;

test('clean()', function () {
    expect(Xssless::setup())->toBeTrue();

    expect(Xssless::clean('foo'))->toBe('foo');
});
