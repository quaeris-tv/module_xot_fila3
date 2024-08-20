<?php

declare(strict_types=1);
/**
 * All the parent's children.
 */

namespace Modules\Xot\Actions\View;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
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
=======

            if(!Str::endsWith($file->getFilename(), '.blade.php')) {
>>>>>>> 16a3369 (✨ (GetViewsSiblingsAndSelfAction.php): Add support for filtering out non-blade.php files when fetching views to improve efficiency and accuracy)
                continue;
            }

            $k = $file->getBasename('.blade.php');
            $views[$k] = $k;

        }

        return $views;
    }
}
