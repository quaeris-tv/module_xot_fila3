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
        $arr = explode('\\', $class);
        if ('Modules' !== $arr[0]) {
            $backtrace = debug_backtrace();
            $backtrace = array_slice(debug_backtrace(), 2);
            $res = Arr::first($backtrace, function ($item, $i) {
                if (! isset($item['object'])) {
                    return false;
                }
                $class = get_class($item['object']);
                $arr = explode('\\', $class);

                $res = 'Modules' === $arr[0];

                return $res;
            });
            if (null == $res) {
                throw new \Exception('Invalid class name['.__LINE__.']['.class_basename($this).']');
            }
            $class = get_class($res['object']);
            $arr = explode('\\', $class);
        }

        $module = $arr[1];
        $module_low = mb_strtolower($module);
        $c = count($arr);
        $type = Str::singular($arr[$c - 2]);
        $class = $arr[$c - 1];
        if (Str::endsWith($class, $type)) {
            $class = Str::beforeLast($class, $type);
        }
        $class_snake = Str::of($class)->snake()->toString();

        if (Str::startsWith($class_snake, 'list_')) {
            $class_snake = Str::of($class_snake)
                ->after('list_')
                ->singular()
                ->toString();
        }
        $tmp = $module_low.'::'.$class_snake;

        return $tmp;
    }
}
