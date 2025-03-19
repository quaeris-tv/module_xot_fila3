<?php

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

use function Safe\json_encode;
use function Safe\json_decode;
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\glob;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\error_log;

class DatabaseSchemaExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xot:schema-export {connection? : Nome della connessione database} {--output=docs/db_schema.json : Percorso file di output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Esporta lo schema del database in un file JSON completo per facilitare la creazione di modelli e migrazioni';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $databaseName = $this->argument('database') ?? config('database.connections.mysql.database');
        if (! is_string($databaseName)) {
            $databaseName = (string) $databaseName;
        }
        $this->info('schemaexport '.$databaseName);
        
        $connection = $this->argument('connection') ?: $this->ask('Inserisci il nome della connessione database');
        $outputPath = $this->option('output');

        // Assicurati che il percorso sia assoluto
        if ($outputPath !== null && !Str::startsWith($outputPath, '/')) {
            $outputPath = base_path($outputPath);
        }

        // Assicurati che connection sia una stringa
        $connectionStr = is_string($connection) ? $connection : (string)$connection;
        
        $this->info("Estrazione schema dal database usando la connessione: {$connectionStr}");

        try {
            // Imposta la connessione database
            DB::setDefaultConnection($connectionStr);
            $databaseName = DB::connection()->getDatabaseName();

            // Assicurati che databaseName sia una stringa
            $databaseNameStr = is_string($databaseName) ? $databaseName : (string)$databaseName;
            
            $this->info("Connesso al database: {$databaseNameStr}");

            // Ottieni tutte le tabelle
            $tables = DB::select('SHOW TABLES');
            $tablesKey = 'Tables_in_' . $databaseNameStr;

            $schema = [
                'database' => $databaseNameStr,
                'connection' => $connectionStr,
                'tables' => [],
                'relationships' => [],
                'generated_at' => now()->toIso8601String(),
            ];

            $bar = $this->output->createProgressBar(count($tables));
            $bar->start();

            foreach ($tables as $table) {
                $tableName = $table->$tablesKey;
                $this->info("\nAnalyzing table: {$tableName}");

                // Ottieni la struttura della tabella
                $columns = DB::select("SHOW FULL COLUMNS FROM `{$tableName}`");
                $indices = DB::select("SHOW INDEX FROM `{$tableName}`");

                // Ottieni la DDL della tabella per poi estrarre le FK constraints
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTableSql = $createTable[0]->{'Create Table'};

                // Estrai foreign keys
                try {
                    $result = \Safe\preg_match_all('/CONSTRAINT\s+`([^`]+)`\s+FOREIGN\s+KEY\s+\(`([^`]+)`\)\s+REFERENCES\s+`([^`]+)`\s+\(`([^`]+)`\)/i', $createTableSql, $foreignKeys, PREG_SET_ORDER);
                } catch (\Exception $e) {
                    $this->error("Errore nell'analisi delle foreign keys per la tabella {$tableName}: " . $e->getMessage());
                    $foreignKeys = [];
                }

                $tableSchema = [
                    'name' => $tableName,
                    'columns' => [],
                    'indices' => [],
                    'foreign_keys' => [],
                    'primary_key' => null,
                    'model_name' => $this->getModelName($tableName),
                    'migration_name' => $this->getMigrationName($tableName),
                ];

                // Processa colonne
                foreach ($columns as $column) {
                    $columnSchema = [
                        'name' => $column->Field,
                        'type' => $column->Type,
                        'null' => $column->Null === 'YES',
                        'key' => $column->Key,
                        'default' => $column->Default,
                        'extra' => $column->Extra,
                        'comment' => $column->Comment,
                    ];

                    if ($column->Key === 'PRI') {
                        $tableSchema['primary_key'] = $column->Field;
                    }

                    $tableSchema['columns'][$column->Field] = $columnSchema;
                }

                // Processa indici
                $groupedIndices = [];
                foreach ($indices as $index) {
                    $indexName = $index->Key_name;

                    if (!isset($groupedIndices[$indexName])) {
                        $groupedIndices[$indexName] = [
                            'name' => $indexName,
                            'columns' => [],
                            'unique' => !$index->Non_unique,
                            'type' => $index->Index_type,
                        ];
                    }

                    $groupedIndices[$indexName]['columns'][] = [
                        'name' => $index->Column_name,
                        'order' => $index->Seq_in_index,
                    ];
                }

                $tableSchema['indices'] = array_values($groupedIndices);

                // Processa foreign keys
                foreach ($foreignKeys as $fk) {
                    $tableSchema['foreign_keys'][] = [
                        'name' => $fk[1],
                        'column' => $fk[2],
                        'references_table' => $fk[3],
                        'references_column' => $fk[4],
                    ];

                    // Registra anche nella sezione delle relazioni
                    $schema['relationships'][] = [
                        'type' => 'belongsTo',
                        'local_table' => $tableName,
                        'local_column' => $fk[2],
                        'foreign_table' => $fk[3],
                        'foreign_column' => $fk[4],
                        'constraint_name' => $fk[1],
                    ];
                }

                $schema['tables'][$tableName] = $tableSchema;
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);

            // Crea directory se non esiste
            if ($outputPath !== null) {
                $directory = dirname($outputPath);
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                // Salva lo schema in un file JSON
                try {
                    $jsonContent = \Safe\json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    File::put($outputPath, $jsonContent);
                    $this->info("Schema del database esportato con successo in: {$outputPath}");
                } catch (\Exception $e) {
                    $this->error("Errore nell'encoding JSON dello schema: " . $e->getMessage());
                    return Command::FAILURE;
                }
            }

            // Genera un report riassuntivo
            $this->generateReport($schema);

        } catch (\Exception $e) {
            $this->error("Errore durante l'estrazione dello schema: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Genera un nome per il modello in base al nome della tabella.
     */
    protected function getModelName(string $tableName): string
    {
        // Rimuovi eventuali prefissi comuni
        $prefixes = ['tbl_', 'anagr_'];
        foreach ($prefixes as $prefix) {
            if (Str::startsWith($tableName, $prefix)) {
                $tableName = Str::substr($tableName, strlen($prefix));
                break;
            }
        }

        // Converti da snake_case a PascalCase
        return Str::studly(Str::singular($tableName));
    }

    /**
     * Genera un nome per la migrazione in base al nome della tabella.
     */
    protected function getMigrationName(string $tableName): string
    {
        return 'create_' . $tableName . '_table';
    }

    /**
     * Genera un report riassuntivo dello schema.
     * 
     * @param array{database: string, connection: string, tables: array<string, array>, relationships: array<int, array>, generated_at: string} $schema Schema del database esportato
     */
    protected function generateReport(array $schema): void
    {
        $this->info("Riepilogo Schema Database");
        $this->info("=============================================");
        $this->info("Database: " . $schema['database']);
        $this->info("Numero tabelle: " . count($schema['tables']));
        $this->info("Numero relazioni: " . count($schema['relationships']));

        $this->newLine();
        $this->info("Tabelle principali:");

        // Mostra le tabelle più rilevanti (con più relazioni o colonne)
        if (!isset($schema['tables']) || !is_array($schema['tables']) || !isset($schema['relationships']) || !is_array($schema['relationships'])) {
            $this->error('Schema non valido: mancano tables o relationships');
            return;
        }

        /** @var \Illuminate\Support\Collection<string, array<string, mixed $relevantTables */
        $relevantTables = collect($schema['tables'])
            ->map(function (array $table, string $tableName) use ($schema): array {
                /** @var \Illuminate\Support\Collection<int, array<string, mixed $relationships */
                $relationships = collect($schema['relationships']);
                
                /** @var int $relationCount */
                $relationCount = $relationships
                    ->filter(function (array $rel) use ($tableName): bool {
                        return $rel['local_table'] === $tableName || $rel['foreign_table'] === $tableName;
                    })
                    ->count();

                return [
                    'name' => $tableName,
                    'columns' => isset($table['columns']) && is_array($table['columns']) ? count($table['columns']) : 0,
                    'relations' => $relationCount,
                    'model' => $table['model_name'] ?? '',
                ];
            })
            ->sortByDesc('relations')
            ->take(10);

        $this->table(
            ['Tabella', 'Colonne', 'Relazioni', 'Modello Suggerito'],
            $relevantTables->values()->toArray()
        );

        $this->newLine();
        $this->info("File JSON generato correttamente. Puoi usarlo per creare modelli, migrazioni, factories e seeder.");
    }

    private function getDatabaseTableList(string $databaseName): array
    {
        $sql = 'SHOW TABLES FROM '.$this->wrapValue($databaseName);

        $result = DB::select($sql);
        $list = collect($result)->map(
            function (object $item) {
                $item_vars = get_object_vars($item);
                /** @var string $first_var */
                $first_var = reset($item_vars);
                return $first_var;
            }
        )->toArray();

        return $list;
    }

    private function getUnescapedTableName(string $databaseName, string $tableName): string
    {
        $sql = 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?';
        $rows = DB::select($sql, [$databaseName, $tableName]);
        
        if (! isset($rows[0])) {
            return $tableName;
        }
        
        $table_name = $rows[0]->TABLE_NAME;
        if (! is_string($table_name)) {
            $table_name = (string) $table_name;
        }
        
        return $table_name;
    }
}
