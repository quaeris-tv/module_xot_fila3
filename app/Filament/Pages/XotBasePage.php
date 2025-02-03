<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Str;
use Modules\Xot\Filament\Traits\TransTrait;

/**
 * Undocumented class.
 *
 * @property ?string $model
 */
abstract class XotBasePage extends Page
{
    use TransTrait;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static string $view = 'job::filament.pages.job-monitor';

    protected static ?string $model = null; // ---

    // public function mount(): void {
    //     $user = auth()->user();
    //     if(!$user->hasRole('super-admin')){
    //         redirect('/admin');
    //     }
    // }
    public static function getModuleName(): string
    {
        return Str::between(static::class, 'Modules\\', '\Filament');
    }

    public static function trans(string $key): string
    {
        $moduleNameLow = Str::lower(static::getModuleName());

        $p = Str::after(static::class, 'Filament\Pages\\');
        $p_arr = explode('\\', $p);
        /*
        dddx([
            'methods' => static::class,
            'p' => $p,
            'p_a' => $p_arr,
        ]);
        // */
        // RelationManager
        // $slug = Str::kebab(Str::before($p_arr[0], 'Resource'));
        // $slug .= '.'.Str::kebab(Str::before($p_arr[2], 'RelationManager'));

        // $modelNameSlug = Str::kebab(class_basename(static::class));

        $slug = collect($p_arr)->map(static fn ($item) => Str::kebab($item))->implode('.');
        $res = $moduleNameLow.'::'.$slug.'.'.$key;

        return __($res);
    }

    public static function getPluralModelLabel(): string
    {
        return static::transFunc(__FUNCTION__);
    }

    public static function getNavigationLabel(): string
    {
        return static::transFunc(__FUNCTION__);
        // return static::trans('navigation.plural');
    }

    public static function getNavigationGroup(): string
    {
        return static::transFunc(__FUNCTION__);
    }

    public function getModel(): string
    {
        // if (null != static::$model) {
        //    return static::$model;
        // }
        $moduleName = static::getModuleName();
        $modelName = Str::before(class_basename(static::class), 'Resource');
        $res = 'Modules\\'.$moduleName.'\Models\\'.$modelName;
        $this->model = $res;
        // self::$model = $res;

        return $res;
    }
}
