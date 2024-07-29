<?php

use Medilies\Xssless\Dompurify\Cli;
use Medilies\Xssless\Dompurify\Http;
use Medilies\Xssless\Dompurify\Tcp;

it('cleans via CLI', function () {
    $cleaner = new Cli;

    $clean = $cleaner->clean('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
});

it('cleans via HTTP', function () {
    $cleaner = new Http;

    $clean = $cleaner->clean('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
});

it('cleans via TCP', function () {
    $cleaner = new Tcp;

    $clean = $cleaner->clean('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
});

it('communicates via TCP 65535 bytes', function () {
    $cleaner = new Tcp;

    $clean = $cleaner->clean(str_repeat('.', 65535));

    expect($clean)->toBe(str_repeat('.', 65535));
});

it('communicates via TCP 67*1000 bytes', function () {
    $cleaner = new Tcp;
    $dirty = str_repeat('*/', 67 * 1000) . '.....';

    $clean = $cleaner->clean($dirty);

    expect($clean)->toBe($dirty);

    // dump(strlen($clean), strlen($dirty));
});

it('cleans via TCP 34*1000 bytes', function () {
    $cleaner = new Tcp;
    $dirty = str_repeat('*/', 34 * 1000) . '<IMG """><SCRIPT>alert("XSS")</SCRIPT>">';

    $clean = $cleaner->clean($dirty);

    expect($clean)->toBe(str_repeat('*/', 34 * 1000) . '<img>"&gt;');

    // dump(strlen($clean), strlen($dirty));
});
