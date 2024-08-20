<?php

declare(strict_types=1);
/**
 * All the parent's children.
 */

namespace Modules\Xot\Actions\View;

use Illuminate\Support\Facades\File;
<<<<<<< HEAD
use Illuminate\Support\Str;
=======
>>>>>>> 35d9347 (.)
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

=======
>>>>>>> 35d9347 (.)
            $k = $file->getBasename('.blade.php');
            $views[$k] = $k;
        }

        return $views;
    }
}
