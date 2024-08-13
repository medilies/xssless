<?php

test('config is loaded', function () {
    expect(config('xssless.default'))->toBe('dompurify-cli');
});
