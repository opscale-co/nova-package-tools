<?php

declare(strict_types=1);

use Illuminate\Contracts\Foundation\Application;
use Laravel\Nova\Nova;
use Opscale\NovaPackageTools\NovaPackageServiceProvider;
use Opscale\Tests\TestCase;
use Spatie\LaravelPackageTools\Package;

uses(TestCase::class)->in('Feature');

function makeProvider(Application $application, Closure $configure): NovaPackageServiceProvider
{
    return new class($application, $configure) extends NovaPackageServiceProvider
    {
        /** @var Closure(Package): void */
        private Closure $configure;

        public function __construct(Application $app, Closure $configure)
        {
            parent::__construct($app);
            $this->configure = $configure;
        }

        public function configurePackage(Package $package): void
        {
            ($this->configure)($package);
        }
    };
}

function clearNovaResources(): void
{
    $reflection = new ReflectionClass(Nova::class);

    foreach (['resources', 'customResources'] as $name) {
        if ($reflection->hasProperty($name)) {
            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue(null, []);
        }
    }
}
