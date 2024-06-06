<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Class;

// use Modules\Xot\Services\ArrayService;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GetFilenameByClassnameAction
{
    use QueueableAction;


    public function execute(string $class_name): string {
        $reflector = new \ReflectionClass($class_name);
        return $reflector->getFileName();
    }

}
