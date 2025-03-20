<?php

declare(strict_types=1);

namespace Modules\Xot\Providers;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Infolists\Components\Entry;
use Filament\Support\Components\Component;
use Filament\Support\Concerns\Configurable;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Modules\Xot\Exceptions\Handlers\HandlerDecorator;
use Modules\Xot\Exceptions\Handlers\HandlersRepository;
use Modules\Xot\Exceptions\Formatters\WebhookErrorFormatter;
use Modules\Xot\View\Composers\XotComposer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

use function Safe\realpath;

/**
 * Class XotServiceProvider.
 */
class XotServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Xot';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();
        $this->redirectSSL();
        $this->registerViewComposers();
        $this->registerEvents();
        $this->registerExceptionHandler();
        $this->registerTimezone();
        $this->registerProviders();
    }

    public function register(): void
    {
        parent::register();
        $this->registerConfig();
        //$this->registerExceptionHandlersRepository();
        //$this->extendExceptionHandler();
        $this->registerCommands();
    }

    public function registerProviders(): void
    {
        // $this->app->register(Filament\ModulesServiceProvider::class);
    }

    public function registerTimezone(): void
    {
        Assert::string($timezone = config('app.timezone') ?? 'Europe/Berlin', '['.__LINE__.']['.class_basename($this).']');
        Assert::string($date_format = config('app.date_format') ?? 'd/m/Y', '['.__LINE__.']['.class_basename($this).']');
        Assert::string($locale = config('app.locale') ?? 'it', '['.__LINE__.']['.class_basename($this).']');

        app()->setLocale($locale);
        Carbon::setLocale($locale);
        date_default_timezone_set($timezone);

        DateTimePicker::configureUsing(fn (DateTimePicker $component) => $component->timezone($timezone));
        DatePicker::configureUsing(fn (DatePicker $component) => $component->timezone($timezone)->displayFormat($date_format));
        TimePicker::configureUsing(fn (TimePicker $component) => $component->timezone($timezone));
        TextColumn::configureUsing(fn (TextColumn $column) => $column->timezone($timezone));
    }

    /**
     * @see https://github.com/cerbero90/exception-handler
     */
    public function registerExceptionHandler(): void
    {
        $exceptionHandler = $this->app->make(ExceptionHandler::class);
        if ($exceptionHandler instanceof HandlerDecorator) {
            $exceptionHandler->reporter(
                static function (\Throwable $e): void {
                    $data = (new WebhookErrorFormatter($e))->format();
                    if ($e instanceof AuthenticationException || $e instanceof NotFoundHttpException) {
                        return;
                    }

                    if (is_string(config('logging.channels.slack_errors.url'))
                        && mb_strlen(config('logging.channels.slack_errors.url')) > 5) {
                        Log::channel('slack_errors')
                            ->error($e->getMessage(), $data);
                    }
                }
            );
        }
    }

    public function registerConfig(): void
    {
        // $config_file = realpath(__DIR__.'/../config/metatag.php');
        // $this->mergeConfigFrom($config_file, 'metatag');
    }

    public function loadHelpersFrom(string $path): void
    {
        $files = File::files($path);
        foreach ($files as $file) {
            if ('php' !== $file->getExtension()) {
                continue;
            }

            $realPath = $file->getRealPath();
            if (false === $realPath) {
                continue;
            }

            include_once $realPath;
        }
    }

    protected function translatableComponents(): void
    {
        $components = [Field::class, BaseFilter::class, Placeholder::class, Column::class, Entry::class];
        foreach ($components as $component) {
            /* @var Configurable $component */
            $component::configureUsing(function (Component $translatable): void {
                /* @phpstan-ignore method.notFound */
                $translatable->translateLabel();
            });
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
        if (
            config('xra.forcessl') && (isset($_SERVER['SERVER_NAME']) && 'localhost' !== $_SERVER['SERVER_NAME']
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
