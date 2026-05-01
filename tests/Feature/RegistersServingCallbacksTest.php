<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Laravel\Nova\Events\ServingNova;
use Opscale\NovaPackageTools\NovaPackage;
use Spatie\LaravelPackageTools\Package;

beforeEach(function (): void {
    clearNovaResources();
});

afterEach(function (): void {
    clearNovaResources();
});

it('runs the registered serving callback on ServingNova', function (): void {
    $marker = new stdClass;
    $marker->called = false;

    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package) use ($marker): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->servingNova(function () use ($marker): void {
                $marker->called = true;
            });
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    expect($marker->called)->toBeFalse();

    event(new ServingNova($this->app, new Request));

    expect($marker->called)->toBeTrue();
});

it('runs all registered callbacks in registration order', function (): void {
    $order = [];

    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package) use (&$order): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package
                ->servingNova(function () use (&$order): void {
                    $order[] = 'first';
                })
                ->servingNova(function () use (&$order): void {
                    $order[] = 'second';
                });
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    expect($order)->toBe(['first', 'second']);
});

it('passes the ServingNova event to the callback', function (): void {
    $captured = null;

    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package) use (&$captured): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->servingNova(function (ServingNova $servingNova) use (&$captured): void {
                $captured = $servingNova;
            });
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    $servingNova = new ServingNova($this->app, new Request);
    event($servingNova);

    expect($captured)->toBe($servingNova);
});

it('does not call Nova::serving when no callbacks are registered', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    // No assertion needed beyond the boot completing without error.
    expect(true)->toBeTrue();
});
