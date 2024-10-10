<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

/**
 * @property \Modules\Xot\Contracts\ProfileContract|null $creator
 * @property \Modules\Xot\Contracts\ProfileContract|null $updater
 *
 * @method static \Modules\Xot\Database\Factories\PulseEntryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PulseEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PulseEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PulseEntry query()
 *
 * @mixin \Eloquent
 */
class PulseEntry extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'type',
        'key',
        'value',
    ];
}
