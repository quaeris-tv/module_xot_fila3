<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Webmozart\Assert\Assert;

trait NavigationLabelTrait
{
    use TransTrait;

    public static function getModelLabel(): string
    {
        return static::trans('navigation.name');
    }

    public static function getPluralModelLabel(): string
    {
        return static::trans('navigation.plural');
    }

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
    /*

    public function getHeading(): string|Htmlable
    {
        return 'AAAAAAAAAA';
    }

    public static function getNavigationSort(): ?int {
        return JobsWaitingPlugin::make()->getNavigationSort();
    }

    public static function getBreadcrumb(): string {
        return JobsWaitingPlugin::make()->getBreadcrumb();
    }

    public static function shouldRegisterNavigation(): bool {
        return JobsWaitingPlugin::make()->shouldRegisterNavigation();
    }

    public static function getNavigationIcon(): string {
        return JobsWaitingPlugin::make()->getNavigationIcon();
    }

    */
}

/*
public static function transPath(string $key): string
    {
        $moduleNameLow = Str::lower(static::getModuleName());
        // $modelClass = static::$model ?? static::getModel();
        $modelClass = static::getModel();
        Assert::notNull($modelClass,'['.__LINE__.']['.class_basename($this).']');
        $modelNameSlug = Str::kebab(class_basename($modelClass));

        return $moduleNameLow.'::'.$modelNameSlug.'.'.$key;
    }

    public static function trans(string $key): string
    {
        $res = __(static::transPath($key));
        if (\is_array($res)) {
            throw new \Exception('fix lang ['.$key.']');
        }

        return $res;
    }
*/
