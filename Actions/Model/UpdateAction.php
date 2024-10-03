<?php

declare(strict_types=1);

/**
 * --- usata ricorsivamente.
 */

namespace Modules\Xot\Actions\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function execute(Model $model, array $data, array $rules): Model
    {
        $validator = Validator::make($data, $rules);
        $validator->validate();

        $keyName = $model->getKeyName();
        // $data['updated_by'] = authId();
        if (null === $model->getKey()) {
            $key = $data[$keyName];
            $data = collect($data)->except($keyName)->toArray();

            if (method_exists($model, 'withTrashed')) {
                $model = $model->withTrashed();
            }

            $model = $model->firstOrCreate([$keyName => $key], $data);
        }

        /**
         * @phpstan-ignore method.notFound (.)
         */
        $model = tap($model)->update($data);

        app(__NAMESPACE__.'\\Update\RelationAction')->execute($model, $data);

        $msg = 'aggiornato! ['.$model->getKey().']!';

        Session::flash('status', $msg); // .

        return $model;
    }
}
