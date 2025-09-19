<?php

namespace Opscale\NovaPackageTools\Concerns\PackageServiceProvider;

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

        Nova::resources($this->package->resources);

        return $this;
    }
}
