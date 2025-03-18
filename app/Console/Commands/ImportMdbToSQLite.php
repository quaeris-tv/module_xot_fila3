    /**
     * Estrae le tabelle dal file .mdb e le converte in file CSV.
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