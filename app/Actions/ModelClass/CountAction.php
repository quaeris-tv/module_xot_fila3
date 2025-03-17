<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Webmozart\Assert\Assert;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;
use Modules\Xot\Models\InformationSchemaTable;

/**
 * Counts records for a given model class using optimized table information.
 */
class CountAction
{
    use QueueableAction;

    /**
     * Execute the count action for the given model class.
     *
     * @param class-string<Model> $modelClass The fully qualified model class name
     *
     * @throws \InvalidArgumentException If model class is invalid or not found
     *
     * @return int The total count of records
     */
    public function execute(string $modelClass): int
    {
        return InformationSchemaTable::getModelCount($modelClass);
    }
}
