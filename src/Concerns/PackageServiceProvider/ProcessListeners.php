<?php

declare(strict_types=1);

namespace Opscale\NovaPackageTools\Concerns\PackageServiceProvider;

use Illuminate\Support\Facades\Event;
use Opscale\NovaPackageTools\NovaPackage;

trait ProcessListeners
{
    final protected function bootPackageListeners(): self
    {
        if (! $this->package instanceof NovaPackage) {
            return $this;
        }

        if ($this->package->listeners === []) {
            return $this;
        }

        foreach ($this->package->listeners as $entry) {
            Event::listen($entry['event'], $entry['listener']);
        }

        return $this;
    }
}
