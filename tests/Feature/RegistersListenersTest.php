<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Opscale\NovaPackageTools\NovaPackage;
use Spatie\LaravelPackageTools\Package;

it('registers a listener with the Laravel event dispatcher', function (): void {
    $event = 'test.event.'.uniqid();
    $listener = 'Some\Listener';

    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package) use ($event, $listener): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasListener($event, $listener);
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    expect(Event::hasListeners($event))->toBeTrue();
});

it('registers an Opscale Action listener tuple', function (): void {
    $event = 'test.event.'.uniqid();

    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package) use ($event): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasListener($event, ['Some\Action', 'asListener']);
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    expect(Event::hasListeners($event))->toBeTrue();
});

it('actually invokes the registered listener when the event fires', function (): void {
    $event = 'test.event.'.uniqid();
    $marker = new stdClass;
    $marker->fired = false;

    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package) use ($event, $marker): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasListener($event, function () use ($marker): void {
                $marker->fired = true;
            });
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    Event::dispatch($event);

    expect($marker->fired)->toBeTrue();
});

it('does not register any listeners when none are configured', function (): void {
    $event = 'test.event.'.uniqid();

    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package): void {
        $package->name('nova-package-tools');
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    expect(Event::hasListeners($event))->toBeFalse();
});

it('registers all listeners passed via hasListeners', function (): void {
    $eventA = 'test.a.'.uniqid();
    $eventB = 'test.b.'.uniqid();

    $novaPackageServiceProvider = makeProvider($this->app, function (Package $package) use ($eventA, $eventB): void {
        $package->name('nova-package-tools');

        if ($package instanceof NovaPackage) {
            $package->hasListeners([
                $eventA => 'Listener\A',
                $eventB => ['Listener\B', 'asListener'],
            ]);
        }
    });

    $novaPackageServiceProvider->register();
    $novaPackageServiceProvider->boot();

    expect(Event::hasListeners($eventA))->toBeTrue();
    expect(Event::hasListeners($eventB))->toBeTrue();
});
