<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

/**
 * @property \Modules\Xot\Contracts\ProfileContract|null $creator
 * @property \Modules\Xot\Contracts\ProfileContract|null $updater
 *
 * @method static \Modules\Xot\Database\Factories\PulseAggregateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate query()
 *
 * @property int $id
 * @property int $bucket
 * @property int $period
 * @property string $type
 * @property string $key
 * @property string|null $key_hash
 * @property string $aggregate
 * @property string $value
 * @property int|null $count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate whereAggregate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate whereBucket($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate whereKeyHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PulseAggregate whereValue($value)
 *
 * @mixin \Eloquent
 */
class PulseAggregate extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'type',
        'key',
        'value',
    ];
}
