<?php

declare(strict_types=1);

namespace Modules\Xot\Models\Traits;

use Illuminate\Support\Str;

/**
 * Undocumented trait
 * https://www.larashout.com/using-uuids-in-laravel-models.
 */
trait HasUuid
{
    /**
     * Get the value indicating whether the IDs are incrementing.
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     */
    public function getKeyType(): string
    {
        return 'string';
    }

    /**
     * Boot function from Laravel.
     */
    protected static function bootHasUuid(): void
    {
        // parent::boot();
        static::creating(
            static function ($model): void {
                if (empty($model->{$model->getKeyName()})) {
                    $model->{$model->getKeyName()} = Str::uuid()->toString();
                }
            }
        );
    }
}
