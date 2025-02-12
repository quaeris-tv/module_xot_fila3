<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Xot\Actions\Model\Update\BelongsToAction;
use Modules\Xot\Actions\Model\Update\BelongsToManyAction;
use Modules\Xot\Actions\Model\Update\HasManyAction;
use Modules\Xot\Actions\Model\Update\HasOneAction;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;
use RuntimeException;
use Modules\Xot\Datas\RelationData;

class StoreAction
{
    use QueueableAction;

    public function execute(Model $model, array $data, array $rules): Model
    {
        if (! isset($data['lang']) && \in_array('lang', $model->getFillable(), false)) {
            $data['lang'] = app()->getLocale();
        }
        $data['updated_by'] = authId();
        $data['created_by'] = authId();
        /*if (
            ! isset($data['user_id'])
            && \in_array('user_id',  $row->getFillable(), false)
            && 'user_id' !== $row->getKeyName()
        ) {
            $data['user_id'] = \Auth::id();
        }*/

        $validator = Validator::make($data, $rules);
        $validator->validate();

        $model = $model->fill($data);

        $model->save();

        $relations = app(FilterRelationsAction::class)->execute($model, $data);

        foreach ($relations as $relation) {
            $relationType = class_basename(get_class($relation));
            
            $relationAction = match ($relationType) {
                'BelongsTo' => app(BelongsToAction::class),
                'BelongsToMany' => app(BelongsToManyAction::class),
                'HasMany' => app(HasManyAction::class),
                'HasOne' => app(HasOneAction::class),
                default => throw new RuntimeException("Unsupported relation type: $relationType"),
            };
            
            $relationAction->execute($model, RelationData::from($relation));
        }

        // $msg = 'created! ['.$model->getKey().']!';

        // Session::flash('status', $msg); // .

        return $model;
    }
}
