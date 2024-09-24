<?php

/**
 * @see https://github.com/protonemedia/laravel-ffmpeg
 */

declare(strict_types=1);

namespace Modules\Xot\Actions;

use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;

class GetModelByModelTypeAction
{
    use QueueableAction;

    /**
     * Execute the action.
     */
    public function execute(string $model_type, ?string $model_id): Model
    {
        $model_class = app(GetModelClassByModelTypeAction::class)->execute($model_type);
        $model = app($model_class);
        if (null !== $model_id) {
            $model = $model->find($model_id);
        }

        if (null === $model) {
<<<<<<< HEAD
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
=======
            throw new \Exception('['.__LINE__.']['.class_basename($this).']');
>>>>>>> 9a1e719aa93e06137cb8175cb55e169573197018
        }

        return $model;
    }
}
