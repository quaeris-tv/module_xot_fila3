<?php

declare(strict_types=1);

namespace Modules\Xot\Datas;

use Spatie\LaravelData\Data;

class ColumnData extends Data
{
    /**
     * @param string $name The name of the column
     * @param string|null $type The SQL type of the column (optional)
     */
    public function __construct(
        public string $name,
        public ?string $type = null
    ) {
    }
}
