<?php

use GuzzleHttp\Exception\ConnectException;
use Medilies\Xssless\Dompurify\DompurifyService;
use Medilies\Xssless\Dompurify\DompurifyServiceConfig;
use Medilies\Xssless\Xssless;
use Symfony\Component\Process\Exception\ProcessFailedException;

test('send()', function () {
    $cleaner = (new DompurifyService)->configure(new DompurifyServiceConfig(
        'node',
        'npm',
        '127.0.0.1',
        63000,
    ));

    $cleaner->start();

    $dirty = str_repeat('*/', 34 * 1000).'<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    $clean = $cleaner->send($dirty);

    $cleaner->stop()->throwIfFailedOnTerm();

    expect($clean)->toBe(str_repeat('*/', 34 * 1000).'<img>"&gt;');
});

test('clean()', function () {
    $config = new DompurifyServiceConfig(
        'node',
        'npm',
        '127.0.0.1',
        63000,
    );

    $cleaner = (new Xssless)->using($config);

    $service = (new DompurifyService)->configure($config);

    $service->start();

    $dirty = '<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    $clean = $cleaner->clean($dirty);

    $service->stop()->throwIfFailedOnTerm();

    expect($clean)->toBe('<img>"&gt;');
});

it('throws on bad host', function () {
    $cleaner = (new DompurifyService)->configure(new DompurifyServiceConfig(
        'node',
        'npm',
        'a.b.c.example.com',
        63000,
    ));

    $dirty = '<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    expect(fn () => $cleaner->send($dirty))->toThrow(ConnectException::class);
});

it('throws on bad node path', function () {
    $service = (new DompurifyService)->configure(new DompurifyServiceConfig(
        'nodeZz',
        'npm',
        '127.0.0.1',
        63000,
    ));

    expect(fn () => $service->start())->toThrow(ProcessFailedException::class);
    expect($service->serviceProcess->getTermSignal() === 127);
});
