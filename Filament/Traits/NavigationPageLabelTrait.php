<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

trait NavigationPageLabelTrait
{
<<<<<<< HEAD
    use TransTrait;
=======
    use NavigationLabelTrait;
>>>>>>> 35d9347 (.)

    public function getModelLabel(): string
    {
        return static::trans('navigation.name');
    }

    public function getPluralModelLabel(): string
    {
        return static::trans('navigation.plural');
    }
<<<<<<< HEAD

    public static function getNavigationLabel(): string
    {
        return static::trans('navigation.plural');
    }

    public static function getNavigationGroup(): string
    {
        return static::trans('navigation.group.name');
    }

    public static function getPluralLabel(): string
    {
        return static::trans('navigation.plural');
    }

    public static function getLabel(): string
    {
        return static::trans('navigation.name');
    }

    public function getTitle(): string
    {
        return static::trans('navigation.name');
    }
=======
>>>>>>> 35d9347 (.)
}
