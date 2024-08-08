<?php

use Medilies\Xssless\Dompurify\DompurifyCli;
use Medilies\Xssless\Dompurify\DompurifyCliConfig;
use Medilies\Xssless\Xssless;
use Symfony\Component\Process\Exception\ProcessFailedException;

test('setup()', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        'node',
        'npm',
    ));

    expect(fn () => $cleaner->setup())->not->toThrow(Exception::class);
});

test('exec()', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        'node',
        'npm',
    ));

    $clean = $cleaner->exec('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
})->depends('setup()');

test('clean()', function () {
    $cleaner = (new Xssless)->using(new DompurifyCliConfig(
        'node',
        'npm',
    ));

    $clean = $cleaner->clean('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
})->depends('setup()');

it('throws on bad node path', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        'nodeZz',
        'npm',
    ));

    expect(fn () => $cleaner->exec('foo'))->toThrow(ProcessFailedException::class);
});
