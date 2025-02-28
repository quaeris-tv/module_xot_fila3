<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateDbDocumentationCommand extends Command
{
    /**
     * Il nome e la firma del comando console.
     *
     * @var string
     */
    protected $signature = 'xot:generate-db-documentation {schema_file : Percorso del file schema JSON} {output_dir? : Directory di output per i file markdown}';

    /**
     * La descrizione del comando console.
     *
     * @var string
     */
    protected $description = 'Genera documentazione in formato Markdown per lo schema del database';

    /**
     * Esegui il comando console.
     */
    public function handle(): int
    {
        $schemaFilePath = $this->argument('schema_file');
        $outputDir = $this->argument('output_dir') ?? base_path('docs/database');

        if (!File::exists($schemaFilePath)) {
            $this->error("Il file schema {$schemaFilePath} non esiste!");
            return 1;
        }

        $schemaContent = File::get($schemaFilePath);
        $schema = json_decode($schemaContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Errore nella decodifica del file JSON: " . json_last_error_msg());
            return 1;
        }

        // Crea directory se non esiste
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // Genera il file README.md principale con l'indice
        $this->generateMainReadme($schema, $outputDir);

        // Genera file per ogni tabella
        $progressBar = $this->output->createProgressBar(count($schema['tables']));
        $progressBar->start();

        foreach ($schema['tables'] as $tableName => $tableInfo) {
            $this->generateTableDoc($tableName, $tableInfo, $schema['relationships'], $outputDir);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->info("Documentazione generata con successo in: {$outputDir}");

        return 0;
    }

    /**
     * Genera il file README.md principale con l'indice.
     */
    protected function generateMainReadme(array $schema, string $outputDir): void
    {
        $database = $schema['database'];
        $tableCount = count($schema['tables']);
        
        $content = <<<MARKDOWN
# Documentazione Database: {$database}

## Panoramica
Questo documento fornisce una documentazione completa per il database **{$database}**.

- **Database**: {$database}
- **Connessione**: {$schema['connection']}
- **Tabelle**: {$tableCount}

## Indice delle Tabelle

| Nome Tabella | Descrizione | Numero Record |
|--------------|-------------|---------------|

MARKDOWN;

        foreach ($schema['tables'] as $tableName => $tableInfo) {
            $recordCount = $tableInfo['record_count'];
            $fileName = $tableName . '.md';
            $content .= "| [{$tableName}]({$fileName}) | | {$recordCount} |\n";
        }

        $content .= "\n\n## Diagramma ER\n\n";
        $content .= "```mermaid\nerDiagram\n";

        // Aggiungi gli ER per le relazioni
        $processedRelationships = [];
        foreach ($schema['relationships'] as $relationship) {
            if ($relationship['type'] === 'belongs_to') {
                $from = $relationship['from_table'];
                $to = $relationship['to_table'];
                $key = "{$from}_{$to}";

                if (!in_array($key, $processedRelationships)) {
                    $content .= "    {$from} ||--o{ {$to} : \"\"\n";
                    $processedRelationships[] = $key;
                }
            }
        }

        $content .= "```\n";

        File::put($outputDir . '/README.md', $content);
    }

    /**
     * Genera documentazione per una singola tabella.
     */
    protected function generateTableDoc(string $tableName, array $tableInfo, array $relationships, string $outputDir): void
    {
        $columns = $tableInfo['columns'];
        $primaryKey = $tableInfo['primary_key'];
        $indexes = $tableInfo['indexes'];
        $foreignKeys = $tableInfo['foreign_keys'];
        $recordCount = $tableInfo['record_count'];

        $content = <<<MARKDOWN
# Tabella: {$tableName}

## Descrizione
Tabella `{$tableName}` nel database.

- **Numero di record**: {$recordCount}
- **Chiave primaria**: {$primaryKey ? implode(', ', $primaryKey['columns']) : 'Nessuna'}

## Struttura

| Colonna | Tipo | Nullable | Default | Extra | Commento |
|---------|------|----------|---------|-------|----------|

MARKDOWN;

        foreach ($columns as $columnName => $column) {
            $type = $column['type'];
            $nullable = $column['nullable'] ? 'Sì' : 'No';
            $default = $column['default'] ?? 'NULL';
            $extra = $column['extra'] ?? '';
            $comment = $column['comment'] ?? '';
            
            $content .= "| {$columnName} | {$type} | {$nullable} | {$default} | {$extra} | {$comment} |\n";
        }

        if (!empty($indexes) && count($indexes) > 0) {
            $content .= "\n## Indici\n\n";
            $content .= "| Nome | Colonne | Unico |\n";
            $content .= "|------|---------|-------|\n";

            foreach ($indexes as $indexName => $index) {
                if ($indexName === 'PRIMARY') {
                    continue;
                }
                
                $columns = implode(', ', $index['columns']);
                $unique = $index['unique'] ? 'Sì' : 'No';
                
                $content .= "| {$indexName} | {$columns} | {$unique} |\n";
            }
        }

        if (!empty($foreignKeys) && count($foreignKeys) > 0) {
            $content .= "\n## Chiavi Esterne\n\n";
            $content .= "| Nome | Colonne | Tabella Riferimento | Colonne Riferimento |\n";
            $content .= "|------|---------|---------------------|--------------------|\n";

            foreach ($foreignKeys as $foreignKeyName => $foreignKey) {
                $columns = implode(', ', $foreignKey['columns']);
                $refTable = $foreignKey['references_table'];
                $refColumns = implode(', ', $foreignKey['references_columns']);
                
                $content .= "| {$foreignKeyName} | {$columns} | {$refTable} | {$refColumns} |\n";
            }
        }

        // Relazioni
        $tableRelationships = $this->getTableRelationships($tableName, $relationships);
        
        if (!empty($tableRelationships)) {
            $content .= "\n## Relazioni\n\n";
            
            if (!empty($tableRelationships['belongs_to'])) {
                $content .= "### Belongs To\n\n";
                $content .= "| Tabella | Chiave Esterna | Chiave Riferimento |\n";
                $content .= "|---------|---------------|-------------------|\n";
                
                foreach ($tableRelationships['belongs_to'] as $relation) {
                    $targetTable = $relation['to_table'];
                    $foreignKey = implode(', ', $relation['from_columns']);
                    $targetKey = implode(', ', $relation['to_columns']);
                    
                    $content .= "| [{$targetTable}]({$targetTable}.md) | {$foreignKey} | {$targetKey} |\n";
                }
            }
            
            if (!empty($tableRelationships['has_many'])) {
                $content .= "\n### Has Many\n\n";
                $content .= "| Tabella | Chiave Esterna | Chiave Locale |\n";
                $content .= "|---------|---------------|-------------|\n";
                
                foreach ($tableRelationships['has_many'] as $relation) {
                    $targetTable = $relation['to_table'];
                    $foreignKey = implode(', ', $relation['to_columns']);
                    $localKey = implode(', ', $relation['from_columns']);
                    
                    $content .= "| [{$targetTable}]({$targetTable}.md) | {$foreignKey} | {$localKey} |\n";
                }
            }
        }

        // Dati di esempio
        if (!empty($tableInfo['sample_data'])) {
            $content .= "\n## Dati di Esempio\n\n";
            $content .= "```json\n";
            $content .= json_encode($tableInfo['sample_data'], JSON_PRETTY_PRINT);
            $content .= "\n```\n";
        }

        File::put($outputDir . '/' . $tableName . '.md', $content);
    }

    /**
     * Ottiene le relazioni per una tabella specifica.
     */
    protected function getTableRelationships(string $tableName, array $relationships): array
    {
        $tableRelationships = [
            'belongs_to' => [],
            'has_many' => [],
        ];
        
        foreach ($relationships as $relationship) {
            if ($relationship['from_table'] === $tableName && $relationship['type'] === 'belongs_to') {
                $tableRelationships['belongs_to'][] = $relationship;
            } elseif ($relationship['from_table'] === $tableName && $relationship['type'] === 'has_many') {
                $tableRelationships['has_many'][] = $relationship;
            }
        }
        
        return $tableRelationships;
    }
} 