<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Laravel\Nova\Script;
use Laravel\Nova\Style;
use Opscale\NovaPackageTools\NovaPackage;
use Spatie\LaravelPackageTools\Package;

beforeEach(function (): void {
    clearNovaResources();
    Nova::$scripts = [];
    Nova::$styles = [];
});

afterEach(function (): void {
    clearNovaResources();
    Nova::$scripts = [];
    Nova::$styles = [];
});

it('registers a single script with Nova on ServingNova', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasNovaScript('my-tool', '/dist/js/tool.js');
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    $names = array_map(static fn (Script $script): string => (string) $script->name(), Nova::$scripts);
    expect($names)->toContain('my-tool');
});

it('registers a single style with Nova on ServingNova', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasNovaStyle('my-tool', '/dist/css/tool.css');
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    $names = array_map(static fn (Style $style): string => (string) $style->name(), Nova::$styles);
    expect($names)->toContain('my-tool');
});

it('hasNovaAssets registers both script and style under the same name', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasNovaAssets('nova-widgets', '/abs/dist');
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    $scriptNames = array_map(static fn (Script $script): string => (string) $script->name(), Nova::$scripts);
    $styleNames = array_map(static fn (Style $style): string => (string) $style->name(), Nova::$styles);

    expect($scriptNames)->toContain('nova-widgets');
    expect($styleNames)->toContain('nova-widgets');
});

it('does not register assets before ServingNova fires', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasNovaScript('my-tool', '/dist/js/tool.js');
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    expect(Nova::$scripts)->toBeEmpty();

    event(new ServingNova($this->app, new Request));

    expect(Nova::$scripts)->not->toBeEmpty();
});

it('does not call Nova::serving when no assets are registered', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    event(new ServingNova($this->app, new Request));

    expect(Nova::$scripts)->toBeEmpty();
    expect(Nova::$styles)->toBeEmpty();
});
