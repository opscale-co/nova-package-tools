<?php

declare(strict_types=1);

use Opscale\NovaPackageTools\NovaPackage;

it('starts with no serving callbacks', function (): void {
    expect((new NovaPackage)->servingCallbacks)
        ->toBeArray()
        ->toBeEmpty();
});

it('returns self from servingNova for chaining', function (): void {
    $package = new NovaPackage;
    $callback = static function (): void {};

    expect($package->servingNova($callback))->toBe($package);
});

it('stores callbacks in registration order', function (): void {
    $package = new NovaPackage;

    $first = static function (): void {};
    $second = static function (): void {};

    $package->servingNova($first)->servingNova($second);

    expect($package->servingCallbacks)
        ->toHaveCount(2)
        ->and($package->servingCallbacks[0])->toBe($first)
        ->and($package->servingCallbacks[1])->toBe($second);
});
