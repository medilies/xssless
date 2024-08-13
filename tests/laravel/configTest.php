<?php

test('config is loaded', function () {
    expect(config('xssless.default'))->toBe('dompurify-cli');
});

it('caches', function () {
    $config = config('xssless');
    $serializedConfig = var_export($config, true);

    $evaluatedConfig = eval("return {$serializedConfig};");
    expect($evaluatedConfig)->toBeArray();

    expect($evaluatedConfig)->toEqual($config);
})->depends('config is loaded');
