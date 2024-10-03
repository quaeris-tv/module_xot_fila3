<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

/**
 * @property \Modules\Camping\Models\Profile|null $creator
 * @property \Modules\Camping\Models\Profile|null $updater
 *
 * @method static \Modules\Xot\Database\Factories\PulseValueFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PulseValue  newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PulseValue  newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PulseValue  query()
 *
 * @mixin \Eloquent
 */
class PulseValue extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'type',
        'key',
        'value',
    ];
}
