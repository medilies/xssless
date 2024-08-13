<?php

use Medilies\Xssless\Dompurify\DompurifyCliConfig;
use Medilies\Xssless\Exceptions\XsslessException;
use Medilies\Xssless\Interfaces\CliInterface;
use Medilies\Xssless\Interfaces\ConfigInterface;
use Medilies\Xssless\Xssless;

// ----------------------------------------------------------------------------
// makeCleaner()
// ----------------------------------------------------------------------------

it('throws when makeCleaner() with no interface', function () {
    $cleaner = new Xssless;

    $cleaner->using(new class implements ConfigInterface
    {
        public function getClass(): string
        {
            return Xssless::class;
        }
    });

    expect(fn () => $cleaner->clean('foo'))->toThrow(XsslessException::class);
});

it('throws when makeCleaner() with no config', function () {
    $cleaner = new Xssless;

    expect(fn () => $cleaner->clean('foo'))->toThrow(XsslessException::class);
});

// ----------------------------------------------------------------------------
// return gracefully when interface not implemented
// ----------------------------------------------------------------------------

it('returns null when start() without ServiceInterface', function () {
    $cleaner = new Xssless;
    $cleaner->using(new DompurifyCliConfig);

    expect($cleaner->start())->toBeNull();
});

it('returns false when setup() without HasSetupInterface', function () {
    $cleaner = new Xssless;

    class NoSetupDriver implements CliInterface
    {
        public function configure(ConfigInterface $config): static
        {
            return $this;
        }

        public function exec(string $html): string
        {
            return '';
        }
    }

    $cleaner->using(new class implements ConfigInterface
    {
        public function getClass(): string
        {
            return NoSetupDriver::class;
        }
    });

    expect($cleaner->setup())->toBeFalse();
});
