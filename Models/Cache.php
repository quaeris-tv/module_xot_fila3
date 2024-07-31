<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Modules\Xot\Models\Cache.
 *
 * @property string $key
 * @property string $value
 * @property int    $expiration
 * @method static \Modules\Xot\Database\Factories\CacheFactory factory($count = null, $state = [])
 * @method static Builder|Cache                                newModelQuery()
 * @method static Builder|Cache                                newQuery()
 * @method static Builder|Cache                                query()
 * @method static Builder|Cache                                whereExpiration($value)
 * @method static Builder|Cache                                whereKey($value)
 * @method static Builder|Cache                                whereValue($value)
 * @property int $expiration
 * @method static \Modules\Xot\Database\Factories\CacheFactory factory($count = null, $state = [])
 * @method static Builder|Cache                                newModelQuery()
 * @method static Builder|Cache                                newQuery()
 * @method static Builder|Cache                                query()
 * @method static Builder|Cache                                whereExpiration($value)
 * @method static Builder|Cache                                whereKey($value)
 * @method static Builder|Cache                                whereValue($value)
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
