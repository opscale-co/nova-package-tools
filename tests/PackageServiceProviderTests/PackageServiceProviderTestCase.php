<?php

namespace Opscale\Tests\PackageServiceProviderTests;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Facade;
use Laravel\Nova\Nova;
use Opscale\Tests\TestCase;
use Opscale\Tests\TestPackage\Src\TestServiceProvider;
use ReflectionClass;
use Spatie\LaravelPackageTools\Package;

abstract class PackageServiceProviderTestCase extends TestCase
{
    protected function setUp(): void
    {
        TestServiceProvider::$configurePackageUsing = function (Package $package): void {
            $this->configurePackage($package);
        };

        parent::setUp();

        Event::fake();
    }

    protected function tearDown(): void
    {
        $this->clearNovaRegistrations();

        parent::tearDown();
    }

    abstract public function configurePackage(Package $package);

    protected function getPackageProviders($app)
    {
        return [
            TestServiceProvider::class,
        ];
    }

    protected function clearNovaRegistrations(): self
    {
        TestServiceProvider::reset();
        Facade::clearResolvedInstances();

        // Clear Nova resources
        $reflectionClass = new ReflectionClass(Nova::class);

        if ($reflectionClass->hasProperty('resources')) {
            $property = $reflectionClass->getProperty('resources');
            $property->setAccessible(true);
            $property->setValue(null, []);
        }

        if ($reflectionClass->hasProperty('customResources')) {
            $property = $reflectionClass->getProperty('customResources');
            $property->setAccessible(true);
            $property->setValue(null, []);
        }

        return $this;
    }
}
