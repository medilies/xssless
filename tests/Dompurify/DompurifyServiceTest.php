<?php

use Medilies\Xssless\Dompurify\DompurifyService;

it('cleans via send', function () {
    $cleaner = new DompurifyService([
        'node_path' => 'node',
        'npm_path' => 'npm',
        'host' => '127.0.0.1',
        'port' => 63000,
    ]);

    $cleaner->start();

    $dirty = str_repeat('*/', 34 * 1000).'<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    // TODO: move to ServiceInterface + timeout
    $cleaner->serviceProcess->waitUntil(function (string $type, string $buffer) {
        return strpos($buffer, 'Server is running on http://127.0.0.1:63000') !== false;
    });

    $clean = $cleaner->send($dirty);

    $cleaner->stop();

    expect($clean)->toBe(str_repeat('*/', 34 * 1000).'<img>"&gt;');
});
