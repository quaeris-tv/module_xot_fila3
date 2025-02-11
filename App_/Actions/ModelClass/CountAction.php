<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Counts records for a given model class using optimized table information.
 *
 * -implements \Spatie\QueueableAction\QueueableAction
 */
class CountAction
{
    use QueueableAction;

    /**
     * Execute the count action for the given model class.
     *
     * @param  class-string<Model>  $modelClass  The fully qualified model class name
     * @return int The total count of records
     *
     * @throws \InvalidArgumentException If model class is invalid or not found
     */
    public function execute(string $modelClass): int
    {
        if (! class_exists($modelClass)) {
            throw new \InvalidArgumentException("Model class [$modelClass] does not exist");
        }

        /** @var Model $model */
        $model = app($modelClass);

        if (! $model instanceof Model) {
            throw new \InvalidArgumentException("Class [$modelClass] must be an instance of ".Model::class);
        }

        $connection = $model->getConnection();
        $database = $connection->getDatabaseName();
        $driver = $connection->getDriverName();
        $table = $model->getTable();

        // Handle in-memory database
        if ($database === ':memory:') {
            return (int) $model->count();
        }
        // Handle SQLite specifically
        if ($driver === 'sqlite') {
            return (int) $model->count();
        }

        // Get count from table information for better performance
        $count = DB::table('information_schema.TABLES')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', $table)
            ->value('TABLE_ROWS');

        $result = is_int($count) ? $count : 0;

        Assert::integer($result, 'Count must be an integer');

        return $result;
    }
}
