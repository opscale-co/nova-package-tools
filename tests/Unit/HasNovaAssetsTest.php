<?php

declare(strict_types=1);

use Opscale\NovaPackageTools\NovaPackage;

it('starts with no scripts or styles registered', function (): void {
    $package = new NovaPackage;

    expect($package->novaScripts)->toBeEmpty();
    expect($package->novaStyles)->toBeEmpty();
});

it('returns self from hasNovaScript for chaining', function (): void {
    $package = new NovaPackage;

    expect($package->hasNovaScript('name', '/path/to/script.js'))->toBe($package);
});

it('returns self from hasNovaStyle for chaining', function (): void {
    $package = new NovaPackage;

    expect($package->hasNovaStyle('name', '/path/to/style.css'))->toBe($package);
});

it('returns self from hasNovaAssets for chaining', function (): void {
    $package = new NovaPackage;

    expect($package->hasNovaAssets('name', '/dist'))->toBe($package);
});

it('stores a script asset', function (): void {
    $package = new NovaPackage;
    $package->hasNovaScript('my-tool', '/dist/js/tool.js');

    expect($package->novaScripts)->toBe([
        ['name' => 'my-tool', 'path' => '/dist/js/tool.js'],
    ]);
});

it('stores a style asset', function (): void {
    $package = new NovaPackage;
    $package->hasNovaStyle('my-tool', '/dist/css/tool.css');

    expect($package->novaStyles)->toBe([
        ['name' => 'my-tool', 'path' => '/dist/css/tool.css'],
    ]);
});

it('hasNovaAssets registers tool script and style by convention', function (): void {
    $package = new NovaPackage;
    $package->hasNovaAssets('nova-widgets', '/abs/dist');

    expect($package->novaScripts)->toBe([
        ['name' => 'nova-widgets', 'path' => '/abs/dist/js/tool.js'],
    ]);
    expect($package->novaStyles)->toBe([
        ['name' => 'nova-widgets', 'path' => '/abs/dist/css/tool.css'],
    ]);
});

it('hasNovaAssets supports field convention', function (): void {
    $package = new NovaPackage;
    $package->hasNovaAssets('nova-bpmn-field', '/abs/dist', 'field');

    expect($package->novaScripts[0]['path'])->toBe('/abs/dist/js/field.js');
    expect($package->novaStyles[0]['path'])->toBe('/abs/dist/css/field.css');
});

it('hasNovaAssets strips trailing slash from base path', function (): void {
    $package = new NovaPackage;
    $package->hasNovaAssets('name', '/abs/dist/');

    expect($package->novaScripts[0]['path'])->toBe('/abs/dist/js/tool.js');
});
