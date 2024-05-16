<?php

declare(strict_types=1);

/**
 * @see https://github.com/TheDoctor0/laravel-factory-generator. 24 days ago
 * @see https://github.com/mpociot/laravel-test-factory-helper  on 2 Mar 2020.
 * @see https://github.com/laravel-shift/factory-generator on 10 Aug.
 * @see https://dev.to/marcosgad/make-factory-more-organized-laravel-3c19.
 * @see https://medium.com/@yohan7788/seeders-and-faker-in-laravel-6806084a0c7.
 */

namespace Modules\Xot\Actions\Factory;

use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;

/**
 * @see https://github.com/mpociot/laravel-test-factory-helper/blob/master/src/Console/GenerateCommand.php#L213
 */
class GetPropertiesFromTableByModelAction
{
    use QueueableAction;

    public function execute(Model $model): array
    {
        $table = $model->getConnection()->getTablePrefix().$model->getTable();
        // Method Illuminate\Database\Connection::getDoctrineSchemaManager() invoked with 1 parameter, 0 required.
        // $schema = $model->getConnection()->getDoctrineSchemaManager($table);
        // Call to an undefined method Illuminate\Database\Connection::getDoctrineSchemaManager().
        $schema = $model->getConnection()->getDoctrineSchemaManager();
        $databasePlatform = $schema->getDatabasePlatform();
        // Type to be overwritten customEnum does not exist.
        // $databasePlatform->registerDoctrineTypeMapping('enum', 'customEnum');

        $platformName = $databasePlatform->getName();
        // $customTypes = $this->laravel['config']->get("ide-helper.custom_db_types.{$platformName}", []);
        // foreach ($customTypes as $yourTypeName => $doctrineTypeName) {
        //    $databasePlatform->registerDoctrineTypeMapping($yourTypeName, $doctrineTypeName);
        // }

        $database = null;
        if (strpos($table, '.')) {
            [$database, $table] = explode('.', $table);
        }

        $columns = $schema->listTableColumns($table, $database);
        $data = [];
        if ($columns) {
            foreach ($columns as $column) {
                $name = $column->getName();
                if (in_array($name, $model->getDates())) {
                    $type = 'datetime';
                } else {
                    $type = $column->getType()->getName();
                }
                if (! ($model->incrementing && $model->getKeyName() === $name)
                    && $name !== $model::CREATED_AT
                    && $name !== $model::UPDATED_AT
                ) {
                    if (! method_exists($model, 'getDeletedAtColumn') || (method_exists($model, 'getDeletedAtColumn') && $name !== $model->getDeletedAtColumn())) {
                        // $this->setProperty($name, $type, $table);
                        $data['name'] = app(GetFakerAction::class)->execute($name, $type, $table);
                    }
                }
            }
        }

        return $data;
    }
}
