<?php

declare(strict_types=1);

namespace Modules\Xot\Actions;

use Illuminate\Support\Str;
use Modules\Xot\Services\FileService;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GetViewAction
{
    use QueueableAction;

    public function execute(string $tpl = '', string $file0 = ''): string
    {
<<<<<<< HEAD
        if ($file0 === '') {
=======
        if ('' === $file0) {
>>>>>>> 35d9347 (.)
            $backtrace = debug_backtrace();
            $file0 = FileService::fixpath($backtrace[0]['file'] ?? '');
        }

        $file0 = Str::after($file0, base_path());
        $arr = explode(DIRECTORY_SEPARATOR, $file0);

<<<<<<< HEAD
        if ($arr[0] === '') {
=======
        if ('' === $arr[0]) {
>>>>>>> 35d9347 (.)
            $arr = array_slice($arr, 1);
            $arr = array_values($arr);
        }

        $mod = $arr[1];
        $tmp = array_slice($arr, 3);

        $tmp = collect($tmp)->map(
            static function ($item) {
                $item = str_replace('.php', '', $item);

                return Str::slug(Str::snake($item));
            }
        )->implode('.');

        $pub_view = 'pub_theme::'.$tmp;
        Assert::string($pub_view, '['.__LINE__.']['.__FILE__.']');

<<<<<<< HEAD
        if ($tpl !== '') {
=======
        if ('' !== $tpl) {
>>>>>>> 35d9347 (.)
            $pub_view .= '.'.$tpl;
        }
        if (view()->exists($pub_view)) {
            return $pub_view;
        }

        $view = Str::lower($mod).'::'.$tmp;

<<<<<<< HEAD
        if ($tpl !== '') {
=======
        if ('' !== $tpl) {
>>>>>>> 35d9347 (.)
            $view .= '.'.$tpl;
        }

        // if (inAdmin()) {
        if (Str::contains($view, '::panels.actions.')) {
            $to = '::'.(inAdmin() ? 'admin.' : '').'home.acts.';
            $view = Str::replace('::panels.actions.', $to, $view);
            $view = Str::replace('-action', '', $view);
        }

        // }
        Assert::string($view, '['.__LINE__.']['.__FILE__.']');
        if (! view()->exists($view)) {
            throw new \Exception('View ['.$view.'] not found');
        }

        return $view;
    }
}
