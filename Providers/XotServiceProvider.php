<?php

declare(strict_types=1);

namespace Modules\Xot\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Modules\Xot\Exceptions\Formatters\WebhookErrorFormatter;
use Modules\Xot\Exceptions\Handlers\HandlerDecorator;
use Modules\Xot\Exceptions\Handlers\HandlersRepository;
use Modules\Xot\Providers\Traits\TranslatorTrait;
use Modules\Xot\View\Composers\XotComposer;

use function Safe\realpath;

use Webmozart\Assert\Assert;

/**
 * Class XotServiceProvider.
 */
class XotServiceProvider extends XotBaseServiceProvider
{
    use TranslatorTrait;

    public string $module_name = 'xot';
    protected string $module_dir = __DIR__;
    protected string $module_ns = __NAMESPACE__;

    public function bootCallback(): void
    {
        $this->redirectSSL();
        $this->registerTranslator();
        $this->registerViewComposers(); // rompe filament
        $this->registerEvents();
        $this->registerExceptionHandler();
        $this->registerTimezone();
    }

    public function registerTimezone(): void
    {
        Assert::string($timezone = config('app.timezone') ?? 'Europe/Berlin');
        Assert::string($date_format = config('app.date_format') ?? 'd/m/Y');
        date_default_timezone_set($timezone);

        DateTimePicker::configureUsing(fn (DateTimePicker $component) => $component->timezone($timezone));
        DatePicker::configureUsing(fn (DatePicker $component) => $component->timezone($timezone)->displayFormat($date_format));
        TimePicker::configureUsing(fn (TimePicker $component) => $component->timezone($timezone));
        TextColumn::configureUsing(fn (TextColumn $column) => $column->timezone($timezone));
    }

    public function registerCallback(): void
    {
        $this->registerConfigs();
        $this->registerExceptionHandlersRepository();
        $this->extendExceptionHandler();
    }

    /**
     * @see https://github.com/cerbero90/exception-handler
     */
    public function registerExceptionHandler(): void
    {
        $exceptionHandler = $this->app->make(ExceptionHandler::class);
        $exceptionHandler->reporter(
            static function (\Throwable $e): void {
                $data = (new WebhookErrorFormatter($e))->format();
                if ($e instanceof AuthenticationException) {
                    return;
                }
                Log::channel('slack_errors')
                    ->error(
                        $e->getMessage(),
                        $data
                    );
            }
        );

        // $exceptionHandler->renderer(function ($e, $request) {
        //    dddx([$e, $request]);
        // });

        /*
        ->reporter(function ($e) {
            // $this->app['log']->debug($e->getMessage());
            dddx('a');
        });

        // register a custom renderer to redirect the user back and show validation errors
        $this->app->make(ExceptionHandler::class)->renderer(function ($e, $request) {
            // return back()->withInput()->withErrors($e->errors());
            dddx('b');
        });
        */
    }

    public function registerConfigs(): void
    {
        $config_file = realpath(__DIR__.'/../Config/metatag.php');
        $this->mergeConfigFrom($config_file, 'metatag');
    }

    public function loadHelpersFrom(string $path): void
    {
        $files = File::files($path);
        foreach ($files as $file) {
            if ('php' !== $file->getExtension()) {
                continue;
            }

            if (false === $file->getRealPath()) {
                continue;
            }

            include_once $file->getRealPath();
        }
    }

    /**
     * Register the custom exception handlers repository.
     */
    private function registerExceptionHandlersRepository(): void
    {
        $this->app->singleton(HandlersRepository::class, HandlersRepository::class);
    }

    /**
     * Extend the Laravel default exception handler.
     *
     * @see https://github.com/cerbero90/exception-handler/blob/master/src/Providers/ExceptionHandlerServiceProvider.php
     */
    private function extendExceptionHandler(): void
    {
        $this->app->extend(
            ExceptionHandler::class,
            static function (ExceptionHandler $handler, $app) {
                return new HandlerDecorator($handler, $app[HandlersRepository::class]);
            }
        );
    }

    private function redirectSSL(): void
    {
        // --- meglio ficcare un controllo anche sull'env
        if (config('xra.forcessl') && (isset($_SERVER['SERVER_NAME']) && 'localhost' !== $_SERVER['SERVER_NAME']
            && isset($_SERVER['REQUEST_SCHEME']) && 'http' === $_SERVER['REQUEST_SCHEME'])
        ) {
            URL::forceScheme('https');
            /*
             * da fare in htaccess
             */
            if (! request()->secure() /* && in_array(env('APP_ENV'), ['stage', 'production']) */) {
                exit(redirect()->secure(request()->getRequestUri()));
            }
        }
    }

    /**
     * Undocumented function.
     *
     * @see https://medium.com/@dobron/running-laravel-ide-helper-generator-automatically-b909e75849d0
     */
    private function registerEvents(): void
    {
        Event::listen(
            MigrationsEnded::class,
            static function (): void {
                // Artisan::call('ide-helper:models -r -W');
            }
        );
    }

    private function registerViewComposers(): void
    {
        View::composer('*', XotComposer::class);
    }
} // end class
