<?php

use Medilies\Xssless\Dompurify\DompurifyCli;
use Medilies\Xssless\Dompurify\DompurifyCliConfig;
use Medilies\Xssless\Exceptions\XsslessException;
use Medilies\Xssless\Xssless;
use Symfony\Component\Process\Exception\ProcessFailedException;

// ----------------------------------------------------------------------------
// Errors and mocked binaries
// ----------------------------------------------------------------------------

it('throws on bad node path', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        node: 'nodeZz',
    ));

    expect(fn () => $cleaner->exec('foo'))->toThrow(ProcessFailedException::class);
});

it('throws when cannot find binary file', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        binary: __DIR__.'/js-mocks/x.js',
    ));

    expect(fn () => $cleaner->exec('foo'))->toThrow(ProcessFailedException::class);
});

it('throws when cannot locate temp folder', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        tempFolder: __DIR__.'/x',
    ));

    expect(fn () => $cleaner->exec('foo'))->toThrow(XsslessException::class);
});

it('throws when cannot read cleaned file', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        binary: __DIR__.'/js-mocks/cli-returns-bad-path.js',
    ));

    expect(fn () => $cleaner->exec('foo'))->toThrow(XsslessException::class);
});

// ----------------------------------------------------------------------------
// Real setup and clean
// ----------------------------------------------------------------------------

test('setup()', function () {
    $cleaner = (new Xssless)->using(new DompurifyCliConfig);

    expect($cleaner->setup())->toBeTrue();
});

test('exec()', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig);

    $clean = $cleaner->exec('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
})->depends('setup()');

test('clean()', function () {
    $cleaner = (new Xssless)->using(new DompurifyCliConfig(
        tempFolder: __DIR__,
    ));

    $clean = $cleaner->clean('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
})->depends('setup()');
