<?php

declare(strict_types=1);

namespace Modules\Xot\App\Helpers;

use Illuminate\Support\Facades\File;
use ReflectionClass;
use RuntimeException;
use Safe\Exceptions\FilesystemException;
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\glob;
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\error_log;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

class ResourceFormSchemaGenerator
{
    /**
     * @throws FilesystemException
     * @throws RuntimeException
     */
    public function generateForResource(string $resourceClass): void
    {
        if (!class_exists($resourceClass)) {
            throw new RuntimeException("Class {$resourceClass} not found");
        }

        /** @var class-string $resourceClass */
        $reflection = new ReflectionClass($resourceClass);
        $namespace = $reflection->getNamespaceName();
        $className = $reflection->getShortName();

        $schemaPath = $this->getSchemaPath($namespace, $className);
        if ($schemaPath === null) {
            throw new RuntimeException("Cannot determine schema path for {$resourceClass}");
        }

        $content = file_get_contents($schemaPath);
        if (!str_contains($content, 'form:')) {
            throw new RuntimeException("Invalid schema file format in {$schemaPath}");
        }

        file_put_contents($schemaPath, $content);
    }

    /**
     * @throws RuntimeException
     */
    public function generateForAllResources(): void
    {
        $resourcesPath = base_path('Modules/*/app/Filament/Resources');
        
        $resourceFiles = glob($resourcesPath . '/*.php');
        if ($resourceFiles == false) {
            throw new RuntimeException('No resource files found');
        }

        foreach ($resourceFiles as $file) {
            $namespace = $this->getNamespaceFromFile($file);
            if ($namespace === null) {
                continue;
            }

            $className = basename($file, '.php');
            /** @var class-string */
            $resourceClass = $namespace . '\\' . $className;

            try {
                $this->generateForResource($resourceClass);
            } catch (\Exception $e) {
                error_log("Error generating form schema for {$resourceClass}: " . $e->getMessage());
            }
        }
    }

    private function getSchemaPath(string $namespace, string $className): ?string
    {
        $parts = explode('\\', $namespace);
        if (count($parts) < 2) {
            return null;
        }

        return base_path("Modules/{$parts[1]}/Resources/schemas/" . strtolower($className) . '.yaml');
    }

    private function getNamespaceFromFile(string $file): ?string
    {
        try {
            $content = file_get_contents($file);
            /** @var array{0: string, 1: string}|null $matches */
            $matches = [];
            
            if (!preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
                return null;
            }
            Assert::isArray($matches);
            return $matches[1];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * --
     */
    public static function generateFormSchema(string $resourceClass): bool
    {
        try {
            /** @var class-string $resourceClass */
            $reflection = new ReflectionClass($resourceClass);
            $filename = $reflection->getFileName();
            Assert::string($filename);

            $fileContents = file_get_contents($filename);

            if (str_contains($fileContents, 'public function getFormSchema')) {
                return false;
            }

            $modelName = str_replace('Resource', '', $reflection->getShortName());
            $modelVariable = Str::camel($modelName);

            $formSchemaMethod = "\n    public function getFormSchema(): array\n    {\n        return [\n";
            $formSchemaMethod .= "            Forms\\Components\\TextInput::make('{$modelVariable}_name')\n";
            $formSchemaMethod .= "                ->label('".Str::headline($modelName)." Name')\n";
            $formSchemaMethod .= "                ->required(),\n";
            $formSchemaMethod .= "        ];\n    }\n";

            $isInClustersDir = str_contains($filename, 'Clusters');

            $modifiedContents = preg_replace(
                '/}(\s*)$/',
                $formSchemaMethod.($isInClustersDir ? '' : '}$1'),
                $fileContents
            );
            Assert::string($modifiedContents);

            file_put_contents($filename, $modifiedContents);

            return true;
        } catch (\Exception $e) {
            error_log("Error generating form schema for {$resourceClass}: ".$e->getMessage());
            return false;
        }
    }
}
