<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\XotBaseResource\RelationManager;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Xot\Filament\Traits\HasXotTable;
use Webmozart\Assert\Assert;

/**
 * @property class-string<XotBaseResource> $resource
 */
abstract class XotBaseRelationManager extends RelationManager
{
    use HasXotTable;

    protected static string $relationship = '';

    //@var class-string<XotBaseResource> 
    //protected static string $resource;

    public static function getModuleName(): string
    {
        return Str::between(static::class, 'Modules\\', '\Filament');
    }

    public static function getNavigationLabel(): string
    {
        return static::transFunc(__FUNCTION__);
    }

    public static function getNavigationGroup(): string
    {
        return static::transFunc(__FUNCTION__);
    }

    protected static function getPluralModelLabel(): string
    {
        return static::transFunc(__FUNCTION__);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema());
    }

    /**
     * Get form schema.
     *
     * @return array<string, \Filament\Forms\Components\Component>
     */
    public function getFormSchema(): array
    {
        return $this->getResource()::getFormSchema();
    }

    public function getListTableColumns(): array
    {
        $index = Arr::get($this->getResource()::getPages(), 'index');
        $index_page = $index->getPage();
        $columns = app($index_page)->getListTableColumns();
        
        return $columns;
    }

    // public function table(Table $table): Table
    // {

    //     /** @var class-string<Model> $resource */
    //     $resource = $this->getResource();
    //     Assert::classExists($resource);

    //     if (method_exists($resource, 'getListTableColumns')) {
    //         /** @var array<string, Tables\Columns\Column> $columns */
    //         $columns = $resource::getListTableColumns();

    //         return $table->columns($columns);
    //     }

    //     return $table->columns($this->getTableColumns());
    // }

    // /**
    //  * Get table columns.
    //  *
    //  * @return array<string, Tables\Columns\Column>
    //  */
    // protected function getTableColumns(): array
    // {
    //     return [];
    // }

    /**
     * Get the resource class.
     *
     * @return class-string<XotBaseResource>
     */
    protected function getResource(): string
    {
        try {
            /** @var class-string<XotBaseResource> $resourceClass */
            $resourceClass = $this->resource;
            Assert::isInstanceOf($resourceClass, XotBaseResource::class);

            return $resourceClass;
        } catch (\Exception $e) {
            $class = $this::class;
            $resource_name = Str::of(class_basename($this))
                ->beforeLast('RelationManager')
                ->singular()
                ->append('Resource')
                ->toString();
            $ns = Str::of($class)
                ->before('Resources\\')
                ->append('Resources\\')
                ->toString();
            Assert::classExists($resource_class = $ns.'\\'.$resource_name);
            Assert::isInstanceOf($resource_class, XotBaseResource::class);

            return $resource_class;
        }
    }
}
