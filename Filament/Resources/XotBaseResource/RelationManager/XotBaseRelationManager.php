<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\XotBaseResource\RelationManager;

use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\Str;
use Modules\Xot\Filament\Traits\HasXotTable;

abstract class XotBaseRelationManager extends RelationManager
{
    use HasXotTable;
    // protected static string $relationship = 'roles';
    // protected static ?string $recordTitleAttribute = 'name';

    public static function getModuleName(): string
    {
        return Str::between(static::class, 'Modules\\', '\Filament');
    }

    public static function trans(string $key): string
    {
        $moduleNameLow = Str::lower(static::getModuleName());
        // Assert::notNull(static::$model,'['.__LINE__.']['.class_basename($this).']');
        $p = Str::after(static::class, 'Filament\Resources\\');
        $p_arr = explode('\\', $p);
        /*
        dddx([
            'methods' => static::class,
            'p' => $p,
            'p_a' => $p_arr,
        ]);
        */
        // RelationManager
        $slug = Str::kebab(Str::before($p_arr[0], 'Resource'));
        $slug .= '.'.Str::kebab(Str::before($p_arr[2], 'RelationManager'));

        // $modelNameSlug = Str::kebab(class_basename(static::class));
        $res = $moduleNameLow.'::'.$slug.'.'.$key;

        return __($res);
    }

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
}
