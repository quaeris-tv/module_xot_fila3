<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Store;

use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;

class HasManyDeepAction
{
    use QueueableAction;

    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        // Assert::isInstanceOf($rows = $relationDTO->rows, BelongsToMany::class);
        dddx('wip');
    }
}
