<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Store;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;

class HasManyAction
{
    use QueueableAction;

    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        if (! $relationDTO->rows instanceof HasMany) {
            throw new \Exception('['.__LINE__.']['.class_basename($this).']');
        }

        $rows = $relationDTO->rows;
        $rows->create($relationDTO->data);
    }
}
