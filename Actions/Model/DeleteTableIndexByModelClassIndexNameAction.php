<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Spatie\QueueableAction\QueueableAction;

class DeleteTableIndexByModelClassIndexNameAction
{
    use QueueableAction;

    public function execute(string $modelClass, string $indexName): void
    {
        $model = app($modelClass);
        $table = $model->getTable();
        $schemaManager = app(GetSchemaManagerByModelClassAction::class)->execute($modelClass);
        $doctrineTable = $schemaManager->introspectTable($table);
        // $doctrineTable=$schemaManager->listTableDetails($table);
        $doctrineTable->dropIndex($indexName);
        // ALTER TABLE `roles` DROP INDEX `roles_name_guard_name_unique`;
        // dddx(['res'=>$res,'doctrineTable'=>$doctrineTable,'indexName'=>$indexName]);
    }
}
