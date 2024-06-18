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
        try {
            $path = str_replace('\\', '/', $class_name);
            $path = base_path($path).'.php';

            return $path;
        } catch (\Throwable $th) {
            // throw $th;
        }

        $reflector = new \ReflectionClass($class_name);

        $filename = $reflector->getFileName();

        if (is_string($filename)) {
            return $filename;
        }
        throw new \Exception('['.__LINE__.']['.__FILE__.']['.$class_name.']');
    }
}