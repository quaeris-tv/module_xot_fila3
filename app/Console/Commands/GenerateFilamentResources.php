<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;

class GenerateFilamentResources extends Command
{
    protected $signature = 'xot:generate-filament-resources {module}';

    protected $description = 'Genera le Filament Resources per ogni modello in un modulo';

    public function handle()
    {
        $moduleName = $this->argument('module');
        $module = Module::find($moduleName);

        if (! $module) {
            $this->error("Il modulo '{$moduleName}' non esiste.");

            return;
        }

        $this->info("Generazione delle Filament Resources per il modulo: {$moduleName}");

        $modelsPath = $module->getPath().'/app/Models';
        if (! File::isDirectory($modelsPath)) {
            $this->error("Nessuna cartella 'Models' trovata nel modulo {$moduleName}.");

            return;
        }

        $models = File::files($modelsPath);
        foreach ($models as $model) {
            $modelName = $model->getFilenameWithoutExtension();

            $panel = strtolower($moduleName).'::admin';
            $params = [
                'name' => $modelName,
                '--panel' => $panel,
                '--model-namespace' => "Modules\\{$moduleName}\\Models",
                '--generate' => true,
                '--factory' => true,
                '--force' => true,
            ];
            try {
                Artisan::call('make:filament-resource', $params);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->info("Resource generata per il modello: {$modelName}");
        }

        $this->info('Tutte le resources sono state generate con successo!');
    }
}
