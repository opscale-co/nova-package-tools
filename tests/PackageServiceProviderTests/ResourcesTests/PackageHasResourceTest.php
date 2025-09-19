<?php

namespace Opscale\Tests\PackageServiceProviderTests\ResourcesTests;

use Laravel\Nova\Nova;
use Opscale\Nova\TestResource;
use Opscale\NovaPackageTools\Concerns\Package\HasResources;
use Opscale\NovaPackageTools\NovaPackage;
use Opscale\NovaPackageTools\NovaPackageServiceProvider;
use Opscale\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;

#[CoversClass(NovaPackage::class)]
#[CoversClass(HasResources::class)]
#[CoversClass(NovaPackageServiceProvider::class)]
class PackageHasResourceTest extends TestCase
{
    #[Test]
    public function it_can_register_a_single_nova_resource(): void
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
                    $package->hasResource(TestResource::class);
                }
            }
        };

        $provider->register();
        $provider->boot();

        $registeredResources = Nova::$resources;

        $this->assertContains(TestResource::class, $registeredResources);
    }

    #[Test]
    public function it_stores_the_resource_in_the_package_resources_array(): void
    {
        $novaPackage = new NovaPackage('test');
        $novaPackage->hasResource(TestResource::class);

        $this->assertContains(TestResource::class, $novaPackage->resources);
        $this->assertCount(1, $novaPackage->resources);
    }
}
