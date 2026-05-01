<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Laravel\Nova\Events\ServingNova;
use Opscale\NovaPackageTools\NovaPackage;
use Opscale\NovaPackageTools\NovaPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

it('inherits from the spatie package service provider', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');
    });

    expect($novaPackageServiceProvider)->toBeInstanceOf(PackageServiceProvider::class);
});

it('returns a NovaPackage instance from newPackage', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');
    });

    expect($novaPackageServiceProvider->newPackage())->toBeInstanceOf(NovaPackage::class);
});

it('returns a fresh package instance on every newPackage call', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');
    });

    expect($novaPackageServiceProvider->newPackage())->not->toBe($novaPackageServiceProvider->newPackage());
});

it('runs the consumer packageBooted hook when boot completes', function (): void {
    $marker = new stdClass;
    $marker->called = false;

    $provider = new class($this->app) extends NovaPackageServiceProvider
    {
        public ?stdClass $marker = null;

        public function configurePackage(Package $package): void
        {
            $package->name('nova-package-tools');
        }

        public function packageBooted(): void
        {
            parent::packageBooted();

            if ($this->marker instanceof stdClass) {
                $this->marker->called = true;
            }
        }
    };

    $provider->marker = $marker;

    $provider->register();
    $provider->boot();

    expect($marker->called)->toBeTrue();
});

it('runs the packageServingNova hook when Nova starts serving', function (): void {
    clearNovaResources();

    $marker = new stdClass;
    $marker->called = false;
    $marker->event = null;

    $provider = new class($this->app) extends NovaPackageServiceProvider
    {
        public ?stdClass $marker = null;

        public function configurePackage(Package $package): void
        {
            $package->name('nova-package-tools');
        }

        public function packageServingNova(ServingNova $servingNova): void
        {
            if ($this->marker instanceof stdClass) {
                $this->marker->called = true;
                $this->marker->event = $servingNova;
            }
        }
    };

    $provider->marker = $marker;

    $provider->register();
    $provider->boot();

    expect($marker->called)->toBeFalse();

    $servingNova = new ServingNova($this->app, new Request);
    event($servingNova);

    expect($marker->called)->toBeTrue();
    expect($marker->event)->toBe($servingNova);
});

it('does not error when packageServingNova is not overridden', function (): void {
    clearNovaResources();

    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    expect(true)->toBeTrue();
});
