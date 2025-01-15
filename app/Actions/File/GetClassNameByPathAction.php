<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\File;

use function Safe\file_get_contents;
use function Safe\preg_match;

use Spatie\QueueableAction\QueueableAction;

class GetClassNameByPathAction
{
    use QueueableAction;

    public function execute(string $path): string
    {
        $content = file_get_contents($path);

        preg_match('/namespace\s+(.+);/', $content, $namespaceMatch);
        preg_match('/class\s+(\w+)/', $content, $classMatch);

        $namespace = $namespaceMatch[1] ?? '';
        $className = $classMatch[1] ?? '';

        $fullClassName = $namespace ? $namespace.'\\'.$className : $className;

        return $fullClassName;
    }
}

/*
$class = Str::of($path)
                    ->after(base_path('Modules'))
                    ->prepend('\Modules')
                    ->before('.php')
                    ->replace('/', '\\')
                    ->toString();
                    */
