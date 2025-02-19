<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Counts records for a given model class using optimized table information.
 */
class CountAction
{
    use QueueableAction;

    /**
     * Cached table counts per database.
     *
     * @var array<string, array<string, int>>
     */
    protected static array $tableCounts = [];

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

        // Handle special cases
        if (':memory:' === $database || 'sqlite' === $driver) {
            return (int) $model->count();
        }

        // Get or load table counts for this database
        if (!isset(static::$tableCounts[$database])) {
            static::$tableCounts[$database] = $this->loadTableCounts($database);
        }

        return static::$tableCounts[$database][$table] ?? 0;
    }

    /**
     * Load all table counts for a database in a single query.
     *
     * @param string $database Database name
     * @return array<string, int> Array of table counts indexed by table name
     */
    protected function loadTableCounts(string $database): array
    {
        $counts = DB::table('information_schema.TABLES')
            ->where('TABLE_SCHEMA', $database)
            ->pluck('TABLE_ROWS', 'TABLE_NAME')
            ->map(fn ($count) => is_int($count) ? $count : 0)
            ->all();

        Assert::isArray($counts);
        
        return $counts;
    }
}
