<?php

declare(strict_types=1);

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

        if (null === $model->getKey()) {
            $key = $data[$keyName];
            $data = collect($data)->except($keyName)->toArray();
            $model = $model->withTrashed()->firstOrCreate([$keyName => $key], $data);
        }

        $model = tap($model)->update($data);

        app(__NAMESPACE__.'\\Update\RelationAction')->execute($model, $data);

        $msg = 'aggiornato! ['.$model->getKey().']!'; // .'['.implode(',',$row->getChanges()).']';

        Session::flash('status', $msg); // .

        return $model;
    }
}
