<?php

use Tests\Mocks\NoSetupDriverConfig;
use Tests\OrchestraTestCase;

test('xssless:setup', function () {
    /** @var OrchestraTestCase $this */
    $this->artisan('xssless:setup')
        ->expectsOutput('Setup done.')
        ->assertExitCode(0);
});

test('NoSetupDriverConfig', function () {
    config([
        'xssless.default' => 'no-setup',
        'xssless.drivers.no-setup' => new NoSetupDriverConfig,
    ]);

    $this->artisan('xssless:setup')
        ->expectsOutput('The current driver has no setup.')
        ->assertExitCode(0);
});
