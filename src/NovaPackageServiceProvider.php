<?php

namespace Opscale\NovaPackageTools;

use Opscale\NovaPackageTools\Concerns\PackageServiceProvider\ProcessResources;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

abstract class NovaPackageServiceProvider extends PackageServiceProvider
{
    use ProcessResources;

    final public function boot(): void
    {
        $package = parent::boot();
        $package->bootPackageResources();
    }

    /**
     * @phpstan-ignore solid.lsp.parentCall
     */
    final public function newPackage(): NovaPackage
    {
        /** @phpstan-ignore solid.dip.disallowInstantiation */
        return new NovaPackage;
    }

    abstract public function configurePackage(Package $package): void;
}
