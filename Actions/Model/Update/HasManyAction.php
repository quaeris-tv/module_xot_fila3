<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Xot\Actions\Model\UpdateAction;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;

class HasManyAction
{
    use QueueableAction;

    /**
     * Undocumented function.
     */
    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        if (! $relationDTO->rows instanceof HasMany) {
            throw new \Exception('['.__LINE__.']['.class_basename($this).']');
        }

        if (isset($relationDTO->data['from']) && isset($relationDTO->data['to'])) {
            $f_key = $relationDTO->rows->getForeignKeyName();
            $res = $relationDTO->related->where($f_key, $model->getKey())
                ->update([$f_key => null]);
            foreach ($relationDTO->data['to'] as $item) {
                $row0 = $relationDTO->related
                    ->where('id', $item)
                    ->update([$f_key => $model->getKey()]);
            }

            return;
        }

        $models = [];
        $ids = [];
        $related = $relationDTO->related;
        $keyName = $relationDTO->related->getKeyName();

        $rows = $relationDTO->rows;

        /*
         "getExistenceCompareKey" => "asset_operation.operation_id"
        "getParentKey" => "99f91389-a3a3-4d1b-9cb0-e937e9aa8183"
        "getQualifiedParentKeyName" => "operations.id"
        "getForeignKeyName" => "operation_id"
        "getQualifiedForeignKeyName" => "asset_operation.operation_id"
        "getLocalKeyName" => "id"
        "getRelationCountHash" => "laravel_reserved_0"
        */

        $parentKey = $rows->getParentKey();

        $foreignKeyName = $rows->getForeignKeyName();

        // dddx(get_class_methods($relationDTO->rows));

        foreach ($relationDTO->data as $data) {
            if (\in_array($keyName, array_keys($data), false)) {
                $data[$foreignKeyName] = $parentKey;
                $res = app(UpdateAction::class)->execute($related, $data, []);

                /*
                dddx([
                    'model' => $model,
                    'relationDTO' => $relationDTO,
                    'related' => $related,
                    'keyName' => $keyName,
                    'data' => $data,
                    'res' => $res,
                ]);

                // */
                $ids[] = $res->getKey();
                $models[] = $res;
            } else {
                dddx(['model' => $model, 'relationDTO' => $relationDTO]);
            }
        }

        // dddx(['model' => $model, 'relationDTO' => $relationDTO]);

        // $rows = $relationDTO->rows;
        // $rows->update($relationDTO->data); //NON CANCELLARE
    }
}
