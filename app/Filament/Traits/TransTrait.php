<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Lang\Actions\SaveTransAction;
use Modules\Xot\Actions\GetTransKeyAction;

trait TransTrait
{
    /**
     * Get translation for a given key.
     *
     * @throws \Exception Se exceptionIfNotExist è true e la traduzione non esiste
     */
    public static function trans(string $key, bool $exceptionIfNotExist = false): string
    {
        $tmp = static::getKeyTrans($key);
        /** @var string|array<int|string,mixed>|null $res */
        $res = trans($tmp);

        if (is_string($res)) {
            if ($exceptionIfNotExist && $res === $tmp) {
                throw new \Exception('['.__LINE__.']['.class_basename(__CLASS__).']');
            }

            return $res;
        }

        if (is_array($res)) {
            $first = current($res);
            if (is_string($first) || is_numeric($first)) {
                return (string) $first;
            }
        }

        return 'fix:'.$tmp;
    }

    /**
     * Get translation key for a given key.
     */
    public static function getKeyTrans(string $key): string
    {
        /** @var string */
        $transKey = app(GetTransKeyAction::class)->execute(static::class);

        $key = $transKey.'.'.$key;
        $key = Str::of($key)->replace('.cluster.pages.', '.')->toString();

        return $key;
    }

    /**
     * Get translation key for a given function name.
     */
    public static function getKeyTransFunc(string $func): string
    {
        $key = Str::of($func)
            ->after('get')
            ->snake()
            ->replace('_', '.')
            ->toString();
        /** @var string */
        $transKey = app(GetTransKeyAction::class)->execute(static::class);

        $key = $transKey.'.'.$key;
        $key = Str::of($key)->replace('.cluster.pages.', '.')->toString();

        return $key;
    }

    /**
     * Get translation for a given function name.
     */
    public static function transFunc(string $func, bool $exceptionIfNotExist = false): string
    {
        $key = static::getKeyTransFunc($func);
        /** @var string|array<int|string,mixed>|null $trans */
        $trans = trans($key);

        if ($key == $trans) {
            $group = Str::of($key)->before('.')->toString();
            $item = Str::of($key)->after($group.'.')->toString();
            $group_arr = trans($group);
            if (is_array($group_arr)) {
                $trans = Arr::get($group_arr, $item);
            }
        }
        if (is_numeric($trans)) {
            return strval($trans);
        }

        // if (! is_string($trans) && ! is_numeric($trans) && ! is_array($trans)) {
        //    return 'fix:'.$key;
        // }
        if (is_array($trans)) {
            $first = current($trans);
            if (is_string($first) || is_numeric($first)) {
                return (string) $first;
            }
        }

        if (is_string($trans) /* || is_numeric($trans) */) {
            if ($trans === $key) {
                $newTrans = Str::of($key)
                    ->between('::', '.')
                    ->replace('_', ' ')
                    ->toString();
                app(SaveTransAction::class)->execute($key, $newTrans);

                return $newTrans;
            }

            return $trans;
        }

        if (is_null($trans)) {
            $newTrans = Str::of($key)
                ->between('::', '.')
                ->replace('_', ' ')
                ->toString();
            app(SaveTransAction::class)->execute($key, $newTrans);

            return $newTrans;
        }

        // $first = current($trans);
        // if (is_string($first) || is_numeric($first)) {
        //    return (string) $first;
        // }

        return 'fix:'.$key;
    }

    protected function transChoice(string $key, int $number, array $replace = []): string
    {
        return trans_choice($key, $number, $replace) ?? $key;
    }
}
