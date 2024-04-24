<?php

declare(strict_types=1);

namespace Modules\Xot\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Modules\User\Models\Device;
use Modules\User\Models\DeviceUser;
use Modules\User\Models\User;
use Modules\Xot\Datas\XotData;
use Spatie\MediaLibrary\InteractsWithMedia;

trait IsProfileTrait
{
    use InteractsWithMedia;
    // --- RELATIONS

    /**
     * Undocumented function.
     */
    public function user(): BelongsTo
    {
        // $user = TenantService::model('user'); //no bisgna guardare dentro config(auth  etc etc
        // $user_class = \get_class($user);
        $userClass = XotData::make()->getUserClass();

        return $this->belongsTo($userClass);
    }

    // ---- mutators
    public function getFullNameAttribute(?string $value): ?string
    {
        if (null !== $value) {
            return $value;
        }

        $res = $this->first_name.' '.$this->last_name;
        if (\strlen($res) > 2) {
            return $res;
        }

        return $this->user?->name;
    }

    public function getFirstNameAttribute(?string $value): ?string
    {
        if (null !== $value) {
            return $value;
        }
        $value = $this->user?->first_name;
        $this->update(['first_name' => $value]);

        return $value;
    }

    public function getLastNameAttribute(?string $value): ?string
    {
        if (null !== $value) {
            return $value;
        }
        $value = $this->user?->last_name;
        $this->update(['last_name' => $value]);

        return $value;
    }

    /**
     * Get the user's user_name.
     */
    protected function userName(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                return $this->user?->name;
            }
        );
    }

    /**
     * Get the user's avatar.
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $value = $this->getFirstMediaUrl('avatar');

                return $value;
            }
        );
    }

    public function isSuperAdmin(): bool
    {
        if (null === $this->user) {
            return false;
        }

        return $this->user->hasRole('super-admin');
    }

    public function mobileDevices(): BelongsToMany
    {
        return $this->devices();
    }

    public function devices(): BelongsToMany
    {
        $pivotClass = DeviceUser::class;
        $pivot = app($pivotClass);
        $pivotTable = $pivot->getTable();
        $pivotFields = $pivot->getFillable();

        return $this
            ->belongsToMany(
                related: Device::class,
                table: $pivotTable,
                foreignPivotKey: 'user_id',
                relatedPivotKey: null,
                parentKey: 'user_id',
                relatedKey: null,
                relation: null,
            )
            ->using($pivotClass)
            ->withPivot($pivotFields)
            ->withTimestamps();
    }

    public function mobileDeviceUsers(): HasMany
    {
        return $this->deviceUsers();
    }

    public function deviceUsers(): HasMany
    {
        return $this->hasMany(
            related: DeviceUser::class,
            foreignKey: 'user_id',
            localKey: 'user_id',
        );
    }

    /**
     * @return Collection<(int|string), mixed>
     */
    public function getMobileDeviceTokens(): Collection
    {
        return $this
            ->mobileDeviceUsers()
            ->whereNotNull('push_notifications_token')
            ->where('push_notifications_enabled', '=', true)
            ->get()
            ->pluck('push_notifications_token');
    }

    /*
     * Get all of the teams the user belongs to.

    public function teams(): BelongsToMany
    {
        $xot = XotData::make();
        $pivotClass = $xot->getMembershipClass();
        $pivot = app($pivotClass);
        $pivotTable = $pivot->getTable();
        $pivotDbName = $pivot->getConnection()->getDatabaseName();
        $pivotTableFull = $pivotDbName.'.'.$pivotTable;

        // $this->setConnection('mysql');
        return $this->belongsToMany($xot->getTeamClass(), $pivotTableFull, 'user_id', 'team_id','user_id')
            ->using($pivotClass)
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }
    */
}
