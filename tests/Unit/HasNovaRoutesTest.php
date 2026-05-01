<?php

declare(strict_types=1);

use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Http\Middleware\Authorize;
use Opscale\NovaPackageTools\NovaPackage;

it('starts with no routes registered', function (): void {
    $package = new NovaPackage;

    expect($package->novaApiRoutes)->toBeEmpty();
    expect($package->novaPageRoutes)->toBeEmpty();
});

it('returns self from hasNovaApiRoute for chaining', function (): void {
    $package = new NovaPackage;

    expect($package->hasNovaApiRoute('/path/api.php', 'opscale-co/foo'))->toBe($package);
});

it('returns self from hasNovaPageRoute for chaining', function (): void {
    $package = new NovaPackage;

    expect($package->hasNovaPageRoute('/path/inertia.php', '/foo'))->toBe($package);
});

it('stores api route with default Nova middleware', function (): void {
    $package = new NovaPackage;
    $package->hasNovaApiRoute('/path/api.php', 'opscale-co/foo');

    expect($package->novaApiRoutes)->toBe([
        [
            'path' => '/path/api.php',
            'prefix' => 'opscale-co/foo',
            'middleware' => ['nova', Authorize::class],
        ],
    ]);
});

it('accepts custom middleware for api routes', function (): void {
    $package = new NovaPackage;
    $package->hasNovaApiRoute('/path/api.php', 'foo', ['web', 'auth']);

    expect($package->novaApiRoutes[0]['middleware'])->toBe(['web', 'auth']);
});

it('stores page route with default Nova middleware', function (): void {
    $package = new NovaPackage;
    $package->hasNovaPageRoute('/path/inertia.php', '/foo');

    expect($package->novaPageRoutes)->toBe([
        [
            'path' => '/path/inertia.php',
            'uri' => '/foo',
            'middleware' => ['nova', Authenticate::class, Authorize::class],
        ],
    ]);
});

it('accepts custom middleware for page routes', function (): void {
    $package = new NovaPackage;
    $package->hasNovaPageRoute('/path/inertia.php', '/foo', ['custom']);

    expect($package->novaPageRoutes[0]['middleware'])->toBe(['custom']);
});

it('preserves multiple route registrations', function (): void {
    $package = new NovaPackage;

    $package
        ->hasNovaApiRoute('/a.php', 'one')
        ->hasNovaApiRoute('/b.php', 'two')
        ->hasNovaPageRoute('/page.php', '/page');

    expect($package->novaApiRoutes)->toHaveCount(2);
    expect($package->novaPageRoutes)->toHaveCount(1);
});
