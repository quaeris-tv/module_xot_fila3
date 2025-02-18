<?php

declare(strict_types=1);

namespace Modules\Xot\Console\Commands;

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

        // Additional handling for Clusters resources
        $clustersResources = glob('/var/www/html/base_techplanner_fila3/laravel/Modules/*/app/Filament/Clusters/*/Resources/*Resource.php');
        $clustersUpdated = 0;
        $clustersSkipped = 0;

        foreach ($clustersResources as $file) {
            try {
                // Get the full class name
                $content = file_get_contents($file);
                preg_match('/namespace\s+([\w\\\\]+);/', $content, $namespaceMatch);
                preg_match('/class\s+(\w+)\s+extends\s+XotBaseResource/', $content, $classMatch);

                if (isset($namespaceMatch[1]) && isset($classMatch[1])) {
                    $fullClassName = $namespaceMatch[1].'\\'.$classMatch[1];

                    // Modify the file to add getFormSchema method
                    $modifiedContent = preg_replace(
                        '/}(\s*)$/',
                        "\n    public function getFormSchema(): array {\n        return [\n            // Basic form schema\n            Forms\\Components\\TextInput::make('name')->required(),\n        ];\n    }\n}$1",
                        $content
                    );

                    file_put_contents($file, $modifiedContent);
                    $this->info("Updated Clusters Resource: {$fullClassName}");
                    ++$clustersUpdated;
                } else {
                    $this->warn("Could not process Clusters Resource: {$file}");
                    ++$clustersSkipped;
                }
            } catch (\Exception $e) {
                $this->error("Error processing Clusters Resource {$file}: ".$e->getMessage());
                ++$clustersSkipped;
            }
        }

        $this->info("Clusters Resources Updated: {$clustersUpdated}");
        $this->warn("Clusters Resources Skipped: {$clustersSkipped}");

        return 0;
    }
}
