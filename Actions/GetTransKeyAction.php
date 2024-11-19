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
     * Generate a translation key based on the class name.
     */
    public function execute(string $class = ''): string
    {
        // If no class is provided, try to get it from the backtrace
        if ('' === $class) {
            $backtrace = debug_backtrace();
            Assert::isArray($backtrace);
            $class = Arr::get($backtrace, '1.class');
            Assert::string($class, '[' . __LINE__ . '][' . class_basename($this) . ']');
        }

        $arr = explode('\\', $class);

        // Handle cases where the provided class is not in the "Modules" namespace
        if ('Modules' !== $arr[0]) {
            $backtrace = array_slice(debug_backtrace(), 2);
            $res = Arr::first(
                $backtrace,
                function (array $item): bool {
                    return isset($item['object']) && 'Modules' === explode('\\', get_class($item['object']))[0];
                }
            );

            if (null === $res || ! isset($res['object'])) {
                throw new \Exception('Invalid class name[' . __LINE__ . '][' . class_basename($this) . ']');
            }

            $class = get_class($res['object']);
            $arr = explode('\\', $class);
        }

        $module = $arr[1];
        $module_low = mb_strtolower($module);
        $c = count($arr);

        $type = Str::singular($arr[$c - 2]);
        $class = $arr[$c - 1];

        // If the class name ends with the type, remove the suffix
        if (Str::endsWith($class, $type)) {
            $class = Str::beforeLast($class, $type);
        }

        $class_snake = Str::of($class)->snake()->toString();

        // Handle cases where the class starts with "list_"
        if (Str::startsWith($class_snake, 'list_')) {
            $class_snake = Str::of($class_snake)
                ->after('list_')
                ->singular()
                ->toString();
        }

        $tmp = $module_low . '::' . $class_snake;

        return $tmp;
    }
}
