<?php

/**
 * @see https://dev.to/kodeas/executing-shell-commands-in-laravel-1098
 */

declare(strict_types=1);

namespace Modules\Xot\Actions;

use function Safe\preg_match_all;

use Spatie\QueueableAction\QueueableAction;

class ParsePrintPageStringAction
{
    use QueueableAction;

    public static function execute(string $str): array
    {
        // $pattern = '(\d+)(?:(?:-)(\d+))?(?:,(?!$))?';
        $pattern = '/(\d+)(?:(?:-)(\d+))?(?:,(?!$))?/';
        preg_match_all($pattern, $str, $matches);
        if (null === $matches) {
            return [];
        }
        $n = count($matches[0]);
        $res = [];
        for ($i = 0; $i < $n; ++$i) {
            if ('' === $matches[2][$i]) {
                $res[] = (int) $matches[1][$i];
            } else {
                $res = array_merge($res, self::fromTo((int) $matches[1][$i], (int) $matches[2][$i]));
            }
        }

        return $res;
    }

    public static function fromTo(int $from, int $to): array
    {
        $res = [];
        for ($i = $from; $i <= $to; ++$i) {
            $res[] = $i;
        }

        return $res;
    }
}

/*
1-4,6,7,8,11-14

 => "1"
      1 => "6"
      2 => "7"
      3 => "8"
      4 => "11"
    ]
    2 => array:5 [
      0 => "4"
      1 => ""
      2 => ""
      3 => ""
      4 => "14"

*/
