<?php

/**
 * -WIP.
 */

declare(strict_types=1);

namespace Modules\Xot\Actions\Filament\Block;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Xot\Actions\File\FixPathAction;
use Spatie\QueueableAction\QueueableAction;

class GetViewBlocksOptionsByTypeAction
{
    use QueueableAction;

    /**
     * Undocumented function.
     * return number of input added.
     */
    public function execute(string $type, bool $img = false): array
    {
        $files = File::glob(base_path('Modules').'/*/Resources/views/components/blocks/'.$type.'/*.blade.php');

        $opts = Arr::mapWithKeys(
            $files,
            function ($path) use ($img, $type) {
                $path = app(FixPathAction::class)->execute($path);
                $module_low = Str::of($path)
                    ->between(DIRECTORY_SEPARATOR.'Modules'.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR)
                    ->lower()
                    ->toString();
                $info = pathinfo($path);
                $name = Str::of($info['basename'])->before('.blade.php')->toString();
                $view = $module_low.'::components.blocks.'.$type.'.'.$name;
                if ($img) {
                    $img_path = app(\Modules\Xot\Actions\File\AssetAction::class)
                        ->execute($module_low.'::img/screenshots/'.$name.'.png');

                    return [$view => $img_path];
                }

                return [$view => $name];
            }
        );

        return $opts;
    }
}
