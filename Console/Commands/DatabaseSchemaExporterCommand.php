<?php

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseSchemaExporterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:schema-exporter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export the database schema';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Ottieni la lista di tutte le tabelle nel database.
        $tables = $this->getTables('mysql');

        // Ora puoi utilizzare $tables come preferisci
        $this->info('Tabelle trovate: ' . implode(', ', $tables));

        return 0;
    }

    /**
     * Ottieni la lista di tutte le tabelle nel database.
     */
    private function getTables(string $connection): array
    {
        // Utilizziamo un approccio alternativo che funziona con qualunque connessione
        $tables = DB::connection($connection)
            ->select('SHOW TABLES');
        
        // Otteniamo il nome del database dalla configurazione
        $databaseConfig = config("database.connections.{$connection}.database");
        // Convertiamo esplicitamente in stringa, gestendo il caso in cui il valore potrebbe essere null
        $databaseName = is_string($databaseConfig) ? $databaseConfig : '';
        
        // Il risultato contiene un array di oggetti con una proprietà del tipo Tables_in_{database}
        $tableKey = "Tables_in_{$databaseName}";
        
        return array_map(function ($table) use ($tableKey) {
            return $table->$tableKey;
        }, $tables);
    }
} 