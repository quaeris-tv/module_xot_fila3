<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Webmozart\Assert\Assert;

use function Safe\shell_exec;

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
     *
     * @return int
     */
    public function handle(): int
    {
        $mdbFile = (string)$this->argument('mdbFile');
        $mysqlUser = (string)$this->argument('mysqlUser');
        $mysqlPassword = (string)$this->argument('mysqlPassword');
        $mysqlDb = (string)$this->argument('mysqlDb');

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
     *
     * @param string $mysqlUser
     * @param string $mysqlPassword
     * @param string $mysqlDb
     */
    private function createDatabase(string $mysqlUser, string $mysqlPassword, string $mysqlDb): void
    {
        $command = "mysql -u $mysqlUser -p$mysqlPassword -e 'CREATE DATABASE IF NOT EXISTS $mysqlDb;'";
        shell_exec($command);
    }

    /**
     * Esporta tutte le tabelle dal file .mdb in formato CSV.
     *
     * @param string $mdbFile
     */
    private function exportTablesToCSV(string $mdbFile): void
    {
        $tables = [];
        $tableList = shell_exec("mdb-tables $mdbFile");

        // Esporta ogni tabella in un file CSV
        foreach (explode("\n", trim($tableList)) as $table) {
            if (empty($table)) {
                continue;
            }
            $tables[] = $table;
            $csvFile = storage_path("app/{$table}.csv");
            shell_exec("mdb-export $mdbFile $table > $csvFile");
        }
    }

    /**
     * Crea le tabelle nel database MySQL basandosi sullo schema del file .mdb.
     *
     * @param string $mdbFile
     * @param string $mysqlUser
     * @param string $mysqlPassword
     * @param string $mysqlDb
     */
    private function createTablesInMySQL(string $mdbFile, string $mysqlUser, string $mysqlPassword, string $mysqlDb): void
    {
        $schema = shell_exec("mdb-schema $mdbFile mysql");
        $tables = explode(";\n", $schema);

        foreach ($tables as $tableSchema) {
            if (empty($tableSchema)) {
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
     *
     * @param string $mdbFile
     * @param string $mysqlUser
     * @param string $mysqlPassword
     * @param string $mysqlDb
     */
    private function importDataToMySQL(string $mdbFile, string $mysqlUser, string $mysqlPassword, string $mysqlDb): void
    {
        $tables = $this->exportTablesToCSV($mdbFile);

        foreach ($tables as $table) {
            $csvFile = storage_path("app/{$table}.csv");
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
