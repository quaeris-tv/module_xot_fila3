<?php

declare(strict_types=1);

namespace Modules\Xot\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Modules\Xot\Actions\Blade\RegisterBladeComponentsAction;
use Modules\Xot\Actions\Livewire\RegisterLivewireComponentsAction;
use Modules\Xot\Datas\ComponentFileData;
use Nwidart\Modules\Traits\PathNamespace;

use function Safe\realpath;

use Webmozart\Assert\Assert;

/**
 * Class XotBaseServiceProvider.
 */
abstract class XotBaseServiceProvider extends ServiceProvider
{
    use PathNamespace;

    public string $name = '';

    public string $nameLower = '';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    protected string $module_base_ns;

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerTranslations();

        $this->registerConfig();
        $this->registerViews();
        // $this->registerFactories();
        $this->loadMigrationsFrom($this->module_dir.'/../Database/Migrations');

        // Illuminate\Contracts\Container\BindingResolutionException: Target class [livewire] does not exist.
        $this->registerLivewireComponents();
        // Illuminate\Contracts\Container\BindingResolutionException: Target class [modules] does not exist.
        $this->registerBladeComponents();
        $this->registerCommands();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->nameLower = Str::lower($this->name);
        $this->module_ns = collect(explode('\\', $this->module_ns))->slice(0, -1)->implode('\\');
        $this->app->register(''.$this->module_ns.'\Providers\RouteServiceProvider');
        $this->app->register(''.$this->module_ns.'\Providers\EventServiceProvider');
        $this->registerBladeIcons();
    }

    public function registerBladeIcons(): void
    {
        if ('' === $this->name) {
            
            //throw new \Exception('name is empty on ['.static::class.']');
            $name=class_basename(static::class);
            $name=Str::of($name)->beforeLast('ServiceProvider')->__toString();
            $this->name=$name;
        }

        Assert::string($relativePath = config('modules.paths.generator.assets.path'));

        try {
            $svgPath = module_path($this->name, $relativePath.'/../svg');
            if (! is_string($svgPath)) {
                throw new \Exception('Invalid SVG path');
            }
            // $resolvedPath = realpath($svgPath);
            $resolvedPath = $svgPath;
            $svgPath = $resolvedPath;
        } catch (\Error $e) {
            $svgPath = base_path('Modules/'.$this->name.'/'.$relativePath.'/../svg');
            if (! is_string($svgPath)) {
                throw new \Exception('Invalid fallback SVG path');
            }
        }

        $basePath = base_path(DIRECTORY_SEPARATOR);
        $svgPath = str_replace($basePath, '', $svgPath);

        Config::set('blade-icons.sets.'.$this->nameLower.'.path', $svgPath);
        Config::set('blade-icons.sets.'.$this->nameLower.'.prefix', $this->nameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        if ('' === $this->name) {
            throw new \Exception('name is empty on ['.static::class.']');
        }

        $viewPath = module_path($this->name, 'resources/views');
        if (! is_string($viewPath)) {
            throw new \Exception('Invalid view path');
        }

        $this->loadViewsFrom($viewPath, $this->nameLower);
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        if ('' === $this->name) {
            throw new \Exception('name is empty on ['.static::class.']');
        }

        try {
            $langPath = module_path($this->name, 'lang');
            if (! is_string($langPath)) {
                throw new \Exception('Invalid language path');
            }
            $this->loadTranslationsFrom($langPath, $this->nameLower);
        } catch (\Error $e) {
            $fallbackPath = base_path('Modules/'.$this->name.'/lang');
            $this->loadTranslationsFrom($fallbackPath, $this->nameLower);
        }

        $jsonLangPath = module_path($this->name, 'lang');
        if (! is_string($jsonLangPath)) {
            throw new \Exception('Invalid JSON language path');
        }
        $this->loadJsonTranslationsFrom($jsonLangPath);
    }

    /**
     * Register an additional directory of factories.
     */
    public function registerFactories(): void
    {
        if (! app()->environment('production')) {
            // app(Factory::class)->load($this->module_dir.'/../Database/factories');
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        try {
            Assert::string($relativePath = config('modules.paths.generator.config.path'));
            $configPath = module_path($this->name, $relativePath);
            if (! is_string($configPath)) {
                return;
            }

            if (! file_exists($configPath)) {
                return;
            }

            $this->publishes([
                $configPath => config_path($this->nameLower.'.php'),
            ], 'config');

            $this->mergeConfigFrom($configPath, $this->nameLower);
        } catch (\Exception $e) {
            // Ignore missing configuration
            return;
        }
    }

    public function registerBladeComponents(): void
    {
        Assert::string($relativePath = config('modules.paths.generator.component-class.path'));
        $componentClassPath = module_path($this->name, $relativePath);
        $namespace = $this->module_ns.'\View\Components';
        Blade::componentNamespace($namespace, $this->nameLower);

        app(RegisterBladeComponentsAction::class)
            ->execute(
                // $this->module_dir.'/../View/Components',
                $componentClassPath,
                $this->module_ns
            );
    }

    /**
     * Register Livewire components.
     */
    public function registerLivewireComponents(): void
    {
        $prefix = '';
        app(RegisterLivewireComponentsAction::class)
            ->execute(
                $this->module_dir.'/../Http/Livewire',
                Str::before($this->module_ns, '\Providers'),
                $prefix
            );
    }

    public function registerCommands(): void
    {
        $prefix = '';

        $comps = app(\Modules\Xot\Actions\File\GetComponentsAction::class)
            ->execute(
                $this->module_dir.'/../Console/Commands',
                'Modules\\'.$this->name.'\\Console\\Commands',
                $prefix,
            );
        if (0 == $comps->count()) {
            return;
        }
        $commands = Arr::map(
            $comps->items(),
            function (ComponentFileData $item) {
                return $item->ns;
            }
        );
        $this->commands($commands);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
