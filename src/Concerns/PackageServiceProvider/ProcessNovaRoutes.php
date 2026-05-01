<?php

declare(strict_types=1);

namespace Opscale\NovaPackageTools\Concerns\PackageServiceProvider;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Nova;
use Opscale\NovaPackageTools\NovaPackage;

trait ProcessNovaRoutes
{
    final protected function bootPackageNovaRoutes(): self
    {
        if (! $this->package instanceof NovaPackage) {
            return $this;
        }

        if ($this->package->novaApiRoutes === [] && $this->package->novaPageRoutes === []) {
            return $this;
        }

        /** @var Application $app */
        $app = $this->app;

        if ($app->routesAreCached()) {
            return $this;
        }

        foreach ($this->package->novaApiRoutes as $entry) {
            Route::middleware($entry['middleware'])
                ->prefix('nova-vendor/'.ltrim($entry['prefix'], '/'))
                ->group($entry['path']);
        }

        foreach ($this->package->novaPageRoutes as $entry) {
            Nova::router($entry['middleware'], $entry['uri'])
                ->group($entry['path']);
        }

        return $this;
    }
}
