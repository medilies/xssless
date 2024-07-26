<?php

namespace Medilies\Xssless\Tests;

use Medilies\Xssless\XsslessServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            XsslessServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app) {}
}
