<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Generate;

use Spatie\QueueableAction\QueueableAction;

class FixPathAction
{
    use QueueableAction;

    public function execute(string $path): string
    {
        return str_replace(['/', '\\'], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR], $path);
    }
}
