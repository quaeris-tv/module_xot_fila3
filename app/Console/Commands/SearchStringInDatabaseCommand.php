<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SearchStringInDatabaseCommand extends Command
{
    // Nome e descrizione del comando
    protected $signature = 'xot:db-search-string';
    protected $description = 'Cerca una stringa in tutte le tabelle e colonne del database';

    // Esegui il comando
    public function handle()
    {
        // Chiedi all'utente di inserire la stringa da cercare
        $searchString = $this->ask('Inserisci la stringa da cercare:');

        if (empty($searchString)) {
            $this->error('La stringa di ricerca non può essere vuota.');

            return;
        }

        // Ottieni il nome del database corrente
        $databaseName = env('DB_DATABASE');

        // Ottieni la lista delle tabelle nel database
        $tables = DB::select('SHOW TABLES');

        $this->info("Inizio ricerca della stringa '$searchString' in tutte le tabelle...");

        $foundResults = false;

        // Fai una ricerca in tutte le tabelle e colonne
        foreach ($tables as $table) {
            $tableName = $table->{'Tables_in_'.$databaseName};

            // Ottieni la lista delle colonne per ciascuna tabella
            $columns = DB::select("DESCRIBE `$tableName`");

            foreach ($columns as $column) {
                // Controlla se la colonna è di tipo stringa (VARCHAR, TEXT, CHAR)
                // if (in_array($column->Type, ['varchar', 'text', 'char'])) {
                // Esegui la ricerca nella colonna
                try {
                    $results = DB::table($tableName)
                        ->where($column->Field, 'LIKE', $searchString)
                        ->get();

                    // Se trovi risultati, informa l'utente
                    if ($results->isNotEmpty()) {
                        $this->info("Trovato in $tableName.$column->Field");
                        $foundResults = true;
                    }
                } catch (\Exception $e) {
                    $this->error("Errore nella tabella $tableName, colonna $column->Field: ".$e->getMessage());
                }
                // }
            }
        }

        if (! $foundResults) {
            $this->info("Nessun risultato trovato per '$searchString'.");
        } else {
            $this->info('Ricerca completata!');
        }
    }
}
