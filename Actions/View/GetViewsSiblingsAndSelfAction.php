<?php

declare(strict_types=1);
/**
 * All the parent's children.
 */

namespace Modules\Xot\Actions\View;

use Illuminate\Support\Facades\File;
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
            $k = $file->getBasename('.blade.php');
            $views[$k] = $k;
        }

        return $views;
    }
}
