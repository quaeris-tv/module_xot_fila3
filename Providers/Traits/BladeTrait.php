<?php

declare(strict_types=1);

namespace Modules\Xot\Providers\Traits;

use Illuminate\View\Compilers\BladeCompiler;

trait BladeTrait
{
    /**
     * Register Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        $this->app->afterResolving(
            'blade.compiler',
            static function (BladeCompiler $bladeCompiler): void {
                dddx(['bladeCompiler' => $bladeCompiler]);
            }
        );
    }

    // end registerBladeDirectives
}
