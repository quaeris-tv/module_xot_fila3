<?php

declare(strict_types=1);

namespace Modules\Xot\Providers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Blade;
<<<<<<< HEAD
use Illuminate\Support\Facades\Config;
=======
>>>>>>> 35d9347 (.)
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Modules\Xot\Services\BladeService;
use Modules\Xot\Services\FileService;
use Modules\Xot\Services\LivewireService;

use function Safe\glob;
use function Safe\json_decode;
use function Safe\json_encode;
use function Safe\realpath;

/**
 * Class XotBaseServiceProvider.
 */
abstract class XotBaseServiceProvider extends ServiceProvider
{
    public string $module_name = 'xot';

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
        if (method_exists($this, 'bootCallback')) {
            $this->bootCallback();
        }

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
        $this->module_ns = collect(explode('\\', $this->module_ns))->slice(0, -1)->implode('\\');
        $this->app->register(''.$this->module_ns.'\Providers\RouteServiceProvider');

        $this->app->register(''.$this->module_ns.'\Providers\EventServiceProvider');
        // $this->app->register(EventServiceProvider::class);
        // $this->app->register(RouteServiceProvider::class);
        if (method_exists($this, 'registerCallback')) {
            $this->registerCallback();
        }

<<<<<<< HEAD
        $this->registerBladeIcons();

        // echo '<h3>Time :'.class_basename($this).' '.(microtime(true) - LARAVEL_START).'</h3>';
    }

    public function registerBladeIcons(): void
    {
        $svg_path = Str::of($this->module_ns.'/Resources/svg')->replace('\\', '/')->toString();
        $svg_abs_path = $this->module_dir.'/../../../'.$svg_path;

        if (! File::exists($svg_abs_path)) {
            File::makeDirectory($svg_abs_path, 0755, true, true);
            File::put($svg_abs_path.'/.gitkeep', '');
        }
        Config::set('blade-icons.sets.'.$this->module_name.'.path', $svg_path);
        Config::set('blade-icons.sets.'.$this->module_name.'.prefix', $this->module_name);
    }

=======
        // echo '<h3>Time :'.class_basename($this).' '.(microtime(true) - LARAVEL_START).'</h3>';
    }

>>>>>>> 35d9347 (.)
    /**
     * Register views.
     */
    public function registerViews(): void
    {
        try {
            $sourcePath = realpath($this->module_dir.'/../Resources/views');
        } catch (\Exception $e) {
            throw new \Exception('realpath not find dir ['.$this->module_dir.'/../Resources/views]');
        }
        /*
        $viewPath = resource_path('views/modules/'.$this->module_name);


        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/'.$this->module_name;
        }, \Config::get('view.paths')), [$sourcePath]), $this->module_name);
        */
        $this->loadViewsFrom($sourcePath, $this->module_name);
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        try {
            $langPath = realpath($this->module_dir.'/../Resources/lang');
        } catch (\Exception $e) {
            throw new \Exception('realpath not find dir['.$this->module_dir.'/../Resources/lang]');
        }

        $this->loadTranslationsFrom($langPath, $this->module_name);
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

    public function registerBladeComponents(): void
    {
        /*
        $module = Module::find($this->module_name);
        if (null == $module) {
            throw new \Exception('['.$this->module_name.'] is not found');
        }

        $namespace = 'Modules\\'.$module->getName().'\View\Components';

        Blade::componentNamespace($namespace, $module->getLowerName());
        */
        $namespace = $this->module_ns.'\View\Components';
        Blade::componentNamespace($namespace, $this->module_name);
        /*
        dddx([
            'module_ns'=>$this->module_ns,
            'module_dir'=>$this->module_dir,
            'this'=>$this,
        ])
        ;
        */
        BladeService::registerComponents($this->module_dir.'/../View/Components', $this->module_ns);
    }

    /**
     * Undocumented function.
     */
    public function registerLivewireComponents(): void
    {
        // $prefix=$this->module_name.'::';
        $prefix = '';
        LivewireService::registerComponents(
            $this->module_dir.'/../Http/Livewire',
            Str::before($this->module_ns, '\Providers'),
            $prefix,
        );
    }

    public function registerCommands(): void
    {
        $prefix = '';
        $comps = FileService::getComponents(
            $this->module_dir.'/../Console/Commands',
            Str::before($this->module_ns, '\Providers'),
            $prefix,
        );
        if (\count($comps) > 0) {
            $commands = collect($comps)->map(
                function ($item) {
                    return $this->module_ns.'\Console\Commands\\'.$item->class_name;
                }
            )->toArray();

            $this->commands($commands);
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * Undocumented function.
     *
     * @throws FileNotFoundException
     */
    public function getEventsFrom(string $path): array
    {
        $events = [];
        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $events_file = $path.'/_events.json';
        $force_recreate = request()->input('force_recreate', true);
        if (! File::exists($events_file) || $force_recreate) {
            $filenames = glob($path.'/*.php');
            // if (false === $filenames) {
            //    $filenames = [];
            // }
            foreach ($filenames as $filename) {
                $info = pathinfo((string) $filename);

                // $tmp->namespace='\\'.$vendor.'\\'.$pack.'\\Events\\'.$info['filename'];
                $event_name = $info['filename'];
                $str = 'Event';
                if (Str::endsWith($event_name, $str)) {
                    $listener_name = substr($event_name, 0, -\strlen($str)).'Listener';

                    $event = $this->module_base_ns.'\\Events\\'.$event_name;
                    $listener = $this->module_base_ns.'\\Listeners\\'.$listener_name;
                    $msg = [
                        'event' => $event,
                        'event_exists' => class_exists($event),
                        'listener' => $listener,
                        'listener_exists' => class_exists($listener),
                    ];
                    if (class_exists($event) && class_exists($listener)) {
                        // \Event::listen($event, $listener);
<<<<<<< HEAD
                        $tmp = new \stdClass;
=======
                        $tmp = new \stdClass();
>>>>>>> 35d9347 (.)
                        $tmp->event = $event;
                        $tmp->listener = $listener;
                        $events[] = $tmp;
                    }
                }
            }

            try {
                $events_content = json_encode($events, JSON_THROW_ON_ERROR);
                // if (false === $events_content) {
                //    throw new \Exception('can not encode json');
                // }
                File::put($events_file, $events_content);
            } catch (\Exception $e) {
                dd($e);
            }
        } else {
            $events = File::get($events_file);
            // $events = (array) json_decode((string) $events, null, 512, JSON_THROW_ON_ERROR);
            $events = (array) json_decode((string) $events, false, 512, JSON_THROW_ON_ERROR);
        }

        return $events;
    }

    /**
     * @throws FileNotFoundException
     */
    public function loadEventsFrom(string $path): void
    {
        $events = $this->getEventsFrom($path);
        foreach ($events as $event) {
            Event::listen($event->event, $event->listener);
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        /*
        $this->publishes(
            [
                $this->module_dir.'/../Config/config.php' => config_path($this->module_name.'.php'),
            ],
            'config'
        );
        */
        $this->mergeConfigFrom(
            $this->module_dir.'/../Config/config.php',
            $this->module_name
        );
    }

    // end function
}
