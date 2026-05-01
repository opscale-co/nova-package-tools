<?php

declare(strict_types=1);

namespace Opscale\NovaPackageTools\Concerns\Package;

trait HasNovaAssets
{
    /** @var list<array{name: string, path: string}> */
    public array $novaScripts = [];

    /** @var list<array{name: string, path: string}> */
    public array $novaStyles = [];

    /**
     * Register a Nova script asset (typically dist/js/tool.js or dist/js/field.js).
     *
     * @overridable
     */
    final public function hasNovaScript(string $name, string $path): static
    {
        $this->novaScripts[] = ['name' => $name, 'path' => $path];

        return $this;
    }

    /**
     * Register a Nova style asset (typically dist/css/tool.css or dist/css/field.css).
     *
     * @overridable
     */
    final public function hasNovaStyle(string $name, string $path): static
    {
        $this->novaStyles[] = ['name' => $name, 'path' => $path];

        return $this;
    }

    /**
     * Convenience for the typical Nova package layout: registers both the script
     * and the style under the same name from a base directory.
     *
     * @param  'tool'|'field'  $kind
     *
     * @overridable
     */
    final public function hasNovaAssets(string $name, string $basePath, string $kind = 'tool'): static
    {
        $base = rtrim($basePath, '/');

        return $this
            ->hasNovaScript($name, $base.'/js/'.$kind.'.js')
            ->hasNovaStyle($name, $base.'/css/'.$kind.'.css');
    }
}
