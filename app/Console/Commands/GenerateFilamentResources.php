<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use RuntimeException;

class GenerateFilamentResources extends Command
{
    protected $signature = 'filament:generate-resources {module : Nome del modulo}';

    protected $description = 'Genera le risorse Filament per un modulo specifico';

    public function handle(): int
    {
        /** @var string $moduleName */
        $moduleName = $this->argument('module');

        $module = Module::find($moduleName);
        if (!$module) {
            $this->error(sprintf('Modulo %s non trovato', $moduleName));
            return Command::FAILURE;
        }

        $this->info(sprintf('Generazione risorse Filament per il modulo %s', $moduleName));

        $modelPath = sprintf('Modules/%s/Models', $moduleName);
        if (!is_dir(base_path($modelPath))) {
            $this->error(sprintf('Directory dei modelli non trovata in %s', $modelPath));
            return Command::FAILURE;
        }

        $this->info(sprintf('Cercando modelli in %s', $modelPath));

        $namespace = sprintf('Modules\\%s\\Models', ucfirst($moduleName));
        $this->call('filament:make:resource', [
            '--generate' => true,
            '--simple' => true,
            'name' => $moduleName,
            '--namespace' => $namespace,
        ]);

        return Command::SUCCESS;
    }
}
