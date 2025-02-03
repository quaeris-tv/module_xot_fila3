<?php

declare(strict_types=1);

namespace Modules\Xot\App\Helpers;

use Illuminate\Support\Str;

class ResourceFormSchemaGenerator
{
    public static function generateFormSchema(string $resourceClass)
    {
        $reflection = new \ReflectionClass($resourceClass);
        $filename = $reflection->getFileName();

        // Read the file contents
        $fileContents = file_get_contents($filename);

        // Check if getFormSchema method already exists
        if (false !== strpos($fileContents, 'public function getFormSchema')) {
            return false;
        }

        // Generate a basic form schema based on the class name
        $modelName = str_replace('Resource', '', $reflection->getShortName());
        $modelVariable = Str::camel($modelName);

        $formSchemaMethod = "\n    public function getFormSchema(): array\n    {\n        return [\n";

        // Try to generate some basic form fields
        $formSchemaMethod .= "            Forms\\Components\\TextInput::make('{$modelVariable}_name')\n";
        $formSchemaMethod .= "                ->label('".Str::headline($modelName)." Name')\n";
        $formSchemaMethod .= "                ->required(),\n";

        $formSchemaMethod .= "        ];\n    }\n";

        // Insert the method before the last closing brace
        $modifiedContents = preg_replace(
            '/}(\s*)$/',
            $formSchemaMethod.'}$1',
            $fileContents
        );

        // Write back to the file
        file_put_contents($filename, $modifiedContents);

        return true;
    }

    public static function generateForAllResources()
    {
        $resourceFiles = glob('/var/www/html/base_techplanner_fila3/laravel/Modules/*/app/Filament/Resources/*Resource.php');

        $updatedResources = [];
        $skippedResources = [];

        foreach ($resourceFiles as $file) {
            // Get the full class name
            $content = file_get_contents($file);
            preg_match('/namespace\s+([\w\\\\]+);/', $content, $namespaceMatch);
            preg_match('/class\s+(\w+)\s+extends\s+XotBaseResource/', $content, $classMatch);

            if (! isset($namespaceMatch[1]) || ! isset($classMatch[1])) {
                $skippedResources[] = $file;
                continue;
            }

            $fullClassName = $namespaceMatch[1].'\\'.$classMatch[1];

            try {
                if (self::generateFormSchema($fullClassName)) {
                    $updatedResources[] = $fullClassName;
                }
            } catch (\Exception $e) {
                $skippedResources[] = $fullClassName.': '.$e->getMessage();
            }
        }

        return [
            'updated' => $updatedResources,
            'skipped' => $skippedResources,
        ];
    }
}
