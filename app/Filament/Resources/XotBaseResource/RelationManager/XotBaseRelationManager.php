<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\XotBaseResource\RelationManager;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Xot\Filament\Traits\HasXotTable;

/**
 * @property static string $resource
 */
abstract class XotBaseRelationManager extends RelationManager
{
    use HasXotTable;

    // protected static string $relationship = 'roles';
    // protected static ?string $recordTitleAttribute = 'name';
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
        $resource = static::$resource;

        return $resource::form($form);
    }

    public function getListTableColumns(): array
    {
        $resource = static::$resource;
        $index = Arr::get($resource::getPages(), 'index');
        $index_page = $index->getPage();
        $res = app($index_page)->getListTableColumns();

        return $res;
    }
}
