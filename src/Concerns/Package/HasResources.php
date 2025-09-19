<?php

namespace Opscale\NovaPackageTools\Concerns\Package;

use Illuminate\Support\Collection;

trait HasResources
{
    /** @var array<int, class-string<\Laravel\Nova\Resource<\Illuminate\Database\Eloquent\Model>>> */
    public array $resources = [];

    /**
     * @param  class-string<\Laravel\Nova\Resource<\Illuminate\Database\Eloquent\Model>>  $resourceClassName
     *
     * @overridable
     */
    final public function hasResource(string $resourceClassName): static
    {
        $this->resources[] = $resourceClassName;

        return $this;
    }

    /**
     * @param  array<int, class-string<\Laravel\Nova\Resource<\Illuminate\Database\Eloquent\Model>>>|class-string<\Laravel\Nova\Resource<\Illuminate\Database\Eloquent\Model>>  ...$resourceClassNames
     *
     * @overridable
     */
    final public function hasResources(...$resourceClassNames): static
    {
        /** @var array<int, class-string<\Laravel\Nova\Resource<\Illuminate\Database\Eloquent\Model>>> $flattened */
        $flattened = (new Collection($resourceClassNames))
            ->flatten()
            ->toArray();

        $this->resources = array_merge(
            $this->resources,
            $flattened
        );

        return $this;
    }
}
