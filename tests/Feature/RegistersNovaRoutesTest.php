<?php

declare(strict_types=1);

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Opscale\NovaPackageTools\NovaPackage;
use Spatie\LaravelPackageTools\Package;

beforeEach(function (): void {
    clearNovaResources();
});

afterEach(function (): void {
    clearNovaResources();
});

it('registers an api route under nova-vendor with the configured prefix', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasNovaApiRoute(
                __DIR__.'/../fixtures/routes/api.php',
                'opscale-co/nova-test'
            );
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    /** @var Router $router */
    $router = $this->app['router'];
    $router->getRoutes()->refreshNameLookups();
    $matched = $router->getRoutes()->getByName('opscale.test.api.ping');

    assert($matched instanceof Route);
    expect($matched->uri())->toBe('nova-vendor/opscale-co/nova-test/ping');
    expect($matched->gatherMiddleware())->toContain('nova');
});

it('registers a page route via Nova::router', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasNovaPageRoute(
                __DIR__.'/../fixtures/routes/inertia.php',
                '/nova-test'
            );
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    /** @var Router $router */
    $router = $this->app['router'];
    $router->getRoutes()->refreshNameLookups();
    $matched = $router->getRoutes()->getByName('opscale.test.inertia.ping');

    assert($matched instanceof Route);
    expect($matched->uri())->toBe('nova/nova-test/ping');
});

it('skips registration when routes are cached', function (): void {
    /** @phpstan-ignore method.notFound */
    $legacyMock = Mockery::mock($this->app)
        ->makePartial()
        ->shouldReceive('routesAreCached')
        ->andReturn(true)
        ->getMock();

    /** @phpstan-ignore argument.type */
    $novaPackageServiceProvider = makeProvider($legacyMock, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasNovaApiRoute(
                __DIR__.'/../fixtures/routes/api.php',
                'opscale-co/skipped'
            );
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    /** @var Router $router */
    $router = $this->app['router'];

    expect($router->getRoutes()->getByName('opscale.test.api.ping'))->toBeNull();
});

it('does not register routes when none are configured', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    /** @var Router $router */
    $router = $this->app['router'];

    expect($router->getRoutes()->getByName('opscale.test.api.ping'))->toBeNull();
});

it('registers multiple api routes with different prefixes', function (): void {
    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package
                ->hasNovaApiRoute(__DIR__.'/../fixtures/routes/api.php', 'foo')
                ->hasNovaPageRoute(__DIR__.'/../fixtures/routes/inertia.php', '/bar');
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    /** @var Router $router */
    $router = $this->app['router'];
    $router->getRoutes()->refreshNameLookups();
    $api = $router->getRoutes()->getByName('opscale.test.api.ping');
    $page = $router->getRoutes()->getByName('opscale.test.inertia.ping');

    assert($api instanceof Route);
    assert($page instanceof Route);
    expect($api->uri())->toBe('nova-vendor/foo/ping');
    expect($page->uri())->toBe('nova/bar/ping');
});
