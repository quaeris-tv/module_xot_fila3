<?php

declare(strict_types=1);

/**
 * @see https://github.com/TheDoctor0/laravel-factory-generator. 24 days ago
 * @see https://github.com/mpociot/laravel-test-factory-helper  on 2 Mar 2020.
 * @see https://github.com/laravel-shift/factory-generator on 10 Aug.
 * @see https://dev.to/marcosgad/make-factory-more-organized-laravel-3c19.
 * @see https://medium.com/@yohan7788/seeders-and-faker-in-laravel-6806084a0c7.
 */

namespace Modules\Xot\Actions\Generate;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

/**
 * @see https://github.com/mpociot/laravel-test-factory-helper/blob/master/src/Console/GenerateCommand.php#L213
 */
class ModelByModelClass
{
    use QueueableAction;

    /**
     * Execute the function with the given model class.
     *
     * @param string $model_class the class name of the model
     *
     * @throws \Exception Generating Factory [factory_class] press [F5] to refresh page [__LINE__][__FILE__]
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function execute(string $model_class)
    {
        $factory_class = $this->getFactoryClass($model_class);
        // dddx([file_exists($factory_class), $factory_class, $model_class, class_exists($model_class)]);
        if (class_exists($factory_class)) {
            dddx('a');

            // return $factory_class::new();
            return new $factory_class();
        }

        $this->createModel($model_class);

        throw new \Exception('Generating Factory ['.$factory_class.'] press [F5] to refresh page ['.__LINE__.']['.__FILE__.']');
    }

    public function getFactoryClass(string $model_class): string
    {
        $model_name = class_basename($model_class);
        // dddx($model_name);
        $factory_class = Str::of($model_class)
            // ->before('\Models\\')
            // ->append('\Models\\')
            // ->append($model_name)
            ->toString();

        return $factory_class;
    }

    /**
     * Create a factory for the given model class.
     *
     * @param string $model_class The class name of the model to create the factory for
     *
     * @return void
     */
    public function createModel(string $model_class)
    {
        $model_name = class_basename($model_class);
        $module_name = Str::of($model_class)->between('Modules\\', '\Models\\')->toString();
        $artisan_cmd = 'module:make-model';
        $artisan_params = ['model' => $model_name, 'module' => $module_name];
        // dddx([$artisan_cmd, $artisan_params]);
        Artisan::call($artisan_cmd, $artisan_params);
    }
}
