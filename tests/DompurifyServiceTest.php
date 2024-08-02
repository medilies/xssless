<?php

use Medilies\Xssless\Dompurify\DompurifyService;

it('cleans via send', function () {
    $cleaner = new DompurifyService([
        'host' => '127.0.0.1',
        'port' => 63000,
    ]);

    $dirty = str_repeat('*/', 34 * 1000).'<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    $clean = $cleaner->send($dirty);

    expect($clean)->toBe(str_repeat('*/', 34 * 1000).'<img>"&gt;');
});
