<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Modules\Xot\Models\CacheLock.
 *
 * @property string $key
 * @property string $owner
 * @property int    $expiration
 *
 * @method static \Modules\Xot\Database\Factories\CacheLockFactory factory($count = null, $state = [])
 * @method static Builder|CacheLock                                newModelQuery()
 * @method static Builder|CacheLock                                newQuery()
 * @method static Builder|CacheLock                                query()
 * @method static Builder|CacheLock                                whereExpiration($value)
 * @method static Builder|CacheLock                                whereKey($value)
 * @method static Builder|CacheLock                                whereOwner($value)
 *
 * @property int $expiration
 *
 * @method static \Modules\Xot\Database\Factories\CacheLockFactory factory($count = null, $state = [])
 * @method static Builder|CacheLock                                newModelQuery()
 * @method static Builder|CacheLock                                newQuery()
 * @method static Builder|CacheLock                                query()
 * @method static Builder|CacheLock                                whereExpiration($value)
 * @method static Builder|CacheLock                                whereKey($value)
 * @method static Builder|CacheLock                                whereOwner($value)
 *
 * @property \Modules\Fixcity\Models\Profile|null        $creator
 * @property \Modules\Fixcity\Models\Profile|null        $updater
 * @property \Modules\Xot\Contracts\ProfileContract|null $creator
 * @property \Modules\Xot\Contracts\ProfileContract|null $updater
 *
 * @mixin \Eloquent
 */
class CacheLock extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'key',
        'owner',
        'expiration',
    ];
}
