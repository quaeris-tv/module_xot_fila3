<?php

declare(strict_types=1);

namespace Modules\Xot\View\Composers;

use Illuminate\Support\Arr;
use Nwidart\Modules\Facades\Module;
use Webmozart\Assert\Assert;

/**
 * --.
 */
abstract class XotBaseComposer
{
    /**
     * Undocumented variable.
     */
    public string $module_name = '';

    /**
     * Undocumented function.
     *
     * @param array<mixed|void> $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        $modules = Module::getOrdered();

        $module = Arr::first(
            $modules,
            static function ($module) use ($name): bool {
                $class = '\Modules\\'.$module->getName().'\View\Composers\ThemeComposer';

                return method_exists($class, $name);
            }
        );
        if (! \is_object($module)) {
            throw new \Exception('create a View\Composers\ThemeComposer.php inside a module with ['.$name.'] method');
        }

        Assert::isInstanceOf($module, \Nwidart\Modules\Module::class);
        $class = '\Modules\\'.$module->getName().'\View\Composers\ThemeComposer';
        // Parameter #1 $callback of function call_user_func_array expects callable(): mixed, array{*NEVER*, string} given.
        $app = app($class);
        $callback = [$app, $name];
        Assert::isCallable($callback);

        return \call_user_func_array($callback, $arguments);
    }

    /**
     * Undocumented function.
     */
    public function setModule(string $module_name): self
    {
        $this->module_name = $module_name;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @param array<mixed>|string|int|float|null ...$args
     *
     * @return mixed|void
     */
    public function call(string $func, $args = [])
    {
        $module = Module::find($this->module_name);
        // $module = app(\Nwidart\Modules\Module::class)->find($this->module_name);
        if (! \is_object($module)) {
            throw new \Exception('not find ['.$this->module_name.'] on Modules ['.__LINE__.']['.__FILE__.']');
        }

        $view_composer_class = 'Modules\\'.$module->getName().'\\View\Composers\\'.$module->getName().'Composer';
        if (! class_exists($view_composer_class)) {
            throw new \Exception('['.$view_composer_class.']['.__LINE__.']['.__FILE__.']');
        }

        $view_composer = app($view_composer_class);

        return $view_composer->{$func}(...$args);
        // return call_user_func_array([$view_composer, $func], $args);
    }

    /*
    togliere ogni riferimento a Theme
     * --.
    public function getMenuByName(string $name): ?Menu {
        return Menu::firstWhere('name', $name);
    }

    /*
     * --.
    public function getMenuItemsByName(string $name): Collection {
        $menu = Menu::firstWhere('name', $name);
        if (null === $menu) {
            return collect([]);
        }
        $rows = $menu->items;
        return $rows;
    }
    */
}
