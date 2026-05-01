<?php

declare(strict_types=1);

namespace Opscale\NovaPackageTools\Concerns\Package;

use Closure;

trait HasListeners
{
    /** @var list<array{event: string, listener: string|Closure|array{0: string, 1: string}}> */
    public array $listeners = [];

    /**
     * @param  string|Closure|array{0: string, 1: string}  $listener
     *
     * @overridable
     */
    final public function hasListener(string $event, string|array|Closure $listener): static
    {
        $this->listeners[] = ['event' => $event, 'listener' => $listener];

        return $this;
    }

    /**
     * @param  array<string, string|Closure|array{0: string, 1: string}>  $listeners
     *
     * @overridable
     */
    final public function hasListeners(array $listeners): static
    {
        foreach ($listeners as $event => $listener) {
            $this->hasListener($event, $listener);
        }

        return $this;
    }
}
