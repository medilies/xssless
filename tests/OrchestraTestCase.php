<?php

namespace Tests;

use Medilies\Xssless\Laravel\XsslessServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class OrchestraTestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            XsslessServiceProvider::class,
        ];
    }
}
