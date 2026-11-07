<?php

namespace Opscale\NovaPackageTools\Concerns\Package;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait HasResources
{
    /** @var array<int, class-string<\Laravel\Nova\Resource<Model>>> */
    public array $resources = [];

    /**
     * @param  class-string<\Laravel\Nova\Resource<Model>>  $resourceClassName
     *
     * @overridable
     */
    final public function hasResource(string $resourceClassName): static
    {
        $this->resources[] = $resourceClassName;

        return $this;
    }

    /**
     * @param  array<int, class-string<\Laravel\Nova\Resource<Model>>>|class-string<\Laravel\Nova\Resource<Model>>  ...$resourceClassNames
     *
     * @overridable
     */
    final public function hasResources(...$resourceClassNames): static
    {
        /** @var array<int, class-string<\Laravel\Nova\Resource<Model>>> $flattened */
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
