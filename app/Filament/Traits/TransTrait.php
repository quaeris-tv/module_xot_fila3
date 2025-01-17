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
        $tmp=static::getKeyTrans($key);
        $res = trans($tmp);
        if (\is_string($res)) {
            if ($exceptionIfNotExist && $res === $tmp) {
                throw new \Exception('['.__LINE__.']['.class_basename(__CLASS__).']');
            }

            return $res;
        }
        if (is_array($res)) {
            $tmp = current($res);
            if (is_string($tmp)) {
                return $tmp;
            }
        }

        return 'fix:'.$tmp;
    }

    public static function getKeyTrans(string $key): string
    {
        $transKey = app(GetTransKeyAction::class)->execute(static::class);
        $key = $transKey.'.'.$key;
        return $key;
    }

    public static function getKeyTransFunc(string $func): string
    {
        $key = Str::of($func)
            ->after('get')
            ->snake()
            ->replace('_', '.')
            ->toString();
        $transKey = app(GetTransKeyAction::class)->execute(static::class);
        $key = $transKey.'.'.$key;
        return $key;
    }

    public static function transFunc(string $func, bool $exceptionIfNotExist = false): string
    {

       
        $key = static::getKeyTransFunc($func);
        $trans = trans($key);
        if ($trans == $key) {
            $trans = Str::of($key)
                ->between('::', '.')
                ->replace('_', ' ')
                ->toString();
            app(SaveTransAction::class)->execute($key, $trans);
        }
        if(is_array($trans)) {
            $trans=current($trans);
        }
        
        if(!is_string($trans)) {
            dddx([
                'key' => $key,
                'trans' => $trans,
            ]);
        }
            
        
        return $trans;
    }
}
