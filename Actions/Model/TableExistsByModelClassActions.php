<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Illuminate\Support\Facades\Schema;

class TableExistsByModelClassActions
{
    public function execute(string $modelClass): bool
    {
        if (! class_exists($modelClass)) {
            return false;
        }

        $model = app($modelClass);
        $tableName = $model->getTable();
        return Schema::connection($model->getConnectionName())->hasTable($tableName);
    }
}
