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
use Modules\Xot\Filament\Traits\HasXotTable;
use Webmozart\Assert\Assert;

/**
 * @property class-string<Model> $resource
 */
abstract class XotBaseRelationManager extends RelationManager
{
    use HasXotTable;

    protected static string $relationship = '';
    protected static string $resource;

    public static function getModuleName(): string
    {
        return Str::between(static::class, 'Modules\\', '\Filament');
    }

    /*
    public static function trans(string $key): string
    {
        $moduleNameLow = Str::lower(static::getModuleName());
        // Assert::notNull(static::$model,'['.__LINE__.']['.class_basename($this).']');
        $p = Str::after(static::class, 'Filament\Resources\\');
        $p_arr = explode('\\', $p);
        // RelationManager
        $slug = Str::kebab(Str::before($p_arr[0], 'Resource'));
        $slug .= '.'.Str::kebab(Str::before($p_arr[2], 'RelationManager'));

        $res = $moduleNameLow.'::'.$slug.'.'.$key;

        return __($res);
    }
    */
    public static function getNavigationLabel(): string
    {
        return static::trans('navigation.name');
        // return static::trans('navigation.plural');
    }

    public static function getNavigationGroup(): string
    {
        return static::trans('navigation.group.name');
    }

    protected static function getPluralModelLabel(): string
    {
        return static::trans('navigation.plural');
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
    protected function getFormSchema(): array
    {
        return [];
    }

    public function table(Table $table): Table
    {
        /** @var class-string<Model> $resource */
        $resource = $this->getResource();
        Assert::classExists($resource);

        if (method_exists($resource, 'getListTableColumns')) {
            /** @var array<string, Tables\Columns\Column> $columns */
            $columns = $resource::getListTableColumns();

            return $table->columns($columns);
        }

        return $table->columns($this->getTableColumns());
    }

    /**
     * Get table columns.
     *
     * @return array<string, Tables\Columns\Column>
     */
    protected function getTableColumns(): array
    {
        return [];
    }

    /**
     * Get the resource class.
     *
     * @return class-string<Model>
     */
    protected function getResource(): string
    {
        /* @var class-string<Model> */
        return $this->resource;
    }
}
