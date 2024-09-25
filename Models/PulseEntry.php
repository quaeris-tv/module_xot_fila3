<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

class PulseEntry extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'type',
        'key',
        'value',
    ];
}
