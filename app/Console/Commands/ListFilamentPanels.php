<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;

class ListFilamentPanels extends Command
{
    protected $signature = 'xot:list-panels';

    protected $description = 'Elenca tutti i Filament Panels registrati nei moduli';

    public function handle()
    {
        $modules = Module::all();

        if (empty($modules)) {
            $this->info('Nessun modulo trovato.');

            return;
        }

        $this->info('Filament Panels registrati:');

        foreach ($modules as $module) {
            $moduleName = $module->getName();
            $providers = $module->get('providers', []);

            foreach ($providers as $provider) {
                if (str_contains($provider, 'Filament')) {
                    $this->line("- Modulo: {$moduleName}, Provider: {$provider} Exists [".class_exists($provider).']');
                }
            }
        }
    }
}
