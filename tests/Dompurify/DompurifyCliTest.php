<?php

use Medilies\Xssless\Dompurify\DompurifyCli;
use Medilies\Xssless\Dompurify\DompurifyCliConfig;
use Medilies\Xssless\Xssless;
use Medilies\Xssless\XsslessException;
use Symfony\Component\Process\Exception\ProcessFailedException;

it('throws on bad node path', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        'nodeZz',
        'npm',
    ));

    expect(fn () => $cleaner->exec('foo'))->toThrow(ProcessFailedException::class);
});

test('setup()', function () {
    $cleaner = (new Xssless)->using(new DompurifyCliConfig(
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
        node: 'node',
        npm: 'npm',
        tempFolder: __DIR__,
    ));

    $clean = $cleaner->clean('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
})->depends('setup()');

it('throws when cannot read cleaned file', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        node: 'node',
        npm: 'npm',
        binary: __DIR__.'/js-mocks/cli-returns-bad-path.js',
    ));

    expect(fn () => $cleaner->exec('foo'))->toThrow(XsslessException::class);
})->depends('setup()');

it('throws when cannot find binary file', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        node: 'node',
        npm: 'npm',
        binary: __DIR__.'/js-mocks/x.js',
    ));

    expect(fn () => $cleaner->exec('foo'))->toThrow(XsslessException::class);
})->depends('setup()');

it('throws when cannot locate temp folder', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        node: 'node',
        npm: 'npm',
        tempFolder: __DIR__.'/x',
    ));

    expect(fn () => $cleaner->exec('foo'))->toThrow(XsslessException::class);
})->depends('setup()');
