<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Modules\Xot\Actions\Model\UpdateAction;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;

class BelongsToManyAction
{
    use QueueableAction;

    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        // dddx(['row' => $row, 'relation' => $relation]);
        if (\in_array('to', array_keys($relationDTO->data), false) || \in_array('from', array_keys($relationDTO->data), false)) {
            // $this->saveMultiselectTwoSides($row, $relation->name, $relation->data);
            $to = $relationDTO->data['to'] ?? [];

            $model->{$relationDTO->name}()->sync($to);
            $status = 'collegati ['.implode(', ', $to).'] ';
            Session::flash('status', $status);

            return;
        }

        $models = [];
        $ids = [];
        $related = $relationDTO->related;
        $keyName = $relationDTO->related->getKeyName();

        foreach ($relationDTO->data as $data) {
            if (\in_array($keyName, array_keys($data), false)) {
                // $related_id = $data[$keyName];

                // $row = $related->firstOrCreate([$keyName => $related_id]);

                $res = app(UpdateAction::class)->execute($related, $data, []);
                /*
                dddx([
                    'model' => $model,
                    'relationDTO' => $relationDTO,
                    'related' => $related,
                    'keyName' => $keyName,
                    'related_id' => $related_id,
                    'row_id' => $row->{$keyName},
                    'row' => $row,
                    'res' => $res,
                ]);
                $ids[] = $related_id;
                */
                $ids[] = $res->getKey();
                $models[] = $res;
            } else {
                dddx(['model' => $model, 'relationDTO' => $relationDTO]);
            }
        }

        if ([] !== $ids) {
            try {
                $model->{$relationDTO->name}()->syncWithoutDetaching($ids);
            } catch (\Exception $e) {
                dddx(
                    [
                        'message' => $e->getMessage(),
                        'model' => $model,
                        'relationDTO' => $relationDTO,
                    ]
                );
            }

            return;
        }

        /* ---  controllare
        try {
            $model->{$relationDTO->name}()->sync($relationDTO->data);
        } catch (\Exception $e) {
            dddx([
                'message' => $e->getMessage(),
                'model' => $model,
                'relationDTO' => $relationDTO,
            ]);
        }
        */
    }
}
