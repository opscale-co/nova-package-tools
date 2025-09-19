<?php

namespace Opscale\Tests\PackageServiceProviderTests\ResourcesTests;

use Laravel\Nova\Nova;
use Opscale\Nova\SecondTestResource;
use Opscale\Nova\TestResource;
use Opscale\Nova\ThirdTestResource;
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
class PackageHasResourcesTest extends TestCase
{
    #[Test]
    public function it_can_register_multiple_nova_resources(): void
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
                    $package->hasResources(
                        TestResource::class,
                        SecondTestResource::class,
                        ThirdTestResource::class
                    );
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
    public function it_can_accept_resources_as_an_array(): void
    {
        $novaPackage = new NovaPackage('test');
        $novaPackage->hasResources([
            TestResource::class,
            SecondTestResource::class,
        ]);

        $this->assertContains(TestResource::class, $novaPackage->resources);
        $this->assertContains(SecondTestResource::class, $novaPackage->resources);
        $this->assertCount(2, $novaPackage->resources);
    }

    #[Test]
    public function it_can_accept_resources_as_separate_arguments(): void
    {
        $novaPackage = new NovaPackage('test');
        $novaPackage->hasResources(
            TestResource::class,
            SecondTestResource::class,
            ThirdTestResource::class
        );

        $this->assertContains(TestResource::class, $novaPackage->resources);
        $this->assertContains(SecondTestResource::class, $novaPackage->resources);
        $this->assertContains(ThirdTestResource::class, $novaPackage->resources);
        $this->assertCount(3, $novaPackage->resources);
    }

    #[Test]
    public function it_can_chain_multiple_has_resource_calls(): void
    {
        $novaPackage = new NovaPackage('test');
        $novaPackage
            ->hasResource(TestResource::class)
            ->hasResource(SecondTestResource::class);

        $this->assertContains(TestResource::class, $novaPackage->resources);
        $this->assertContains(SecondTestResource::class, $novaPackage->resources);
        $this->assertCount(2, $novaPackage->resources);
    }

    #[Test]
    public function it_can_mix_has_resource_and_has_resources_calls(): void
    {
        $novaPackage = new NovaPackage('test');
        $novaPackage
            ->hasResource(TestResource::class)
            ->hasResources([SecondTestResource::class, ThirdTestResource::class]);

        $this->assertContains(TestResource::class, $novaPackage->resources);
        $this->assertContains(SecondTestResource::class, $novaPackage->resources);
        $this->assertContains(ThirdTestResource::class, $novaPackage->resources);
        $this->assertCount(3, $novaPackage->resources);
    }
}
