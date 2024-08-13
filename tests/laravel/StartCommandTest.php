<?php

use Tests\OrchestraTestCase;

test('xssless:start with non-startable driver', function () {
    /** @var OrchestraTestCase $this */
    $this->artisan('xssless:start')
        ->expectsOutput('The current driver is not a service to start.')
        ->assertExitCode(0);
});
