<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Modules\Xot\Models\Cache.
 *
 * @property string $key
 * @property string $value
<<<<<<< HEAD
 * @property int $expiration
 *
 * @method static \Modules\Xot\Database\Factories\CacheFactory factory($count = null, $state = [])
 * @method static Builder|Cache newModelQuery()
 * @method static Builder|Cache newQuery()
 * @method static Builder|Cache query()
 * @method static Builder|Cache whereExpiration($value)
 * @method static Builder|Cache whereKey($value)
 * @method static Builder|Cache whereValue($value)
 *
 * @property int $expiration
 *
 * @method static \Modules\Xot\Database\Factories\CacheFactory factory($count = null, $state = [])
 * @method static Builder|Cache newModelQuery()
 * @method static Builder|Cache newQuery()
 * @method static Builder|Cache query()
 * @method static Builder|Cache whereExpiration($value)
 * @method static Builder|Cache whereKey($value)
 * @method static Builder|Cache whereValue($value)
 *
 * @property-read \Modules\Fixcity\Models\Profile|null $creator
 * @property-read \Modules\Fixcity\Models\Profile|null $updater
=======
 * @property int    $expiration
 *
 * @method static \Modules\Xot\Database\Factories\CacheFactory factory($count = null, $state = [])
 * @method static Builder|Cache                                newModelQuery()
 * @method static Builder|Cache                                newQuery()
 * @method static Builder|Cache                                query()
 * @method static Builder|Cache                                whereExpiration($value)
 * @method static Builder|Cache                                whereKey($value)
 * @method static Builder|Cache                                whereValue($value)
 *
 * @property int $expiration
 *
 * @method static \Modules\Xot\Database\Factories\CacheFactory factory($count = null, $state = [])
 * @method static Builder|Cache                                newModelQuery()
 * @method static Builder|Cache                                newQuery()
 * @method static Builder|Cache                                query()
 * @method static Builder|Cache                                whereExpiration($value)
 * @method static Builder|Cache                                whereKey($value)
 * @method static Builder|Cache                                whereValue($value)
>>>>>>> d7d5c20 (Check & fix styling)
 *
 * @mixin \Eloquent
 */
class Cache extends BaseModel
{
    protected $table = 'cache';

    /** @var string */
    protected $primaryKey = 'key';

    /** @var array<int, string> */
    protected $fillable = [
        'key',
        'value',
        'expiration',
    ];
}
