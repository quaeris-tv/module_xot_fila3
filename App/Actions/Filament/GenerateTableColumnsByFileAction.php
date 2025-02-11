<?php

/**
 * -WIP.
 */

declare(strict_types=1);

namespace Modules\Xot\Actions\Filament;

use Filament\Forms\Commands\Concerns\CanGenerateForms;
use Filament\Support\Commands\Concerns\CanReadModelSchemas;
use Filament\Tables\Commands\Concerns\CanGenerateTables;
use Illuminate\Support\Facades\File as LaravelFile;
use Illuminate\Support\Str;
use Modules\Xot\Actions\ModelClass\GetMethodBodyAction;
use Modules\Xot\Actions\String\GetStrBetweenStartsWithAction;
use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\Finder\SplFileInfo as File;
use Webmozart\Assert\Assert;

class GenerateTableColumnsByFileAction
{
    use CanGenerateForms;

    // use CanGenerateImporterColumns;
    use CanGenerateTables;
    use CanReadModelSchemas;
    use QueueableAction;

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function execute(File $file)
    {
        if (! $file->isFile()) {
            return;
        }
        if (! \in_array($file->getExtension(), ['php'], false)) {
            return;
        }
        $filename = $file->getPathname();
        $class_name = Str::replace(base_path('Modules/'), 'Modules/', $filename);
        Assert::string($class_name = Str::replace('/', '\\', $class_name), '['.__LINE__.']['.class_basename($this).']');
        $class_name = Str::substr($class_name, 0, -4);
        $model_name = app($class_name)->getModel();
        $model = app($model_name);
        // ------------------- TABLE -------------------
        // *
        $body = app(GetMethodBodyAction::class)->execute($class_name, 'table');
        $body1 = app(GetStrBetweenStartsWithAction::class)->execute($body, '->columns(', '(', ')');
        $body_new = '->columns(['.chr(13).$this->getResourceTableColumns($model_name).chr(13).'])';
        $body_up = Str::of($body)
            ->replace($body1, $body_new)
            ->toString();
        $content_new = Str::of($file->getContents())->replace($body, $body_up)->toString();
        LaravelFile::put($filename, $content_new);
        // -------------------- FORM ------------------------------
        $body = app(GetMethodBodyAction::class)->execute($class_name, 'form');
        $body1 = app(GetStrBetweenStartsWithAction::class)->execute($body, '->schema(', '(', ')');
        $body_new = '->schema(['.chr(13).$this->getResourceFormSchema($model_name).chr(13).'])';
        $body_up = Str::of($body)
            ->replace($body1, $body_new)
            ->toString();
        $content_new = Str::of($file->getContents())->replace($body, $body_up)->toString();
        LaravelFile::put($filename, $content_new);
        // -----------------------------------------------------

        if (in_array('anno', $model->getFillable())) {
            $body = app(GetMethodBodyAction::class)->execute($class_name, 'table');
            $body1 = app(GetStrBetweenStartsWithAction::class)->execute($body, '->filters(', '(', ')');
            $body_new = "->filters([
                    app(\Modules\Xot\Actions\Filament\Filter\GetYearFilter::class)->execute('anno',intval(date('Y')) - 3,intval(date('Y'))),
                ],layout: \Filament\Tables\Enums\FiltersLayout::AboveContent)
                ->persistFiltersInSession()";
            $body_up = Str::of($body)
                ->replace($body1, $body_new)
                ->toString();
            $content_new = Str::of($file->getContents())->replace($body, $body_up)->toString();
            LaravelFile::put($filename, $content_new);
        }
        // */
    }

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
