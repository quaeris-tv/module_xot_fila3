<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Doctrine\DBAL\Schema\Index;
use Spatie\QueueableAction\QueueableAction;

class GetTableIndexesByModelClassAction
{
    use QueueableAction;

    /**
     * @return array<Index>
     */
    public function execute(string $modelClass): array
    {
        $model = app($modelClass);
        $table = $model->getTable();
        $schemaManager = app(GetSchemaManagerByModelClassAction::class)->execute($modelClass);

        return $schemaManager->listTableIndexes($table);
    }
}
