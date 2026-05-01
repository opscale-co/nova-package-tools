<?php

declare(strict_types=1);

namespace Opscale\NovaPackageTools\Concerns\PackageServiceProvider;

use Laravel\Nova\Nova;
use Opscale\NovaPackageTools\NovaPackage;

trait ProcessNovaAssets
{
    final protected function bootPackageNovaAssets(): self
    {
        if (! $this->package instanceof NovaPackage) {
            return $this;
        }

        if ($this->package->novaScripts === [] && $this->package->novaStyles === []) {
            return $this;
        }

        $scripts = $this->package->novaScripts;
        $styles = $this->package->novaStyles;

        Nova::serving(function () use ($scripts, $styles): void {
            foreach ($scripts as $script) {
                Nova::script($script['name'], $script['path']);
            }

            foreach ($styles as $style) {
                Nova::style($style['name'], $style['path']);
            }
        });

        return $this;
    }
}
