<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Spatie\QueueableAction\QueueableAction;

class SelectAction
{
    use QueueableAction;

    /**
     * execute a select query.
     */
    public function execute(string $modelClass, string $sql): array
    {
        $model = app($modelClass);

        return $model->getConnection()->select($sql);
    }
}
