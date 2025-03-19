<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use function Safe\shell_exec;
use Webmozart\Assert\Assert;

class ImportMdbToMySQL extends Command
{
    /**
     * Il nome e la firma del comando.
     *
     * @var string
     */
    protected $signature = 'mdb:import-mysql {mdbFile} {mysqlUser} {mysqlPassword} {mysqlDb}';

    /**
     * La descrizione del comando.
     *
     * @var string
     */
    protected $description = 'Import MDB file to MySQL database';

    /**
     * Esegui il comando.
     */
    public function handle(): int
    {
        $mdbFile = $this->argument('mdbFile');
        $mysqlUser = $this->argument('mysqlUser');
        $mysqlPassword = $this->argument('mysqlPassword');
        $mysqlDb = $this->argument('mysqlDb');

        if (!is_string($mdbFile) || !is_string($mysqlUser) || !is_string($mysqlPassword) || !is_string($mysqlDb)) {
            $this->error('Tutti i parametri devono essere stringhe');
            return Command::FAILURE;
        }

        Assert::fileExists($mdbFile, "MDB file not found: {$mdbFile}");

        $this->info("Importing {$mdbFile} to MySQL database {$mysqlDb}");

        $this->createDatabase($mysqlUser, $mysqlPassword, $mysqlDb);
        $this->exportTablesToCSV($mdbFile);
        $this->createTablesInMySQL($mdbFile, $mysqlUser, $mysqlPassword, $mysqlDb);
        $this->importDataToMySQL($mdbFile, $mysqlUser, $mysqlPassword, $mysqlDb);

        return Command::SUCCESS;
    }

    /**
     * Crea il database MySQL se non esiste.
     */
    private function createDatabase(string $mysqlUser, string $mysqlPassword, string $mysqlDb): void
    {
        $command = "mysql -u $mysqlUser -p$mysqlPassword -e 'CREATE DATABASE IF NOT EXISTS $mysqlDb;'";
        shell_exec($command);
    }

    /**
     * Esporta tutte le tabelle dal file .mdb in formato CSV.
     * 
     * @return string[] Array di nomi di tabelle esportate
     */
    private function exportTablesToCSV(string $mdbFile): array
    {
        $tables = [];
        $tableList = shell_exec("mdb-tables $mdbFile");

        // Verifica che tableList non sia null
        if ($tableList === null) {
            $this->error("Impossibile ottenere la lista delle tabelle da $mdbFile");
            return $tables;
        }

        // Esporta ogni tabella in un file CSV
        $tables = array_filter(explode("\n", trim((string)$tableList)));
        foreach ($tables as $table) {
            if (!is_string($table)) {
                continue;
            }
            $csvFile = storage_path("app/{$table}.csv");
            shell_exec("mdb-export $mdbFile $table > $csvFile");
        }
        
        return $tables;
    }

    /**
     * Crea le tabelle nel database MySQL basandosi sullo schema del file .mdb.
     */
    private function createTablesInMySQL(string $mdbFile, string $mysqlUser, string $mysqlPassword, string $mysqlDb): void
    {
        $schema = shell_exec("mdb-schema $mdbFile mysql");
        if ($schema === null) {
            $this->error("Impossibile ottenere lo schema da $mdbFile");
            return;
        }

        $tables = explode(";\n", $schema);

        foreach ($tables as $tableSchema) {
            if (!is_string($tableSchema) || trim($tableSchema) === '') {
                continue;
            }
            // Adatta le virgolette per MySQL
            $tableSchema = str_replace('`', '"', $tableSchema);
            // Crea la tabella in MySQL
            $command = "mysql -u $mysqlUser -p$mysqlPassword $mysqlDb -e \"$tableSchema;\"";
            shell_exec($command);
        }
    }

    /**
     * Importa i dati CSV nelle tabelle MySQL.
     */
    private function importDataToMySQL(string $mdbFile, string $mysqlUser, string $mysqlPassword, string $mysqlDb): void
    {
        $tables = $this->exportTablesToCSV($mdbFile);

        // Verifica che $tables non sia vuoto
        if (empty($tables)) {
            $this->error('Nessuna tabella da importare');
            return;
        }

        foreach ($tables as $table) {
            if (!is_string($table)) {
                continue;
            }
            $csvFile = storage_path("app/{$table}.csv");
            if (!file_exists($csvFile)) {
                $this->error("File CSV non trovato: $csvFile");
                continue;
            }
            $command = "mysql -u $mysqlUser -p$mysqlPassword $mysqlDb -e "
                ."\"LOAD DATA LOCAL INFILE '$csvFile' "
                ."INTO TABLE $table "
                ."FIELDS TERMINATED BY ',' "
                ."ENCLOSED BY '\"' "
                ."LINES TERMINATED BY '\\n' "
                .'IGNORE 1 LINES;"';
            shell_exec($command);
        }
    }
}
