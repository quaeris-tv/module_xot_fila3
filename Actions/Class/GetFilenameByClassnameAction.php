<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Class;

// use Modules\Xot\Services\ArrayService;

use Exception;
use Spatie\QueueableAction\QueueableAction;

class GetFilenameByClassnameAction
{
    use QueueableAction;

    /**
     * @param string $class_name
     */
    public function execute(string $class_name): string
    {
        $filename = null;
        try {
            if(class_exists($class_name)) {
                $reflector = new \ReflectionClass($class_name);
                $filename = $reflector->getFileName();
            }
        } catch (Exception $e) {
            $filename = str_replace('\\', '/', $class_name);
            $filename = base_path($filename).'.php';

        }



        if (is_string($filename)) {
            return $filename;
        }
        throw new \Exception('['.__LINE__.']['.__FILE__.']['.$class_name.']');
    }
}
