<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Class;

// use Modules\Xot\Services\ArrayService;

use Spatie\QueueableAction\QueueableAction;

class GetFilenameByClassnameAction
{
    use QueueableAction;

    /**
     * @param class-string $class_name
     */
    public function execute(string $class_name): string
    {
        $reflector = new \ReflectionClass($class_name);

        $filename = $reflector->getFileName();
        if (is_string($filename)) {
            return $filename;
        }
        throw new \Exception('['.__LINE__.']['.__FILE__.']['.$class_name.']');
    }
}
