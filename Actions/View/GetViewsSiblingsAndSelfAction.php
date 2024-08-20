<?php

declare(strict_types=1);
/**
 * All the parent's children.
 */

namespace Modules\Xot\Actions\View;

<<<<<<< HEAD
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
=======
use Illuminate\Support\Facades\File;
>>>>>>> 9af88cd (.)
use Spatie\QueueableAction\QueueableAction;

class GetViewsSiblingsAndSelfAction
{
    use QueueableAction;

    /**
     * ---.
     */
    public function execute(string $view): array
    {
        $path = app(GetViewPathAction::class)->execute($view);

        $dir = \dirname($path);
        $files = File::files($dir);
        $views = [];
        foreach ($files as $file) {
<<<<<<< HEAD
            if (! Str::endsWith($file->getFilename(), '.blade.php')) {
                continue;
            }

            $k = $file->getBasename('.blade.php');
            $views[$k] = $k;

=======
            $k = $file->getBasename('.blade.php');
            $views[$k] = $k;
>>>>>>> 9af88cd (.)
        }

        return $views;
    }
}
