<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Query;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\QueueableAction\QueueableAction;
use InvalidArgumentException;

final class GetFieldnamesByTablenameAction
{
    use QueueableAction;

    /**
     * Get column names from a table with specific database connection
     *
     * @param string $table Table name to get columns from
     * @param string|null $connectionName Database connection name (optional)
     * @return array<int, string>
     * @throws InvalidArgumentException
     */
    public function execute(string $table, ?string $connectionName = null): array
    {
        // Validate table name
        if (empty(trim($table))) {
            throw new InvalidArgumentException('Table name cannot be empty');
        }

        // Set connection if provided
        if ($connectionName !== null) {
            try {
                Schema::connection($connectionName);
                DB::connection($connectionName)->getPdo();
            } catch (\Throwable $e) {
                throw new InvalidArgumentException(
                    sprintf('Invalid database connection: %s', $connectionName)
                );
            }
        }

        try {
            // Check if table exists
            if (! Schema::connection($connectionName)->hasTable($table)) {
                throw new InvalidArgumentException(
                    sprintf('Table %s does not exist in connection %s', $table, $connectionName ?? 'default')
                );
            }

            // Get column listing
            $columns = Schema::connection($connectionName)->getColumnListing($table);

            // Ensure we have an array of strings
            return array_values(array_map('strval', $columns));

        } catch (\Throwable $e) {
            throw new InvalidArgumentException(
                sprintf('Error fetching columns: %s', $e->getMessage())
            );
        }
    }
}
