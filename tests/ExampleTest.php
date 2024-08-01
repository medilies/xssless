<?php

use Medilies\Xssless\Dompurify\Cli;
use Medilies\Xssless\Dompurify\Http;

it('cleans via CLI', function () {
    $cleaner = new Cli;

    $clean = $cleaner->clean('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
});

it('cleans via HTTP', function () {
    $cleaner = new Http;

    $dirty = str_repeat('*/', 34 * 1000).'<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    $clean = $cleaner->clean($dirty);

    expect($clean)->toBe(str_repeat('*/', 34 * 1000).'<img>"&gt;');
});
