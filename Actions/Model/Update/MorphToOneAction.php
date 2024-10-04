<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Fidum\EloquentMorphToOne\MorphToOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;

class MorphToOneAction
{
    use QueueableAction;

    /**
     * Undocumented function.
     */
    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        // dddx(['row' => $row, 'relation' => $relation]);
        if (! $relationDTO->rows instanceof MorphToOne) {
            throw new \Exception('['.__LINE__.']['.class_basename($this).']');
        }

        $rows = $relationDTO->rows;

        // if (is_array($relation->data)) {
        if (! isset($relationDTO->data['lang'])) {
            $relationDTO->data['lang'] = App::getLocale();
        }

        $rows->create($relationDTO->data);
        // }
        // else {
        //    $rows->sync($relation->data);
        // }
    }
}
