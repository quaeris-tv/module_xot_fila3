<?php

declare(strict_types=1);

namespace Modules\Xot\Traits;

/**
 * Trait Updater.
 * https://dev.to/hasanmn/automatically-update-createdby-and-updatedby-in-laravel-using-bootable-traits-28g9.
 */
trait Updater
{
    /**
     * bootUpdater function.
     */
    protected static function bootUpdater(): void
    {
        static::creating(
            static function ($model): void {
                $model->created_by = authId();
                $model->updated_by = authId();
            }
        );

        static::updating(
            static function ($model): void {
                $model->updated_by = authId();
            }
        );
        /*
         * Deleting a model is slightly different than creating or deleting.
         * For deletes we need to save the model first with the deleted_by field
         */
        static::deleting(
            static function ($model): void {
                if (\in_array('deleted_by', array_keys($model->attributes), false)) {
                    $model->deleted_by = authId();
                    $model->save();
                }
            }
        );
    }
}// end trait Updater
