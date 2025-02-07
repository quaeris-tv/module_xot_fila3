<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Doctrine\DBAL\Connection as DoctrineConnection;
use Illuminate\Database\Connection;

class GetSchemaManagerByModelClassAction
{
    public function execute(string $modelClass): AbstractSchemaManager
    {
        if (!class_exists($modelClass)) {
            throw new RuntimeException("Model class {$modelClass} not found");
        }

        if (!is_subclass_of($modelClass, Model::class)) {
            throw new RuntimeException("Class {$modelClass} must extend " . Model::class);
        }

        /** @var Model $model */
        $model = new $modelClass();
        
        /** @var Connection $connection */
        $connection = $model->getConnection();

        if (!method_exists($connection, 'getDoctrineConnection')) {
            throw new RuntimeException('Database connection does not support Doctrine');
        }

        /** @var DoctrineConnection $doctrineConnection */
        $doctrineConnection = $connection->getDoctrineConnection();
        
        return $doctrineConnection->createSchemaManager();
    }
}
