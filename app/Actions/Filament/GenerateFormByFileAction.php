<?php

/**
 * -WIP.
 */

declare(strict_types=1);

namespace Modules\Xot\Actions\Filament;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use function Safe\file;

use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\Finder\SplFileInfo as File;
use Webmozart\Assert\Assert;

class GenerateFormByFileAction
{
    use QueueableAction;

    /**
     * Genera un form Filament basato su un file di risorsa.
     *
     * @param File $file Il file della risorsa Filament
     * 
     * @return int Numero di input aggiunti
     */
    public function execute(File $file): int
    {
        if (! $file->isFile()) {
            return 0;
        }
        if (! \in_array($file->getExtension(), ['php'], false)) {
            return 0;
        }

        $class_name = Str::replace(base_path('Modules/'), 'Modules/', $file->getPathname());
        Assert::string($class_name = Str::replace('/', '\\', $class_name), '['.__LINE__.']['.class_basename($this).']');
        $class_name = Str::substr($class_name, 0, -4);
        
        // Verifichiamo che la classe esista e sia una risorsa Filament
        Assert::classExists($class_name);
        
        /** @var Resource $resourceInstance */
        $resourceInstance = app($class_name);
        
        // Verifichiamo che il metodo getModel esista
        if (!method_exists($resourceInstance, 'getModel')) {
            return 0;
        }
        
        /** @var string $modelClass */
        $modelClass = $resourceInstance->getModel();
        
        // Verifichiamo che la classe del modello esista
        Assert::classExists($modelClass);
        
        /** @var Model $modelInstance */
        $modelInstance = app($modelClass);
        
        // Verifichiamo che il metodo getFillable esista
        if (!method_exists($modelInstance, 'getFillable')) {
            return 0;
        }
        
        $fillable = $modelInstance->getFillable();
        
        $reflection_class = new \ReflectionClass($class_name);
        
        // Verifichiamo che il metodo form esista
        if (!$reflection_class->hasMethod('form')) {
            return 0;
        }
        
        $form_method = $reflection_class->getMethod('form');
        $start_line = $form_method->getStartLine() - 1;
        // it's actually - 1, otherwise you wont get the function() block
        $end_line = $form_method->getEndLine();
        $length = $end_line - $start_line;
        Assert::string($file_name = $form_method->getFileName(), '['.__LINE__.']['.class_basename($this).']');
        // $contents= $file->getContents();
        $source = file($file_name);
        $body = implode('', \array_slice($source, $start_line, $length));
        
        // Otteniamo i metodi della classe risorsa
        $resourceMethods = get_class_methods($resourceInstance);
        
        dd([
            'class_name' => $class_name,
            'model_name' => $modelClass,
            'fillable' => $fillable,
            // 't1'=>app($class_name)->form(app(\Filament\Forms\Form::class)),
            'methods' => $resourceMethods,
            'form_method' => $form_method,
            'form_method_methods' => get_class_methods($form_method),
            'body' => $body,
        ]);
    }

    /**
     * Mostra informazioni di debug su un file.
     *
     * @param File $file Il file da analizzare
     * 
     * @return void
     */
    public function ddFile(File $file): void
    {
        dd([
            'getRelativePath' => $file->getRelativePath(), // =  ""
            'getRelativePathname' => $file->getRelativePathname(), //  AssenzeResource.php
            'getFilenameWithoutExtension' => $file->getFilenameWithoutExtension(), // AssenzeResource
            // 'getContents' => $file->getContents(),
            'getPath' => $file->getPath(), // = /var/www/html/ptvx/laravel/Modules/Progressioni/Filament/Resources
            'getFilename' => $file->getFilename(), // = AssenzeResource.php
            'getExtension' => $file->getExtension(), // php
            'getBasename' => $file->getBasename(), // AssenzeResource.php
            'getPathname' => $file->getPathname(), // "/var/www/html/ptvx/laravel/Modules/Progressioni/Filament/resources/AssenzeResource.php
            'isFile' => $file->isFile(), // true
            'getRealPath' => $file->getRealPath(), // /var/www/html/ptvx/laravel/Modules/Progressioni/Filament/resources/AssenzeResource.php
            // 'getFileInfo' => $file->getFileInfo(),
            // 'getPathInfo' => $file->getPathInfo(),
            'methods' => get_class_methods($file),
        ]);
    }
}
