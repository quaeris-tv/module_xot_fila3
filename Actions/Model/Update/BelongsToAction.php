<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;

class BelongsToAction
{
    use QueueableAction;

    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        if (! $relationDTO->rows instanceof BelongsTo) {
            throw new \Exception('['.__LINE__.']['.class_basename($this).']');
        }

        $rows = $relationDTO->rows;

        /*$relationDTO->data e' un array
        if (! \is_array($relationDTO->data)) {
            $related = $rows->getRelated();
            $related = $related->find($relationDTO->data);
            $res = $rows->associate($related);
            $res->save();

            return;
        }
        */

        if (! Arr::isAssoc($relationDTO->data) && 1 === \count($relationDTO->data)) {
            $related_id = $relationDTO->data[0];
            $related = $relationDTO->related->find($related_id);
            $res = $rows->associate($related);
            $res->save();

            return;
        }

        if (Arr::isAssoc($relationDTO->data)) {
            $sub = $rows->firstOrCreate();
            // $sub = $rows->first() ?? $rows->getModel();
            if (null === $sub) {
<<<<<<< HEAD
                throw new \Exception('['.__LINE__.']['.__FILE__.']');
=======
                throw new \Exception('['.__LINE__.']['.class_basename($this).']');
>>>>>>> 9a1e719aa93e06137cb8175cb55e169573197018
            }

            app(RelationAction::class)->execute($sub, $relationDTO->data);
        }

        $fillable = collect($relationDTO->related->getFillable())->merge($relationDTO->related->getHidden());
        $data = collect($relationDTO->data)->only($fillable)->all();

        if ($rows->exists()) {
            // $rows->update($data); // non passa per il mutator
            $model->{Str::camel($relationDTO->name)}->update($data);

            return;
        }

        // dddx([$relation->related, $data]);

        $related = $relationDTO->related->create($data);
        $res = $rows->associate($related);
        $res->save();
    }
}
