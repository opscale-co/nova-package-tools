<?php

declare(strict_types=1);

use Opscale\Nova\SecondTestResource;
use Opscale\Nova\TestResource;
use Opscale\Nova\ThirdTestResource;
use Opscale\NovaPackageTools\NovaPackage;
use Spatie\LaravelPackageTools\Package;

it('extends the base spatie package class', function (): void {
    expect(new NovaPackage)->toBeInstanceOf(Package::class);
});

it('initializes with an empty resources array', function (): void {
    $novaPackage = new NovaPackage;

    expect($novaPackage->resources)
        ->toBeArray()
        ->toBeEmpty();
});

it('returns self from hasResource for method chaining', function (): void {
    $novaPackage = new NovaPackage;

    expect($novaPackage->hasResource(TestResource::class))->toBe($novaPackage);
});

it('returns self from hasResources for method chaining', function (): void {
    $novaPackage = new NovaPackage;

    expect($novaPackage->hasResources(TestResource::class))->toBe($novaPackage);
});

it('stores a resource added via hasResource', function (): void {
    $novaPackage = new NovaPackage;
    $novaPackage->hasResource(TestResource::class);

    expect($novaPackage->resources)
        ->toHaveCount(1)
        ->toContain(TestResource::class);
});

it('accepts resources as an array via hasResources', function (): void {
    $novaPackage = new NovaPackage;
    $novaPackage->hasResources([
        TestResource::class,
        SecondTestResource::class,
    ]);

    expect($novaPackage->resources)
        ->toHaveCount(2)
        ->toContain(TestResource::class)
        ->toContain(SecondTestResource::class);
});

it('accepts resources as variadic arguments via hasResources', function (): void {
    $novaPackage = new NovaPackage;
    $novaPackage->hasResources(
        TestResource::class,
        SecondTestResource::class,
        ThirdTestResource::class
    );

    expect($novaPackage->resources)
        ->toHaveCount(3)
        ->toContain(TestResource::class)
        ->toContain(SecondTestResource::class)
        ->toContain(ThirdTestResource::class);
});

it('preserves existing resources when more are added', function (): void {
    $novaPackage = new NovaPackage;

    $novaPackage->hasResource(TestResource::class);
    $novaPackage->hasResource(SecondTestResource::class);
    $novaPackage->hasResources([ThirdTestResource::class]);

    expect($novaPackage->resources)
        ->toHaveCount(3)
        ->toContain(TestResource::class)
        ->toContain(SecondTestResource::class)
        ->toContain(ThirdTestResource::class);
});

it('allows duplicate resources to be registered', function (): void {
    $novaPackage = new NovaPackage;

    $novaPackage->hasResource(TestResource::class);
    $novaPackage->hasResource(TestResource::class);

    expect($novaPackage->resources)->toHaveCount(2);
    expect($novaPackage->resources[0])->toBe(TestResource::class);
    expect($novaPackage->resources[1])->toBe(TestResource::class);
});

it('supports fluent chaining of hasResource and hasResources', function (): void {
    $novaPackage = new NovaPackage;

    $novaPackage
        ->hasResource(TestResource::class)
        ->hasResources([SecondTestResource::class, ThirdTestResource::class]);

    expect($novaPackage->resources)
        ->toHaveCount(3)
        ->toContain(TestResource::class)
        ->toContain(SecondTestResource::class)
        ->toContain(ThirdTestResource::class);
});
