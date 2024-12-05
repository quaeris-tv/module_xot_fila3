<?php

declare(strict_types=1);

namespace Modules\Xot\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;

/**
 * Trait Updater.
 * https://dev.to/hasanmn/automatically-update-createdby-and-updatedby-in-laravel-using-bootable-traits-28g9.
 */
trait Updater
{
    /**
     * Summary of creator.
     *
     * @return BelongsTo<Model&ProfileContract, $this>
     */
    public function creator(): BelongsTo
    {
        $profile_class = XotData::make()->getProfileClass();

        /*
        return $this->belongsTo(
            \Modules\Xot\Datas\XotData::make()->getUserClass(),
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
     *
     * @return BelongsTo<Model&ProfileContract, $this>
     */
    public function updater(): BelongsTo
    {
        $profile_class = XotData::make()->getProfileClass();

        /*
        return $this->belongsTo(
            \Modules\Xot\Datas\XotData::make()->getUserClass(),
            'updated_by',
        );
        */
        return $this->belongsTo(
            $profile_class,
            'updated_by',
            'user_id'
        );
    }

    /**
     * bootUpdater function.
     */
    protected static function bootUpdater(): void
    {
        static::creating(
            static function (Model $model): void {
                // @phpstan-ignore property.notFound
                $model->created_by = authId();
                // @phpstan-ignore property.notFound
                $model->updated_by = authId();
            }
        );

        static::updating(
            static function (Model $model): void {
                // @phpstan-ignore property.notFound
                $model->updated_by = authId();
            }
        );
        /*
         * Deleting a model is slightly different than creating or deleting.
         * For deletes we need to save the model first with the deleted_by field
         */
        static::deleting(
            static function (Model $model): void {
                Assert::isArray($attributes = $model->attributes);

                if (\in_array('deleted_by', array_keys($attributes), false)) {
                    $model->update(['deleted_by' => authId()]);
                }
            }
        );
    }
}// end trait Updater
