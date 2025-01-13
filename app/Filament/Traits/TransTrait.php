<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Illuminate\Support\Str;
use Modules\Lang\Actions\SaveTransAction;
use Modules\Xot\Actions\GetTransKeyAction;

trait TransTrait
{
    /**
     * Summary of trans.
     */
    public static function trans(string $key, bool $exceptionIfNotExist = false): string
    {
        $transKey = app(GetTransKeyAction::class)->execute(static::class);

        /*
        $ns = Str::before($transKey, '::');
        $group = Str::after($transKey, '::');
        $group_arr = explode('.', $group);
        if (Str::contains($transKey, '::filament.')) {
            $type = Str::singular($group_arr[1]);
            if (isset($group_arr[2])) {
                if (Str::endsWith($group_arr[2], '_'.$type)) {
                    $group_arr[2] = Str::beforeLast($group_arr[2], '_'.$type);
                }
            }
            $group_arr = array_slice($group_arr, 2);
            $group = implode('.', $group_arr);
            $transKey = $ns.'::'.$group;
        }
        */
        $tmp = $transKey.'.'.$key;
        $res = trans($tmp);
        if (\is_string($res)) {
            if ($exceptionIfNotExist && $res === $tmp) {
                throw new \Exception('['.__LINE__.']['.class_basename(__CLASS__).']');
            }

            return $res;
        }
        if (is_array($res, false)) {
            $tmp = current($res);
            if (is_string($tmp)) {
                return $tmp;
            }
        }

        return 'fix:'.$tmp;
    }

    public static function transFunc(string $func, bool $exceptionIfNotExist = false): string
    {
        $key = Str::of($func)
            ->after('get')
            ->snake()
            ->replace('_', '.')
            ->toString();
        $trans = static::trans($key, $exceptionIfNotExist);
        $transKey = app(GetTransKeyAction::class)->execute(static::class);
        $key = $transKey.'.'.$key;
        if ($trans == $key) {
            $trans = Str::of($key)
                ->between('::', '.')
                ->replace('_', ' ')
                ->toString();
            app(SaveTransAction::class)->execute($key, $trans);
        }

        return $trans;
    }
}
