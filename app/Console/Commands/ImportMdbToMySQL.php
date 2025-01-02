<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;

class ImportMdbToMySQL extends Command
{
    /**
     * Il nome e la firma del comando.
     *
     * @var string
     */
    protected $signature = 'xot:import-mdb-to-mysql';

    /**
     * La descrizione del comando.
     *
     * @var string
     */
    protected $description = 'Importa un file .mdb in MySQL con un processo passo-passo';

    /**
     * Esegui il comando.
     *
     * @return void
     */
    public function handle()
    {
        // Chiedi il percorso del file .mdb
        $mdbFile = $this->ask('Per favore, inserisci il percorso del file .mdb');

        // Chiedi l'utente MySQL
        $mysqlUser = $this->ask('Per favore, inserisci l\'utente MySQL');

        // Chiedi la password MySQL
        $mysqlPassword = $this->secret('Per favore, inserisci la password MySQL');

        // Chiedi il nome del database MySQL
        $mysqlDb = $this->ask('Per favore, inserisci il nome del database MySQL');

        // Mostra i parametri ricevuti (opzionale, per verificare)
        $this->info("File .mdb: $mdbFile");
        $this->info("Utente MySQL: $mysqlUser");
        $this->info("Database MySQL: $mysqlDb");

        // Crea il database MySQL se non esiste
        $this->info("Creando il database MySQL: $mysqlDb...");
        $this->createDatabase($mysqlUser, $mysqlPassword, $mysqlDb);

        // Esporta le tabelle dal file .mdb
        $this->info('Esportando tabelle dal file .mdb in CSV...');
        $tables = $this->exportTablesToCSV($mdbFile);

        // Crea le tabelle in MySQL
        $this->info('Creando tabelle nel database MySQL...');
        $this->createTablesInMySQL($mdbFile, $mysqlUser, $mysqlPassword, $mysqlDb);

        // Carica i dati CSV nelle tabelle MySQL
        $this->info('Importando i dati CSV nelle tabelle MySQL...');
        $this->importDataToMySQL($tables, $mysqlUser, $mysqlPassword, $mysqlDb);

        $this->info('Processo completato!');
    }

    /**
     * Crea il database MySQL se non esiste.
     *
     * @param string $mysqlUser
     * @param string $mysqlPassword
     * @param string $mysqlDb
     */
    private function createDatabase($mysqlUser, $mysqlPassword, $mysqlDb)
    {
        $command = "mysql -u $mysqlUser -p$mysqlPassword -e 'CREATE DATABASE IF NOT EXISTS $mysqlDb;'";
        shell_exec($command);
    }

    /**
     * Esporta tutte le tabelle dal file .mdb in formato CSV.
     *
     * @param string $mdbFile
     *
     * @return array
     */
    private function exportTablesToCSV($mdbFile)
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

        return $tables;
    }

    /**
     * Crea le tabelle nel database MySQL basandosi sullo schema del file .mdb.
     *
     * @param string $mdbFile
     * @param string $mysqlUser
     * @param string $mysqlPassword
     * @param string $mysqlDb
     */
    private function createTablesInMySQL($mdbFile, $mysqlUser, $mysqlPassword, $mysqlDb)
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
     * @param array  $tables
     * @param string $mysqlUser
     * @param string $mysqlPassword
     * @param string $mysqlDb
     */
    private function importDataToMySQL($tables, $mysqlUser, $mysqlPassword, $mysqlDb)
    {
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
