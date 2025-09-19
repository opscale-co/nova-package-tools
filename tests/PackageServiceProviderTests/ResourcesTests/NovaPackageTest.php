<?php

namespace Opscale\Tests\PackageServiceProviderTests\ResourcesTests;

use Opscale\Nova\SecondTestResource;
use Opscale\Nova\TestResource;
use Opscale\Nova\ThirdTestResource;
use Opscale\NovaPackageTools\NovaPackage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(NovaPackage::class)]
class NovaPackageTest extends TestCase
{
    #[Test]
    public function it_extends_the_base_package_class(): void
    {
        $novaPackage = new NovaPackage('test');

        $this->assertInstanceOf(\Spatie\LaravelPackageTools\Package::class, $novaPackage);
    }

    #[Test]
    public function it_initializes_with_an_empty_resources_array(): void
    {
        $novaPackage = new NovaPackage('test');

        $this->assertIsArray($novaPackage->resources);
        $this->assertEmpty($novaPackage->resources);
    }

    #[Test]
    public function it_returns_self_for_method_chaining(): void
    {
        $novaPackage = new NovaPackage('test');

        $result = $novaPackage->hasResource(TestResource::class);
        $this->assertSame($novaPackage, $result);

        $result = $novaPackage->hasResources(SecondTestResource::class);
        $this->assertSame($novaPackage, $result);
    }

    #[Test]
    public function it_preserves_all_resources_when_adding_multiple_times(): void
    {
        $novaPackage = new NovaPackage('test');

        $novaPackage->hasResource(TestResource::class);
        $novaPackage->hasResource(SecondTestResource::class);
        $novaPackage->hasResources([ThirdTestResource::class]);

        $this->assertCount(3, $novaPackage->resources);
        $this->assertContains(TestResource::class, $novaPackage->resources);
        $this->assertContains(SecondTestResource::class, $novaPackage->resources);
        $this->assertContains(ThirdTestResource::class, $novaPackage->resources);
    }

    #[Test]
    public function it_can_handle_nested_arrays_in_has_resources(): void
    {
        $novaPackage = new NovaPackage('test');

        $novaPackage->hasResources([
            TestResource::class,
            [SecondTestResource::class, ThirdTestResource::class],
        ]);

        $this->assertCount(3, $novaPackage->resources);
        $this->assertContains(TestResource::class, $novaPackage->resources);
        $this->assertContains(SecondTestResource::class, $novaPackage->resources);
        $this->assertContains(ThirdTestResource::class, $novaPackage->resources);
    }

    #[Test]
    public function it_allows_duplicate_resources(): void
    {
        $novaPackage = new NovaPackage('test');

        $novaPackage->hasResource(TestResource::class);
        $novaPackage->hasResource(TestResource::class);

        $this->assertCount(2, $novaPackage->resources);
        $this->assertEquals(TestResource::class, $novaPackage->resources[0]);
        $this->assertEquals(TestResource::class, $novaPackage->resources[1]);
    }
}
