<?php

namespace Opscale\Tests\PackageServiceProviderTests\ResourcesTests;

use Laravel\Nova\Nova;
use Opscale\Nova\SecondTestResource;
use Opscale\Nova\TestResource;
use Opscale\NovaPackageTools\Concerns\PackageServiceProvider\ProcessResources;
use Opscale\NovaPackageTools\NovaPackageServiceProvider;
use Opscale\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;

#[CoversClass(ProcessResources::class)]
#[CoversClass(NovaPackageServiceProvider::class)]
#[CoversClass(\Opscale\NovaPackageTools\Concerns\Package\HasResources::class)]
class ProcessResourcesTest extends TestCase
{
    #[Test]
    public function it_boots_resources_when_package_is_booted(): void
    {
        // Clear Nova resources first
        $reflectionClass = new ReflectionClass(Nova::class);
        if ($reflectionClass->hasProperty('resources')) {
            $property = $reflectionClass->getProperty('resources');
            $property->setAccessible(true);
            $property->setValue(null, []);
        }

        $provider = new class($this->app) extends NovaPackageServiceProvider
        {
            public function configurePackage(\Spatie\LaravelPackageTools\Package $package): void
            {
                $package->name('nova-package-tools');

                if ($package instanceof \Opscale\NovaPackageTools\NovaPackage) {
                    $package
                        ->hasResource(TestResource::class)
                        ->hasResource(SecondTestResource::class);
                }
            }
        };

        $provider->register();
        $provider->boot();

        $registeredResources = Nova::$resources;

        $this->assertContains(TestResource::class, $registeredResources);
        $this->assertContains(SecondTestResource::class, $registeredResources);
    }

    #[Test]
    public function it_does_not_register_resources_if_none_are_configured(): void
    {
        // Clear current registrations
        $reflectionClass = new ReflectionClass(Nova::class);
        if ($reflectionClass->hasProperty('resources')) {
            $property = $reflectionClass->getProperty('resources');
            $property->setAccessible(true);
            $property->setValue(null, []);
        }

        // Create a service provider without resources
        $provider = new class($this->app) extends NovaPackageServiceProvider
        {
            public function configurePackage(\Spatie\LaravelPackageTools\Package $package): void
            {
                $package->name('empty-package');
            }
        };

        $provider->register();
        $provider->boot();

        $registeredResources = Nova::$resources;

        $this->assertEmpty($registeredResources);
    }
}
