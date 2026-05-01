<?php

declare(strict_types=1);

namespace Opscale\NovaPackageTools\Concerns\PackageServiceProvider;

use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Opscale\NovaPackageTools\NovaPackage;

trait ProcessServingCallbacks
{
    final protected function bootPackageServingCallbacks(): self
    {
        if (! $this->package instanceof NovaPackage) {
            return $this;
        }

        if ($this->package->servingCallbacks === []) {
            return $this;
        }

        $callbacks = $this->package->servingCallbacks;

        Nova::serving(function (ServingNova $servingNova) use ($callbacks): void {
            foreach ($callbacks as $callback) {
                $callback($servingNova);
            }
        });

        return $this;
    }
}
