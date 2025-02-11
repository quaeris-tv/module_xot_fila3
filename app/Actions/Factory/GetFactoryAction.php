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

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

/**
 * @see https://github.com/mpociot/laravel-test-factory-helper/blob/master/src/Console/GenerateCommand.php#L213
 */
class GetFactoryAction
{
    use QueueableAction;

    /**
     * Execute the function with the given model class.
     *
     * @param  string  $model_class  the class name of the model
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     *
     * @throws \Exception Generating Factory [factory_class] press [F5] to refresh page [__LINE__][__FILE__]
     */
    public function execute(string $model_class)
    {
        $factory_class = $this->getFactoryClass($model_class);

        if (class_exists($factory_class)) {
            return $factory_class::new();
        }

        $this->createFactory($model_class);

        throw new \Exception('Generating Factory ['.$factory_class.'] press [F5] to refresh page ['.__LINE__.']['.class_basename($this).']');
    }

    public function getFactoryClass(string $model_class): string
    {
        $model_name = class_basename($model_class);
        $factory_class = Str::of($model_class)
            ->before('\Models\\')
            ->append('\Database\Factories\\')
            ->append($model_name)
            ->append('Factory')
            ->toString();

        return $factory_class;
    }

    /**
     * Create a factory for the given model class.
     *
     * @param  string  $model_class  The class name of the model to create the factory for
     * @return void
     */
    public function createFactory(string $model_class)
    {
        /*
        $model = app($model_class);
        $dataFromTable = app(GetPropertiesFromTableByModelAction::class)->execute($model);
        $dataFromMethods = app(GetPropertiesFromMethodsByModelAction::class)->execute($model);

        dddx([
            'dataFromTable' => $dataFromTable,
            'dataFromMethods' => $dataFromMethods,
        ]);
        */
        $model_name = class_basename($model_class);
        $module_name = Str::of($model_class)->between('Modules\\', '\Models\\')->toString();
        $artisan_cmd = 'module:make-factory';
        $artisan_params = ['name' => $model_name, 'module' => $module_name];
        Artisan::call($artisan_cmd, $artisan_params);

        /*
        dddx([
            'message' => 'WIP',
            'model_name' => $model_class,
        ]);
        */
    }
}
