<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources;

use Filament\Resources\Resource;
use Illuminate\Support\Str;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;

abstract class XotBaseResource extends Resource
{
    use NavigationLabelTrait;

    protected static ?string $model = null;

    // protected static ?string $navigationIcon = 'heroicon-o-bell';
    // protected static ?string $navigationLabel = 'Custom Navigation Label';
    // protected static ?string $activeNavigationIcon = 'heroicon-s-document-text';
    // protected static bool $shouldRegisterNavigation = false;
    // protected static ?string $navigationGroup = 'Parametri di Sistema';
    protected static ?int $navigationSort = 3;

    public static function getModuleName(): string
    {
        return Str::between(static::class, 'Modules\\', '\Filament');
    }

    public static function getModel(): string
    {
        // if (null != static::$model) {
        //    return static::$model;
        // }
        $moduleName = static::getModuleName();
        $modelName = Str::before(class_basename(static::class), 'Resource');
        $res = 'Modules\\'.$moduleName.'\Models\\'.$modelName;
        static::$model = $res;

        return $res;
    }

    public static function extendTableCallback(): array
    {
        return [
        ];
    }

    public static function extendFormCallback(): array
    {
        return [
        ];
    }
}
