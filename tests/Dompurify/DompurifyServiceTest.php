<?php

use Medilies\Xssless\Dompurify\DompurifyService;
use Medilies\Xssless\Dompurify\DompurifyServiceConfig;

it('cleans via send', function () {
    $cleaner = (new DompurifyService)->configure(new DompurifyServiceConfig(
        'node',
        'npm',
        '127.0.0.1',
        63000,
    ));

    $cleaner->start();

    $dirty = str_repeat('*/', 34 * 1000).'<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    // TODO: move to ServiceInterface + timeout
    $cleaner->serviceProcess->waitUntil(function (string $type, string $buffer) {
        return strpos($buffer, 'Server is running on') !== false;
    });

    $clean = $cleaner->send($dirty);

    $cleaner->stop();

    expect($clean)->toBe(str_repeat('*/', 34 * 1000).'<img>"&gt;');
});
