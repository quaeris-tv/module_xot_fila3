<?php

declare(strict_types=1);

namespace Modules\Xot\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GetTransKeyAction
{
    use QueueableAction;

    /**
     *---.
     */
    public function execute(string $class = ''): string
    {
        if ('' === $class) {
            $backtrace = debug_backtrace();
            Assert::string($class = Arr::get($backtrace, '1.class'), '['.__LINE__.']['.class_basename($this).']');
        }

        $class = Str::of($class)
            ->replace('\Filament\Resources\\', '\\')
            ->toString();

        $class = Str::of($class)
            ->replace('\Filament\Pages\\', '\\')
            ->toString();

        $arr = explode('\\', $class);
        if ('Modules' !== $arr[0]) {
            throw new \Exception('Invalid class name['.__LINE__.']['.class_basename($this).']');
        }

        Assert::string($module = Arr::get($arr, '1'), '['.__LINE__.']['.class_basename($this).']');
        $module_low = mb_strtolower($module);

        Assert::string($model = Arr::get($arr, '2'), '['.__LINE__.']['.class_basename($this).']');
        $model = Str::before($model, 'Resource');

        if (Str::endsWith($model, 'Page') && mb_strlen($model) > 5) {
            $model = Str::before($model, 'Page');
        }
        // $model_low = strtolower($model);
        $model_low = Str::of($model)->snake()->toString();
        $model_plural = Str::plural($model);

        $callable = static function ($item) use ($model, $model_plural) {
            if (Str::endsWith($item, $model)) {
                $item = Str::before($item, $model);
            }
            if (Str::endsWith($item, $model_plural)) {
                $item = Str::before($item, $model_plural);
            }

            if (Str::endsWith($item, 'RelationManager')) {
                $item = Str::before($item, 'RelationManager');
            }

            if (Str::endsWith($item, 'Managers')) {
                $item = Str::before($item, 'Managers');
            }
            if (Str::endsWith($item, 'Page') && mb_strlen($item) > 5) {
                $item = Str::before($item, 'Page');
            }

            // return Str::kebab($item);
            return Str::of($item)->snake()->toString();
        };

        $res = collect($arr)
            ->skip(3)
            ->map($callable)
            ->implode('.');

        $tmp = $module_low.'::'.$model_low;
        if ('' !== $res) {
            $tmp .= '.'.$res;
        }
        $piece = explode('.', $tmp);

        $tmp = Str::of($tmp)->replace('.pages.list.', '.')->toString();
        $tmp = Str::of($tmp)->replace('::enums.', '::')->toString();
        $tmp = Str::of($tmp)->replace('.relation.', '.')->toString();

        if (Str::endsWith($tmp, '.pages.list')) {
            $tmp = Str::before($tmp, '.pages.list');
        }
        if (Str::endsWith($tmp, '.pages.create')) {
            $tmp = Str::before($tmp, '.pages.create');
        }
        if (Str::endsWith($tmp, '.pages.edit')) {
            $tmp = Str::before($tmp, '.pages.edit');
        }
        if (Str::endsWith($tmp, '.pages.view')) {
            $tmp = Str::before($tmp, '.pages.view');
        }
        if (Str::contains($tmp, '::actions.') && count($piece) >= 3) {
            $piece = array_slice($piece, 0, -1);
            $tmp = Str::of(implode('.', $piece))->replace('::actions.', '::')->toString();
        }

        return $tmp;
    }
}
