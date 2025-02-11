<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use RuntimeException;
use function Safe\shell_exec;
use function Safe\sprintf;

class ImportMdbToSQLite extends Command
{
    /**
     * Il nome e la firma del comando.
     *
     * @var string
     */
    protected $signature = 'xot:import-mdb-to-sqlite';

    /**
     * La descrizione del comando.
     *
     * @var string
     */
    protected $description = 'Importa un file .mdb in SQLite con un processo passo-passo';

    /**
     * Esegui il comando.
     */
    public function handle(): void
    {
        /** @var string */
        $mdbFile = $this->ask('Per favore, inserisci il percorso del file .mdb');

        /** @var string */
        $sqliteDb = $this->ask('Per favore, inserisci il nome del database SQLite (includi l\'estensione .sqlite)');

        $this->info(sprintf("File .mdb: %s", $mdbFile));
        $this->info(sprintf("Database SQLite: %s", $sqliteDb));

        try {
            $this->info('Esportando tabelle dal file .mdb in CSV...');
            $tables = $this->exportTablesToCSV($mdbFile);

            $this->info('Creando tabelle nel database SQLite...');
            $this->createTables($mdbFile, $sqliteDb);

            $this->info('Importando i dati CSV nelle tabelle SQLite...');
            $this->importDataToSQLite($tables, $sqliteDb);

            $this->info('Processo completato!');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Esporta tutte le tabelle dal file .mdb in formato CSV.
     *
     * @param string $mdbFile
     * @return array<int, string>
     */
    private function exportTablesToCSV(string $mdbFile): array
    {
        $tables = [];
        try {
            $result = shell_exec(sprintf("mdb-tables %s", $mdbFile));
            
            foreach (explode("\n", trim($result)) as $table) {
                if (empty($table)) {
                    continue;
                }
                $tables[] = $table;
                $csvFile = storage_path(sprintf("app/%s.csv", $table));
                shell_exec(sprintf("mdb-export %s %s > %s", $mdbFile, $table, $csvFile));
            }

            return $tables;
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf('Errore durante l\'esportazione delle tabelle: %s', $e->getMessage()));
        }
    }

    /**
     * Crea le tabelle nel database SQLite basandosi sullo schema del file .mdb.
     *
     * @param string $mdbFile
     * @param string $sqliteDb
     * @return void
     */
    private function createTables(string $mdbFile, string $sqliteDb): void
    {
        try {
            $schema = shell_exec(sprintf("mdb-schema %s sqlite", $mdbFile));
            $tables = explode(";\n", $schema);

            foreach ($tables as $tableSchema) {
                if (empty($tableSchema)) {
                    continue;
                }
                
                $tableSchema = str_replace('`', '"', $tableSchema);
                shell_exec(sprintf('sqlite3 %s "%s;"', $sqliteDb, $tableSchema));
            }
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf('Errore durante la creazione delle tabelle: %s', $e->getMessage()));
        }
    }

    /**
     * Importa i dati CSV nelle tabelle SQLite.
     *
     * @param array<int, string> $tables
     * @param string $sqliteDb
     * @return void
     */
    private function importDataToSQLite(array $tables, string $sqliteDb): void
    {
        try {
            foreach ($tables as $table) {
                $csvFile = storage_path(sprintf("app/%s.csv", $table));
                shell_exec(sprintf('sqlite3 %s ".mode csv" ".import %s %s"', $sqliteDb, $csvFile, $table));
            }
        } catch (\Exception $e) {
            throw new RuntimeException(sprintf('Errore durante l\'importazione dei dati: %s', $e->getMessage()));
        }
    }
}
