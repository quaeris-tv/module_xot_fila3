<?php

declare(strict_types=1);

namespace Modules\Xot\App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Xot\App\Helpers\ResourceFormSchemaGenerator;

class GenerateResourceFormSchemaCommand extends Command
{
    protected $signature = 'xot:generate-resource-form-schema';
    protected $description = 'Generate getFormSchema method for XotBaseResource classes';

    public function handle()
    {
        $result = ResourceFormSchemaGenerator::generateForAllResources();

        $this->info('Resource Form Schema Generation Report:');
        $this->info('Updated Resources: '.count($result['updated']));

        if (! empty($result['updated'])) {
            $this->table(['Updated Resources'],
                array_map(fn ($resource) => [$resource], $result['updated'])
            );
        }

        if (! empty($result['skipped'])) {
            $this->warn('Skipped Resources: '.count($result['skipped']));
            $this->table(['Skipped Resources'],
                array_map(fn ($resource) => [$resource], $result['skipped'])
            );
        }

        return 0;
    }
}
