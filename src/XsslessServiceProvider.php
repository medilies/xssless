<?php

namespace Medilies\Xssless;

use Medilies\Xssless\Commands\XsslessCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class XsslessServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('xssless')
            ->hasConfigFile()
            ->hasCommand(XsslessCommand::class);
    }
}
