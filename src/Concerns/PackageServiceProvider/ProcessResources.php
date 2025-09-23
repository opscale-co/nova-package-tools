<?php

namespace Opscale\NovaPackageTools\Concerns\PackageServiceProvider;

use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Opscale\NovaPackageTools\NovaPackage;

trait ProcessResources
{
    final protected function bootPackageResources(): self
    {
        if (! $this->package instanceof NovaPackage) {
            return $this;
        }

        if ($this->package->resources === []) {
            return $this;
        }

        Nova::serving(function (ServingNova $servingNova): void {
            if ($this->package instanceof NovaPackage) {
                Nova::resources($this->package->resources);
            }
        });

        return $this;
    }
}
