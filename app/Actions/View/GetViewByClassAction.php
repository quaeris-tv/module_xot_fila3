<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\View;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;
use Modules\Xot\Actions\Module\GetModuleNameByModelClassAction;

class GetViewByClassAction
{
    use QueueableAction;

    /**
     * "Modules\UI\Filament\Widgets\GroupWidget" => "ui::filament.widgets.group"
     */
<<<<<<< HEAD
    public function execute(string $class, string $suffix = ''): string
    {
        $module = Str::of($class)->betweenFirst('Modules\\', '\\')->toString();
        $module_low = Str::of($module)->lower()->toString();
        $after = Str::of($class)
=======
    public function execute(string $class, string $suffix=''): string
    {
        $module = Str::of($class)->betweenFirst('Modules\\', '\\')->toString();
        $module_low = Str::of($module)->lower()->toString();
        $after=Str::of($class)
>>>>>>> 8045aaff (up)
            ->after('Modules\\'.$module.'\\')
            ->explode('\\')
            ->toArray();

        $mapped = Arr::map($after, function (string $value, int $key) use ($after) {
<<<<<<< HEAD
            if ($key > 0 && isset($after[$key - 1])) {
                $singular = Str::of($after[$key - 1])->singular()->toString();
                if (Str::endsWith($value, $singular)) {
                    $value = Str::of($value)->beforeLast($singular)->toString();
=======
            if($key>0 && isset($after[$key-1])) {
                $singular = Str::of($after[$key-1])->singular()->toString();
                if(Str::endsWith($value, $singular)) {
                    $value=Str::of($value)->beforeLast($singular)->toString();
>>>>>>> 8045aaff (up)
                }
            }
            return Str::of($value)->slug()->toString();
        });

<<<<<<< HEAD
        $implode = implode('.', $mapped);
        $view = $module_low.'::'.$implode.$suffix;

        return $view;

=======
        $implode=implode('.', $mapped);
        $view=$module_low.'::'.$implode.$suffix;

        return $view;
        
>>>>>>> 8045aaff (up)
    }
}
