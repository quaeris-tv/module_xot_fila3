<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

trait NavigationPageLabelTrait
{
    use NavigationLabelTrait;

    public function getModelLabel(): string
    {
        return static::trans('navigation.name');
    }

    public function getPluralModelLabel(): string
    {
        return static::trans('navigation.plural');
    }
}
