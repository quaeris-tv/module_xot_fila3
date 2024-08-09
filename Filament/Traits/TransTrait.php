<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Modules\Xot\Actions\GetTransKeyAction;

trait TransTrait
{
    public static function trans(string $key): string
    {
        $transKey = app(GetTransKeyAction::class)->execute(static::class);
        $tmp = $transKey.'.'.$key;
        $res = trans($tmp);
        if (\is_string($res)) {
            return $res;
        }

        return 'fix:'.$tmp;
    }
}
