<?php

declare(strict_types=1);

use Opscale\NovaPackageTools\NovaPackage;

it('starts with an empty listeners list', function (): void {
    expect((new NovaPackage)->listeners)
        ->toBeArray()
        ->toBeEmpty();
});

it('returns self from hasListener for chaining', function (): void {
    $package = new NovaPackage;

    expect($package->hasListener('Some\Event', 'Some\Listener'))->toBe($package);
});

it('returns self from hasListeners for chaining', function (): void {
    $package = new NovaPackage;

    expect($package->hasListeners(['Some\Event' => 'Some\Listener']))->toBe($package);
});

it('stores a listener registered via hasListener with default handle method', function (): void {
    $package = new NovaPackage;
    $package->hasListener('Some\Event', 'Some\Listener');

    expect($package->listeners)->toBe([
        ['event' => 'Some\Event', 'listener' => 'Some\Listener'],
    ]);
});

it('stores a listener tuple registered via hasListener', function (): void {
    $package = new NovaPackage;
    $package->hasListener('Some\Event', ['Some\Action', 'asListener']);

    expect($package->listeners)->toBe([
        ['event' => 'Some\Event', 'listener' => ['Some\Action', 'asListener']],
    ]);
});

it('registers multiple listeners via hasListeners map', function (): void {
    $package = new NovaPackage;
    $package->hasListeners([
        'Event\One' => 'Listener\One',
        'Event\Two' => ['Listener\Two', 'asListener'],
    ]);

    expect($package->listeners)->toBe([
        ['event' => 'Event\One', 'listener' => 'Listener\One'],
        ['event' => 'Event\Two', 'listener' => ['Listener\Two', 'asListener']],
    ]);
});

it('preserves previously registered listeners when more are added', function (): void {
    $package = new NovaPackage;

    $package->hasListener('Event\One', 'Listener\One');
    $package->hasListeners(['Event\Two' => 'Listener\Two']);

    expect($package->listeners)->toHaveCount(2);
});

it('allows the same event to have multiple listeners', function (): void {
    $package = new NovaPackage;

    $package
        ->hasListener('Event\One', 'Listener\One')
        ->hasListener('Event\One', ['Listener\Two', 'asListener']);

    expect($package->listeners)->toHaveCount(2);
});
