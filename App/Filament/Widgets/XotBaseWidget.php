<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;

/**
 * @property bool $shouldRender
 */
abstract class XotBaseWidget extends Widget
{
    /**
     * The cache key for the widget.
     */
    protected ?string $cacheKey = null;

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
     * @param  \Closure(): T  $callback
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
        return $this->pollingInterval !== null;
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
    }
}
