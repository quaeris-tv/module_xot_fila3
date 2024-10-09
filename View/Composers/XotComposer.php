<?php

declare(strict_types=1);

namespace Modules\Xot\View\Composers;

use function call_user_func_array;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Xot\Datas\MetatagData;
use Modules\Xot\Datas\XotData;
use Nwidart\Modules\Facades\Module;
use Webmozart\Assert\Assert;

/**
 * Class XotComposer.
 */
class XotComposer
{
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

        Assert::isInstanceOf($module, \Nwidart\Modules\Module::class, '['.__LINE__.']['.class_basename($this).']');
        $class = '\Modules\\'.$module->getName().'\View\Composers\ThemeComposer';
        // Parameter #1 $callback of function call_user_func_array expects callable(): mixed, array{*NEVER*, string} given.
        $app = app($class);
        $callback = [$app, $name];
        Assert::isCallable($callback);

        return \call_user_func_array($callback, $arguments);
    }

    /**
     * Bind data to the view..
     */
    public function compose(View $view): void
    {
        $lang = app()->getLocale();
        $view->with('lang', $lang);
        $view->with('_theme', $this);
        if (Auth::check()) {
            $profile = XotData::make()->getProfileModel();
            $view->with('_profile', $profile);
            $view->with('_user', auth()->user());
        }
    }

    public function asset(string $str): string
    {
        return asset(app(\Modules\Xot\Actions\File\AssetAction::class)->execute($str));
    }

    public function metatag(string $str): string|bool
    {
        $metatag = MetatagData::make();
        $fun = 'get'.Str::studly($str);
        if (method_exists($metatag, $fun)) {
            return $metatag->{$fun}();
            // $callback = [$metatag,$fun];
            // Assert::isCallable($callback);
            // return call_user_func_array($callback, []);
        }

        return $metatag->{$str};
    }
}
