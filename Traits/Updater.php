<?php

declare(strict_types=1);

namespace Modules\Xot\Traits;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Datas\XotData;

=======
>>>>>>> 35d9347 (.)
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
<<<<<<< HEAD

    public function creator(): BelongsTo
    {
        $profile_class = XotData::make()->getProfileClass();

        /*
        return $this->belongsTo(
            User::class,
            'created_by',
        );
        */
        return $this->belongsTo(
            $profile_class,
            'updated_by',
            'user_id'
        );
    }

    /**
     * Defines a relation to obtain the last user who
     * manipulated the Entity instance.
     */
    public function updater(): BelongsTo
    {
        $profile_class = XotData::make()->getProfileClass();

        /*
        return $this->belongsTo(
            User::class,
            'updated_by',
        );
        */
        return $this->belongsTo(
            $profile_class,
            'updated_by',
            'user_id'
        );
    }
=======
>>>>>>> 35d9347 (.)
}// end trait Updater
