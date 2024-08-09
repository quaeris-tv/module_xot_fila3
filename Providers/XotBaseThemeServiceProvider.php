<?php

declare(strict_types=1);

namespace Modules\Xot\Providers;

use Illuminate\Support\Facades\Blade;
use Modules\Xot\Services\BladeService;
use Modules\Xot\Services\LivewireService;

/**
 * Class XotBaseThemeServiceProvider.
 *
 * @property string $dir
 * @property string $name
 */
abstract class XotBaseThemeServiceProvider
{
    public string $dir = '';

    public string $name = '';

    public string $ns = '';

    public function bootCallback(): void
    {
        /*
        $blade_component_path = '\Themes\LaravelIo\View\Components';
        foreach ($this->blade_components as $name => $class) {
            Blade::component($name, $blade_component_path.'\\'.$class);
        }
        */
        $this->registerBladeDirective();
        $this->registerBladeComponents();
        $this->registerLivewireComponents();
    }

    /**
     * Undocumented function.
     */
    public function registerBladeDirective(): void
    {
        Blade::directive(
            'md',
            static fn ($expression): string => '<'.sprintf('?php echo md_to_html(%s); ?', $expression).'>'
        );

        Blade::directive(
            'formGroup',
            static fn ($expression): string => '<div class="form-group<'.sprintf('?php echo $errors->has(%s) ? \' has-error\' : \'\' ?', $expression).'>">'
        );

        Blade::directive(
            'endFormGroup',
            static fn ($expression): string => '</div>'
        );

        Blade::directive(
            'title',
            static fn ($expression): string => '<'.sprintf('?php $title = %s ?', $expression).'>'
        );

        Blade::directive(
            'shareImage',
            static fn ($expression): string => '<'.sprintf('?php $shareImage = %s ?', $expression).'>'
        );

        Blade::directive(
            'canonical',
            static fn ($expression): string => '<'.sprintf('?php $canonical = %s ?', $expression).'>'
        );
    }

    public function registerBladeComponents(): void
    {
        BladeService::registerComponents($this->dir.'/../View/Components', 'Themes\\'.$this->name);
    }

    public function registerLivewireComponents(): void
    {
        LivewireService::registerComponents($this->dir.'/../Http/Livewire', 'Themes\\'.$this->name);
    }
}
