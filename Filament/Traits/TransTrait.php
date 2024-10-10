<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Illuminate\Support\Str;
use Modules\Xot\Actions\GetTransKeyAction;

trait TransTrait
{
    /**
     * Summary of trans.
     */
    public static function trans(string $key): string
    {
        $transKey = app(GetTransKeyAction::class)->execute(static::class);
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

        $tmp = $transKey.'.'.$key;
        $res = trans($tmp);
        if (\is_string($res)) {
            return $res;
        }

        return 'fix:'.$tmp;
    }
}
