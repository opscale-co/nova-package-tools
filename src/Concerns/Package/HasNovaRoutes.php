<?php

declare(strict_types=1);

namespace Opscale\NovaPackageTools\Concerns\Package;

use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Http\Middleware\Authorize;

trait HasNovaRoutes
{
    /** @var list<array{path: string, prefix: string, middleware: list<string>}> */
    public array $novaApiRoutes = [];

    /** @var list<array{path: string, uri: string, middleware: list<string>}> */
    public array $novaPageRoutes = [];

    /**
     * Register a route file mounted under `nova-vendor/{prefix}` for XHR endpoints
     * called from a Nova tool's Vue components.
     *
     * @param  list<string>|null  $middleware
     *
     * @overridable
     */
    final public function hasNovaApiRoute(string $path, string $prefix, ?array $middleware = null): static
    {
        $this->novaApiRoutes[] = [
            'path' => $path,
            'prefix' => $prefix,
            'middleware' => $middleware ?? ['nova', Authorize::class],
        ];

        return $this;
    }

    /**
     * Register a route file mounted via Nova::router() for full Nova pages
     * with the standard Nova layout.
     *
     * @param  list<string>|null  $middleware
     *
     * @overridable
     */
    final public function hasNovaPageRoute(string $path, string $uri, ?array $middleware = null): static
    {
        $this->novaPageRoutes[] = [
            'path' => $path,
            'uri' => $uri,
            'middleware' => $middleware ?? ['nova', Authenticate::class, Authorize::class],
        ];

        return $this;
    }
}
