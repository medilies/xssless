<?php

use GuzzleHttp\Exception\ConnectException;
use Medilies\Xssless\Dompurify\DompurifyService;
use Medilies\Xssless\Dompurify\DompurifyServiceConfig;
use Medilies\Xssless\Exceptions\XsslessException;
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

it('throws response in not Ok', function () {
    $cleaner = (new Xssless)->using(new DompurifyServiceConfig(
        binary: __DIR__.'/js-mocks/service-respond-not-ok.mjs',
        port: 63002,
    ));

    $service = $cleaner->start();

    expect(fn () => $cleaner->clean('foo'))->toThrow(XsslessException::class);

    $service->stop();
});

// ----------------------------------------------------------------------------
// Real setup and clean
// ----------------------------------------------------------------------------

test('setup()', function () {
    $cleaner = (new Xssless)->using(new DompurifyServiceConfig);

    expect($cleaner->setup())->toBeTrue();
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
    $cleaner = (new Xssless)->using(new DompurifyServiceConfig(
        port: 63001, // for parallel tests
    ));

    $service = $cleaner->start();

    $dirty = '<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    $clean = $cleaner->clean($dirty);

    $service->stop();

    expect($clean)->toBe('<img>"&gt;');
})->depends('setup()');
