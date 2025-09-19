<?php

namespace Opscale\Tests\PackageServiceProviderTests\ResourcesTests;

use Laravel\Nova\Nova;
use Opscale\Nova\SecondTestResource;
use Opscale\Nova\TestResource;
use Opscale\Nova\ThirdTestResource;
use Opscale\NovaPackageTools\NovaPackage;
use Opscale\NovaPackageTools\NovaPackageServiceProvider;
use Opscale\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Spatie\LaravelPackageTools\Package;

#[CoversClass(NovaPackageServiceProvider::class)]
#[CoversClass(\Opscale\NovaPackageTools\Concerns\Package\HasResources::class)]
class ResourcesIntegrationTest extends TestCase
{
    #[Test]
    public function it_can_configure_resources_through_service_provider(): void
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
            public function configurePackage(Package $package): void
            {
                $package->name('test-nova-package');

                if ($package instanceof NovaPackage) {
                    $package
                        ->hasResource(TestResource::class)
                        ->hasResources(SecondTestResource::class, ThirdTestResource::class);
                }
            }
        };

        $provider->register();
        $provider->boot();

        $registeredResources = Nova::$resources;

        $this->assertContains(TestResource::class, $registeredResources);
        $this->assertContains(SecondTestResource::class, $registeredResources);
        $this->assertContains(ThirdTestResource::class, $registeredResources);
    }

    #[Test]
    public function it_inherits_all_functionality_from_base_package_service_provider(): void
    {
        $provider = new class($this->app) extends NovaPackageServiceProvider
        {
            public function configurePackage(Package $package): void
            {
                $package->name('test-nova-package');
            }
        };

        $this->assertInstanceOf(\Spatie\LaravelPackageTools\PackageServiceProvider::class, $provider);
    }

    #[Test]
    public function it_calls_package_booted_method_when_booted(): void
    {
        $provider = new class($this->app) extends NovaPackageServiceProvider
        {
            public function configurePackage(Package $package): void
            {
                $package->name('test-nova-package');
            }

            public function packageBooted(): void
            {
                parent::packageBooted();
                $GLOBALS['bootedCalled'] = true;
            }
        };

        $provider->register();
        $provider->boot();

        $this->assertTrue($GLOBALS['bootedCalled']);

        unset($GLOBALS['bootedCalled']);
    }
}
