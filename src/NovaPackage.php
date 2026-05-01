<?php

declare(strict_types=1);

namespace Opscale\NovaPackageTools;

use Opscale\NovaPackageTools\Concerns\Package\HasListeners;
use Opscale\NovaPackageTools\Concerns\Package\HasNovaAssets;
use Opscale\NovaPackageTools\Concerns\Package\HasNovaRoutes;
use Opscale\NovaPackageTools\Concerns\Package\HasResources;
use Opscale\NovaPackageTools\Concerns\Package\HasServingCallbacks;
use Spatie\LaravelPackageTools\Package;

class NovaPackage extends Package
{
    use HasListeners;
    use HasNovaAssets;
    use HasNovaRoutes;
    use HasResources;
    use HasServingCallbacks;
}
