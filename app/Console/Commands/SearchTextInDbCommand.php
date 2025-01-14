<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SearchTextInDbCommand extends Command
{
    protected $signature = 'db:search-text {search : The text to search for} {--tables=* : Optional specific tables to search in}';
    protected $description = 'Search for text in all database tables or specific tables';

    public function handle()
    {
        $searchString = $this->argument('search');
        $specificTables = $this->option('tables');

        $tables = empty($specificTables)
            ? Schema::getAllTables()
            : $specificTables;

        foreach ($tables as $table) {
            $tableName = is_object($table) ? $table->Tables_in_database : $table;

            if (! Schema::hasTable($tableName)) {
                $this->warn("Table {$tableName} does not exist");
                continue;
            }

            $this->info("Searching in table: {$tableName}");

            $columns = Schema::getColumnListing($tableName);

            foreach ($columns as $column) {
                $columnType = Schema::getColumnType($tableName, $column);

                // Search only in string-like columns
                if (! in_array($columnType, ['string', 'text'])) {
                    continue;
                }

                $results = DB::table($tableName)
                    ->select('*')
                    ->whereRaw("LOWER(`$column`) LIKE ?", ['%'.strtolower($searchString).'%'])
                    ->get();

                if ($results->isNotEmpty()) {
                    $this->info("Found in column: $column");
                    foreach ($results as $result) {
                        $this->table(
                            ['Column', 'Value'],
                            collect($result)
                                ->map(fn ($value, $key) => [$key, $value])
                                ->toArray()
                        );
                        $this->newLine();
                    }
                }
            }
        }

        return Command::SUCCESS;
    }
}
