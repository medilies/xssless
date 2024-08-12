<?php

use GuzzleHttp\Exception\ConnectException;
use Medilies\Xssless\Dompurify\DompurifyService;
use Medilies\Xssless\Dompurify\DompurifyServiceConfig;
use Medilies\Xssless\Xssless;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

// ----------------------------------------------------------------------------
// Errors and mocked binaries
// ----------------------------------------------------------------------------

it('throws on bad node path', function () {
    $service = (new DompurifyService)->configure(new DompurifyServiceConfig(
        node: 'nodeZz',
    ));

    expect(fn () => $service->start())->toThrow(ProcessFailedException::class);
});

it('throws when cannot find binary file', function () {
    $cleaner = (new DompurifyService)->configure(new DompurifyServiceConfig(
        binary: __DIR__.'/js-mocks/x.js',
    ));

    expect(fn () => $cleaner->start('foo'))->toThrow(ProcessFailedException::class);
});

it('throws on start() timeout', function () {
    $service = (new DompurifyService)->configure(new DompurifyServiceConfig(
        binary: __DIR__.'/js-mocks/service-start-timeout.js',
        startupTimeoutMs: 50,
    ));

    expect(fn () => $service->start())->toThrow(ProcessTimedOutException::class);
});

it('throws on bad host', function () {
    $cleaner = (new DompurifyService)->configure(new DompurifyServiceConfig(
        host: 'a.b.c.example.com',
    ));

    $dirty = '<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    expect(fn () => $cleaner->send($dirty))->toThrow(ConnectException::class);
});

// ----------------------------------------------------------------------------
// Real setup and clean
// ----------------------------------------------------------------------------

test('setup()', function () {
    $cleaner = (new DompurifyService)->configure(new DompurifyServiceConfig);

    expect(fn () => $cleaner->setup())->not->toThrow(Exception::class);
});

test('send()', function () {
    $cleaner = (new DompurifyService)->configure(new DompurifyServiceConfig);

    $cleaner->start();

    $dirty = str_repeat('*/', 34 * 1000).'<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    $clean = $cleaner->send($dirty);

    $cleaner->stop();

    expect($clean)->toBe(str_repeat('*/', 34 * 1000).'<img>"&gt;');
})->depends('setup()');

test('clean()', function () {
    $config = new DompurifyServiceConfig(
        port: 63001, // for parallel tests
    );

    $cleaner = (new Xssless)->using($config);

    $service = $cleaner->start();

    $dirty = '<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    $clean = $cleaner->clean($dirty);

    $service->stop();

    expect($clean)->toBe('<img>"&gt;');
})->depends('setup()');
