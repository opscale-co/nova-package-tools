<?php

declare(strict_types=1);

namespace Opscale\NovaPackageTools;

use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Opscale\NovaPackageTools\Concerns\PackageServiceProvider\ProcessListeners;
use Opscale\NovaPackageTools\Concerns\PackageServiceProvider\ProcessNovaAssets;
use Opscale\NovaPackageTools\Concerns\PackageServiceProvider\ProcessNovaRoutes;
use Opscale\NovaPackageTools\Concerns\PackageServiceProvider\ProcessResources;
use Opscale\NovaPackageTools\Concerns\PackageServiceProvider\ProcessServingCallbacks;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

abstract class NovaPackageServiceProvider extends PackageServiceProvider
{
    use ProcessListeners;
    use ProcessNovaAssets;
    use ProcessNovaRoutes;
    use ProcessResources;
    use ProcessServingCallbacks;

    final public function boot(): void
    {
        $package = parent::boot();
        $package
            ->bootPackageResources()
            ->bootPackageListeners()
            ->bootPackageNovaAssets()
            ->bootPackageNovaRoutes()
            ->bootPackageServingCallbacks();

        Nova::serving(function (ServingNova $servingNova): void {
            $this->packageServingNova($servingNova);
        });
    }

    /**
     * Override in your service provider to run setup every time Nova starts serving a request.
     * Default is a no-op, so the cost when not overridden is negligible.
     *
     * @phpstan-ignore solid.ocp.conditionalOverride
     */
    public function packageServingNova(ServingNova $servingNova): void
    {
        //
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
