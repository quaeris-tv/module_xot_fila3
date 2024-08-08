<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Spatie\QueueableAction\QueueableAction;

class GetSchemaManagerByModelClassAction
{
    use QueueableAction;

    public function execute(string $modelClass): AbstractSchemaManager
    {
        $model = app($modelClass);
        $connection = $model->getConnection();

        return $connection->getDoctrineSchemaManager();
    }
}
