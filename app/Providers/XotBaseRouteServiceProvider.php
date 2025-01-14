<?php

declare(strict_types=1);

namespace Modules\Xot\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;

use function PHPUnit\Framework\throwException;

/**
 * Class XotBaseRouteServiceProvider.
 */
abstract class XotBaseRouteServiceProvider extends RouteServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\Xot\Http\Controllers';

    /**
     * The module directory.
     */
    protected string $module_dir = __DIR__;

    /**
     * The module namespace.
     */
    protected string $module_ns = __NAMESPACE__;

    public string $name = '';

    /**
     * Undocumented function.
     */
    public function boot(): void
    {
        Config::set('extra_conn', Request::segment(2)); // Se configurato va a prendere db diverso
        // if (method_exists($this, 'bootCallback')) {
        //    $this->bootCallback();
        // }

        parent::boot();
    }

    /**
     * Undocumented function.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Undocumented function.
     */
    protected function mapWebRoutes(): void
    {
        if($this->name==''){
            Notification::make()
            ->title('Error')
            ->danger()
            ->persistent()
            ->body('on [Name]ServiceProvider and RouteServiceProvider add $name variable')
            ->send();
            return;
        }
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            // ->group($this->module_dir.'/../Routes/web.php');
            ->group(module_path($this->name, '/routes/web.php'));
    }

    /**
     * Undocumented function.
     */
    protected function mapApiRoutes(): void
    {
<<<<<<< HEAD
        if($this->name==''){
            Notification::make()
            ->title('Error')
            ->danger()
            ->persistent()
            ->body('on [Name]ServiceProvider and RouteServiceProvider add $name variable')
            ->send();
            return;
=======
        if ($this->name === '') {
            // throw new \Exception('name is empty on'. static::class);
            throw new \Exception('name is empty on ['. static::class.']');
>>>>>>> origin/dev
        }
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            // ->group($this->module_dir.'/../Routes/api.php');
            ->group(module_path($this->name, '/routes/api.php'));
    }
}
