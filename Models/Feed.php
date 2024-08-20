<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Modules\Xot\Models\Feed.
 *
 * @method static \Modules\Xot\Database\Factories\FeedFactory factory($count = null, $state = [])
 * @method static Builder|Feed newModelQuery()
 * @method static Builder|Feed newQuery()
 * @method static Builder|Feed query()
 * @method static Builder|Feed newModelQuery()
 * @method static Builder|Feed newQuery()
 * @method static Builder|Feed query()
 *
 * @property-read \Modules\Fixcity\Models\Profile|null $creator
 * @property-read \Modules\Fixcity\Models\Profile|null $updater
 *
 * @mixin \Eloquent
 */
class Feed extends BaseModel
{
    /** @var array<int, string> */
    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
    ];
}
