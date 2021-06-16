<?php

namespace Phuclh\Affast;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AffastServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('affast-laravel')
            ->hasConfigFile()
            ->hasMigration('add_affast_column_to_users_table');
    }

    public function registeringPackage()
    {
        $this->app->bind(Affast::class, function () {
            return new Affast(config('services.affast.token'));
        });
    }
}
