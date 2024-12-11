<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources;

use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Support\Str;
use Modules\Xot\Actions\ModelClass\CountAction;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;

abstract class XotBaseResource extends FilamentResource
{
    use NavigationLabelTrait;

    protected static ?string $model = null;

    // protected static ?string $navigationIcon = 'heroicon-o-bell';
    // protected static ?string $navigationLabel = 'Custom Navigation Label';
    // protected static ?string $activeNavigationIcon = 'heroicon-s-document-text';
    // protected static bool $shouldRegisterNavigation = false;
    // protected static ?string $navigationGroup = 'Parametri di Sistema';
    protected static ?int $navigationSort = 3;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getModuleName(): string
    {
        return Str::between(static::class, 'Modules\\', '\Filament');
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
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

    public static function getNavigationBadge(): ?string
    {
        try {
            /*
            //return (string) static::getModel()::count();
            $model = app(static::getModel());
            $table = $model->getTable();
            $db = $model->getConnection()->getDatabaseName();
            if ($db == ':memory:') {
                return number_format($model->count(), 0);
            }
            $count = DB::table('information_schema.TABLES')
            ->where('TABLE_SCHEMA', $databaseName)
            ->where('TABLE_NAME', $tableName)
            ->value('TABLE_ROWS');
            return $db;
            */
            $count = app(CountAction::class)->execute(static::getModel());

            return number_format($count, 0);
        } catch (\Exception $e) {
            return '---';
        }
    }
}
