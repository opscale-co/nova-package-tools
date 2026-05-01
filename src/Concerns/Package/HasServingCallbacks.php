<?php

declare(strict_types=1);

namespace Opscale\NovaPackageTools\Concerns\Package;

use Closure;

trait HasServingCallbacks
{
    /** @var list<Closure> */
    public array $servingCallbacks = [];

    /**
     * Register a callback to run inside Nova::serving().
     *
     * @overridable
     */
    final public function servingNova(Closure $callback): static
    {
        $this->servingCallbacks[] = $callback;

        return $this;
    }
}
