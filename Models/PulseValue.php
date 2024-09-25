<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;


class PulseValue extends BaseModel
{

    /** @var list<string> */
    protected $fillable = [
        'type',
        'key',
        'value',
    ];
}
