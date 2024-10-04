<?php

declare(strict_types=1);
/**
 * @see  https://fajarwz.com/blog/laravel-database-transaction-for-data-consistency/
 */

namespace Modules\Xot\Actions\Query;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class StartQueryLogAction
{
    use QueueableAction;

    public function execute(): void
    {
        Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
            $sql = $query->sql;
            $time = $query->time;
            $connection = $query->connection->getName();

            $log = Log::build([
                'driver' => 'daily',
                'path' => storage_path('logs/querylog.log'),
            ]);
            $log->debug('query : '.$sql);
            $log->debug('time '.$time);
            $log->debug('connection '.$connection);
            $log->debug('bindings '.print_r($query->bindings, true));
        });
    }
}
