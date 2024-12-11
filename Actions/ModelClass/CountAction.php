<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Illuminate\Support\Facades\DB;
use Spatie\QueueableAction\QueueableAction;

class CountAction
{
    use QueueableAction;

    public function execute(string $modelClass): int
    {
        $model = app($modelClass);
        $db = $model->getConnection()->getDatabaseName();
        if (':memory:' == $db) {
            return $model->count();
        }
        $table = $model->getTable();
        /*
        $info = DB::select('SELECT * FROM `information_schema`.`TABLES`
            where TABLE_SCHEMA = "'.$db.'" and TABLE_NAME="'.$table.'" ');

        $count = $info[0]->TABLE_ROWS;
        */
        $info = DB::table('information_schema.TABLES')
            ->where('TABLE_SCHEMA', $db)
            ->where('TABLE_NAME', $table)
            ->value('TABLE_ROWS');

        $count = $info ?? 0;

        return $count;
    }
}
