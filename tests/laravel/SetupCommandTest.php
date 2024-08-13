<?php

use Tests\OrchestraTestCase;

test('xssless:setup', function () {
    /** @var OrchestraTestCase $this */
    $this->artisan('xssless:setup')
        ->assertExitCode(0);
});
