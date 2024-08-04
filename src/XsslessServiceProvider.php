<?php

namespace Medilies\Xssless;

use Medilies\Xssless\Commands\SetupCommand;
use Medilies\Xssless\Commands\StartCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class XsslessServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('xssless')
            ->hasConfigFile()
            ->hasCommand(SetupCommand::class)
            ->hasCommand(StartCommand::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile();
            });
    }
}
