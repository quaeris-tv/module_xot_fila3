<?php

declare(strict_types=1);

/**
 * @see https://github.com/TheDoctor0/laravel-factory-generator. 24 days ago
 * @see https://github.com/mpociot/laravel-test-factory-helper  on 2 Mar 2020.
 * @see https://github.com/laravel-shift/factory-generator on 10 Aug.
 * @see https://dev.to/marcosgad/make-factory-more-organized-laravel-3c19.
 * @see https://medium.com/@yohan7788/seeders-and-faker-in-laravel-6806084a0c7.
 */

namespace Modules\Xot\Actions\Factory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

use function Safe\preg_replace;

/**
 * @see https://github.com/mpociot/laravel-test-factory-helper/blob/master/src/Console/GenerateCommand.php#L213
 */
class GetPropertiesFromMethodsByModelAction
{
    use QueueableAction;

    public function execute(Model $model): array
    {
        $methods = get_class_methods($model);
        $data = [];
        foreach ($methods as $method) {
            if (! Str::startsWith($method, 'get') && ! method_exists('Illuminate\Database\Eloquent\Model', $method)) {
                // Use reflection to inspect the code, based on Illuminate/Support/SerializableClosure.php
                $reflection = new \ReflectionMethod($model, $method);
                /** @var string */
                $filename = $reflection->getFileName();
                $file = new \SplFileObject($filename);
                $file->seek($reflection->getStartLine() - 1);
                $code = '';
                while ($file->key() < $reflection->getEndLine()) {
                    $code .= $file->current();
                    $file->next();
                }
                $code = trim(preg_replace('/\s\s+/', '', $code));
                $begin = (int) mb_strpos($code, 'function(');
                $length = (int) mb_strrpos($code, '}') - $begin + 1;
                $code = mb_substr($code, $begin, $length);
                foreach (['belongsTo'] as $relation) {
                    $search = '$this->'.$relation.'(';
                    if ($pos = mb_stripos($code, $search)) {
                        $relationObj = $model->$method();
                        if ($relationObj instanceof Relation) {
                            // $this->setProperty($relationObj->getForeignKeyName(), 'factory('.get_class($relationObj->getRelated()).'::class)');
                            if (! method_exists($relationObj, 'getForeignKeyName')) {
                                throw new \Exception('[WIP]['.__LINE__.']['.class_basename($this).']');
                            }
                            $name = $relationObj->getForeignKeyName();
                            $type = 'factory('.get_class($relationObj->getRelated()).'::class)';
                            $table = null;
                            $data['name'] = app(GetFakerAction::class)->execute($name, $type, $table);
                        }
                    }
                }
            }
        }

        return $data;
    }
}
