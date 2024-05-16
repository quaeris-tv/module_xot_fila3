<?php

declare(strict_types=1);

namespace Modules\Xot\Models\Traits;

// use Laravel\Scout\Searchable;
// ----- models------
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Xot\Models\Widget;

// ---- services -----
// use Modules\Cms\Services\PanelService;

// ------ traits ---

/**
 * Trait WidgetTrait.
 */
trait WidgetTrait
{
    /**
     * @return MorphMany<Widget>
     *
     * -- psalm-return Builder<Widget>
     */
    public function widgets(): MorphMany
    {
        // questo sarebbe itemWidgets, ma teniamo questo nome
        return $this->morphMany(Widget::class, 'post')
            // ->whereNull('layout_position')
            ->where(
                static function ($query): void {
                    $query->where('layout_position', '')
                        ->orWhereNull('layout_position');
                }
            )
            ->orderBy('pos');
    }

    public function containerWidgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'post_type', 'post_type')
            ->orderBy('pos');
        // ->whereNull('post_id');
    }

    // non sembra funzionare, perchè?

    public function scopeOfLayoutPosition(Builder $query, string $layout_position): Builder
    {
        return $query->where('layout_position', $layout_position);
    }
}
