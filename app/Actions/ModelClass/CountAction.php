<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class CountAction
{
    use QueueableAction;

    /**
     * Cache per i conteggi delle tabelle per database.
     * [database => [table => count]]
     */
    private static array $cache = [];

    /**
     * Execute the count action for the given model class.
     *
     * @param class-string<Model> $modelClass
     */
    public function execute(string $modelClass): int
    {
        Assert::classExists($modelClass);
        Assert::subclassOf($modelClass, Model::class);

        /** @var Model $model */
        $model = app($modelClass);

        /** @var ConnectionInterface $connection */
        $connection = $model->getConnection();
        $database = $connection->getDatabaseName();
        $driver = $connection->getDriverName();
        $table = $model->getTable();

        // Handle special cases
        if (':memory:' === $database || 'sqlite' === $driver) {
            return (int) $model->count();
        }

        // Se non abbiamo ancora i dati per questo database, li carichiamo
        if (!isset(self::$cache[$database])) {
            self::$cache[$database] = $this->loadDatabaseCounts($database);
        }

        // Restituisci il conteggio dalla cache
        return self::$cache[$database][$table] ?? 0;
    }

    /**
     * Carica i conteggi di tutte le tabelle per un database.
     *
     * @param string $database Nome del database
     * 
     * @return array<string, int> Array associativo [table => count]
     */
    private function loadDatabaseCounts(string $database): array
    {
        $counts = DB::table('information_schema.TABLES')
            ->where('TABLE_SCHEMA', $database)
            ->select(['TABLE_NAME', 'TABLE_ROWS'])
            ->get()
            ->pluck('TABLE_ROWS', 'TABLE_NAME')
            ->toArray();

        // Converti tutti i valori in interi
        return array_map(function ($count) {
            return is_int($count) ? $count : 0;
        }, $counts);
    }

    /**
     * Pulisce la cache per un database specifico o per tutti i database.
     *
     * @param string|null $database Se specificato, pulisce solo quel database
     */
    public static function clearCache(?string $database = null): void
    {
        if ($database === null) {
            self::$cache = [];
        } else {
            unset(self::$cache[$database]);
        }
    }
}
