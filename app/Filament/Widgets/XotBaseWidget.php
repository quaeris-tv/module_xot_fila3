<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Widgets;

<<<<<<< HEAD
use Filament\Widgets\Widget as FilamentWidget;
=======
>>>>>>> d5e9f6d7 (.)
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\WidgetConfiguration;
<<<<<<< HEAD
=======
use Filament\Widgets\Widget as FilamentWidget;
>>>>>>> 34f9e999 (fix(XotBaseWidget): add missing use statements and correct constructor implementation to properly set the view)
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Modules\Xot\Actions\View\GetViewByClassAction;

/**
 * @property bool $shouldRender
 */
abstract class XotBaseWidget extends FilamentWidget
{
    use InteractsWithPageFilters;
<<<<<<< HEAD
  
=======
    public string $title = '';
    public string $icon = '';
    protected static string $view = 'ui::empty';
>>>>>>> 34f9e999 (fix(XotBaseWidget): add missing use statements and correct constructor implementation to properly set the view)


    public function __construct()
    {
<<<<<<< HEAD
        $view = app(GetViewByClassAction::class)->execute(static::class);
        static::$view = $view;
=======
        //parent::__construct();//Cannot call constructor
<<<<<<< HEAD
<<<<<<< HEAD
        $view=app(GetViewByClassAction::class)->execute(static::class);
        static::$view=$view;
=======
        $view = app(GetViewByClassAction::class)->execute(static::class);
        static::$view = $view;
<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> 755e82bc (.)
=======
=======
    /**
     * The cache TTL in seconds.
     */
    protected ?int $cacheTtl = null;

    /**
     * The polling interval in seconds.
     */
    protected ?int $pollingInterval = null;

    /**
     * @var ?\Closure
     */
    protected $authorizeUsing;

    /**
     * Set the authorization callback.
     */
    public function authorizeUsing(?\Closure $callback): static
    {
        $this->authorizeUsing = $callback;

        return $this;
    }

    /**
     * Enable caching for the widget.
     */
    public function cache(string $key, ?int $ttl = 300): static
    {
        $this->cacheKey = $key;
        $this->cacheTtl = $ttl;

        return $this;
    }

    /**
     * Enable polling for the widget.
     */
    public function poll(?int $interval = 10): static
    {
        $this->pollingInterval = $interval;

        return $this;
    }

    /**
     * Get the cached data.
     *
     * @template T
     *
     * @param \Closure(): T $callback
     *
     * @return T
     */
    protected function getCachedData(\Closure $callback): mixed
    {
        if (! $this->cacheKey) {
            return $callback();
        }

        return Cache::remember(
            $this->getCacheKey(),
            $this->cacheTtl,
            $callback
        );
    }

    /**
     * Get the full cache key.
     */
    protected function getCacheKey(): string
    {
        return 'widget.'.$this->cacheKey.'.'.class_basename($this);
    }

    /**
     * Check if the widget is authorized.
     */
    public function isAuthorized(): bool
    {
        if (! $this->authorizeUsing) {
            return true;
        }

        return (bool) call_user_func($this->authorizeUsing);
    }

    /**
     * Get the polling interval.
     */
    public function getPollingInterval(): ?int
    {
        return $this->pollingInterval;
    }

    /**
     * Check if the widget should poll.
     */
    public function shouldPoll(): bool
    {
        return null !== $this->pollingInterval;
    }

    /**
     * Mount the widget.
     */
    public function mount(): void
    {
        if (! $this->isAuthorized()) {
            $this->shouldRender = false;
        }
    }

    /**
     * Get the view data.
     *
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'pollingInterval' => $this->getPollingInterval(),
            'shouldPoll' => $this->shouldPoll(),
        ];
>>>>>>> 5ecd18f4 (up)
>>>>>>> dd421439 (fix(XotBaseWidget): refactor widget to support caching, polling and authorization)
=======
>>>>>>> b31705d4 (refactor(XotBaseWidget): update XotBaseWidget to extend Filament\Widgets\Widget as FilamentWidget)
=======
        $view = app(GetViewByClassAction::class)->execute(static::class);
        static::$view = $view;
=======
        $view=app(GetViewByClassAction::class)->execute(static::class);
        static::$view=$view;
>>>>>>> d5e9f6d7 (.)
>>>>>>> 9fb3d5cf (fix(XotBaseWidget): add missing use statements and correct constructor implementation to properly set the view)
>>>>>>> 34f9e999 (fix(XotBaseWidget): add missing use statements and correct constructor implementation to properly set the view)
    }
}
