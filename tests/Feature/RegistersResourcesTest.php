<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Opscale\Nova\SecondTestResource;
use Opscale\Nova\TestResource;
use Opscale\Nova\ThirdTestResource;
use Opscale\NovaPackageTools\NovaPackage;
use Spatie\LaravelPackageTools\Package;

beforeEach(function (): void {
    clearNovaResources();
});

afterEach(function (): void {
    clearNovaResources();
});

it('registers a single resource through the service provider', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasResource(TestResource::class);
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    expect(Nova::$resources)->toContain(TestResource::class);
});

it('registers multiple resources through the service provider', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasResources(
                TestResource::class,
                SecondTestResource::class,
                ThirdTestResource::class
            );
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    expect(Nova::$resources)
        ->toContain(TestResource::class)
        ->toContain(SecondTestResource::class)
        ->toContain(ThirdTestResource::class);
});

it('registers resources configured via fluent chaining', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package
                ->hasResource(TestResource::class)
                ->hasResources(SecondTestResource::class, ThirdTestResource::class);
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    expect(Nova::$resources)
        ->toContain(TestResource::class)
        ->toContain(SecondTestResource::class)
        ->toContain(ThirdTestResource::class);
});

it('does not register any resources when none are configured', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('empty-package');
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    expect(Nova::$resources)->toBeEmpty();
});

it('only registers Nova resources after the ServingNova event fires', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasResource(TestResource::class);
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    expect(Nova::$resources)->not->toContain(TestResource::class);

    event(new ServingNova($this->app, new Request));

    expect(Nova::$resources)->toContain(TestResource::class);
});
