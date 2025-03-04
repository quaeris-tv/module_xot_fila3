<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSchemaExporterCommand extends Command
{
    /**
     * Il nome e la firma del comando console.
     *
     * @var string
     */
    protected $signature = 'xot:export-db-schema {connection? : Nome della connessione database}';

    /**
     * La descrizione del comando console.
     *
     * @var string
     */
    protected $description = 'Esporta lo schema completo di un database in formato JSON';

    /**
     * Esegui il comando console.
     */
    public function handle(): int
    {
        $connection = $this->argument('connection') ?? config('database.default');

        $this->info("Esportazione dello schema del database dalla connessione: {$connection}");

        // Ottieni il nome del database dalla configurazione
        $databaseName = config("database.connections.{$connection}.database");

        if (empty($databaseName)) {
            $this->error("Impossibile trovare il database per la connessione {$connection}");

            return 1;
        }

        $this->info("Database: {$databaseName}");

        // Ottieni la lista di tutte le tabelle
        $tables = $this->getTables($connection);

        if (empty($tables)) {
            $this->error("Nessuna tabella trovata nel database {$databaseName}");

            return 1;
        }

        $this->info('Trovate '.count($tables).' tabelle.');

        // Inizializza l'array che conterrà tutte le informazioni
        $databaseSchema = [
            'database' => $databaseName,
            'connection' => $connection,
            'tables' => [],
        ];

        $progressBar = $this->output->createProgressBar(count($tables));
        $progressBar->start();

        foreach ($tables as $table) {
            $databaseSchema['tables'][$table] = $this->getTableInfo($connection, $table);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        // Aggiungi informazioni sulle relazioni tra tabelle
        $databaseSchema['relationships'] = $this->getRelationships($connection, $tables);

        // Crea directory se non esiste
        $outputDir = base_path('docs');
        if (! File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Salva i dati in un file JSON
        $filename = "{$outputDir}/{$databaseName}_schema.json";
        File::put($filename, json_encode($databaseSchema, JSON_PRETTY_PRINT));

        $this->info("Schema del database esportato con successo in: {$filename}");

        return 0;
    }

    /**
     * Ottieni la lista di tutte le tabelle nel database.
     */
    private function getTables(string $connection): array
    {
        // Versione più moderna e compatibile per ottenere l'elenco delle tabelle
        $databaseName = DB::connection($connection)->getDatabaseName();
        $tables = DB::connection($connection)
            ->select("SELECT table_name FROM information_schema.tables WHERE table_schema = ? AND table_type = 'BASE TABLE'", [$databaseName]);

        return array_map(function ($table) {
            return $table->table_name;
        }, $tables);
    }

    /**
     * Ottieni informazioni dettagliate su una tabella.
     */
    private function getTableInfo(string $connection, string $table): array
    {
        $columns = $this->getTableColumns($connection, $table);
        $indexes = $this->getTableIndexes($connection, $table);
        $primaryKey = $this->getTablePrimaryKey($connection, $table);
        $foreignKeys = $this->getTableForeignKeys($connection, $table);
        $recordCount = $this->getTableRecordCount($connection, $table);
        $sampleData = $this->getTableSampleData($connection, $table);

        return [
            'name' => $table,
            'columns' => $columns,
            'indexes' => $indexes,
            'primary_key' => $primaryKey,
            'foreign_keys' => $foreignKeys,
            'record_count' => $recordCount,
            'sample_data' => $sampleData,
        ];
    }

    /**
     * Ottieni informazioni su tutte le colonne di una tabella.
     */
    private function getTableColumns(string $connection, string $table): array
    {
        $columns = [];
        $columnInfo = DB::connection($connection)->select("SHOW FULL COLUMNS FROM `{$table}`");

        foreach ($columnInfo as $column) {
            $columns[$column->Field] = [
                'type' => $column->Type,
                'nullable' => $column->Null === 'YES',
                'default' => $column->Default,
                'comment' => $column->Comment ?? '',
                'extra' => $column->Extra ?? '',
            ];
        }

        return $columns;
    }

    /**
     * Ottieni gli indici di una tabella.
     */
    private function getTableIndexes(string $connection, string $table): array
    {
        $indexes = [];
        $indexInfo = DB::connection($connection)->select("SHOW INDEX FROM `{$table}`");

        foreach ($indexInfo as $index) {
            if (! isset($indexes[$index->Key_name])) {
                $indexes[$index->Key_name] = [
                    'columns' => [],
                    'unique' => ! $index->Non_unique,
                ];
            }

            $indexes[$index->Key_name]['columns'][] = $index->Column_name;
        }

        return $indexes;
    }

    /**
     * Ottieni la chiave primaria di una tabella.
     */
    private function getTablePrimaryKey(string $connection, string $table): ?array
    {
        $indexInfo = DB::connection($connection)->select("SHOW INDEX FROM `{$table}` WHERE Key_name = 'PRIMARY'");

        if (empty($indexInfo)) {
            return null;
        }

        $primaryKey = [
            'name' => 'PRIMARY',
            'columns' => [],
        ];

        foreach ($indexInfo as $index) {
            $primaryKey['columns'][] = $index->Column_name;
        }

        return $primaryKey;
    }

    /**
     * Ottieni le chiavi esterne di una tabella.
     */
    private function getTableForeignKeys(string $connection, string $table): array
    {
        $foreignKeys = [];
        $databaseName = DB::connection($connection)->getDatabaseName();

        // Utilizziamo query SQL dirette per ottenere le chiavi esterne
        $fkResults = DB::connection($connection)->select('
            SELECT 
                k.CONSTRAINT_NAME as constraint_name,
                k.COLUMN_NAME as column_name,
                k.REFERENCED_TABLE_NAME as referenced_table,
                k.REFERENCED_COLUMN_NAME as referenced_column
            FROM information_schema.KEY_COLUMN_USAGE k
            WHERE 
                k.TABLE_SCHEMA = ? AND
                k.TABLE_NAME = ? AND
                k.REFERENCED_TABLE_NAME IS NOT NULL
            ORDER BY constraint_name
        ', [$databaseName, $table]);

        // Raggruppiamo le chiavi esterne per nome del vincolo
        foreach ($fkResults as $fk) {
            if (! isset($foreignKeys[$fk->constraint_name])) {
                $foreignKeys[$fk->constraint_name] = [
                    'name' => $fk->constraint_name,
                    'local_columns' => [],
                    'foreign_table' => $fk->referenced_table,
                    'foreign_columns' => [],
                ];
            }

            $foreignKeys[$fk->constraint_name]['local_columns'][] = $fk->column_name;
            $foreignKeys[$fk->constraint_name]['foreign_columns'][] = $fk->referenced_column;
        }

        return $foreignKeys;
    }

    /**
     * Ottieni il numero di record in una tabella.
     */
    private function getTableRecordCount(string $connection, string $table): int
    {
        return DB::connection($connection)->table($table)->count();
    }

    /**
     * Ottieni un campione di dati dalla tabella.
     */
    private function getTableSampleData(string $connection, string $table, int $limit = 5): array
    {
        try {
            return DB::connection($connection)->table($table)->limit($limit)->get()->toArray();
        } catch (\Exception $e) {
            $this->warn("Impossibile ottenere dati di esempio per la tabella {$table}: ".$e->getMessage());

            return [];
        }
    }

    /**
     * Analizza le relazioni tra le tabelle.
     */
    private function getRelationships(string $connection, array $tables): array
    {
        $relationships = [];

        foreach ($tables as $table) {
            $foreignKeys = $this->getTableForeignKeys($connection, $table);

            foreach ($foreignKeys as $name => $foreignKey) {
                $relationships[] = [
                    'type' => 'belongs_to',
                    'from_table' => $table,
                    'from_columns' => $foreignKey['local_columns'],
                    'to_table' => $foreignKey['foreign_table'],
                    'to_columns' => $foreignKey['foreign_columns'],
                ];

                // Aggiungi anche la relazione inversa (has_many)
                $relationships[] = [
                    'type' => 'has_many',
                    'from_table' => $foreignKey['foreign_table'],
                    'from_columns' => $foreignKey['foreign_columns'],
                    'to_table' => $table,
                    'to_columns' => $foreignKey['local_columns'],
                ];
            }
        }

        return $relationships;
    }
}
