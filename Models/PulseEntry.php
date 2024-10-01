<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

/**
 * 
 *
 * @property-read \Modules\Camping\Models\Profile|null $creator
 * @property-read \Modules\Camping\Models\Profile|null $updater
 * @method static \Modules\Xot\Database\Factories\PulseEntryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PulseEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PulseEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PulseEntry query()
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
