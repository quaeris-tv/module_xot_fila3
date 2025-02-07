<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Modules\Xot\App\Helpers\ResourceFormSchemaGenerator;

class GenerateResourceFormSchemaCommand extends Command
{
    protected $signature = 'xot:generate-resource-form-schema';

    protected $description = 'Genera gli schemi dei form per le risorse Filament';

    public function handle(): int
    {
        $generator = new ResourceFormSchemaGenerator();
        
        try {
            $generator->generateForAllResources();
            $this->info('Schemi dei form generati con successo');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Errore durante la generazione degli schemi: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
