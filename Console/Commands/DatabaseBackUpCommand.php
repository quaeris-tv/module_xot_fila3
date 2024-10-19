<?php

declare(strict_types=1);

/**
 * @see https://github.com/paulvl/backup/blob/master/src/Console/Commands/MysqlDump.php
 */

namespace Modules\Xot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use function Safe\exec;

use Webmozart\Assert\Assert;

class DatabaseBackUpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump your Mysql database to a file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $filename = 'backup-'.Carbon::now()->format('Y-m-d').'.gz';
        $backup_path = storage_path('app/backup/'.$filename);
        Assert::string($backup_path = Str::replace(['/', '\\'], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR], $backup_path), 'wip');
        Assert::string($user = env('DB_USERNAME'));
        Assert::string($password = env('DB_PASSWORD'));
        Assert::string($host = env('DB_HOST'));
        Assert::string($database = env('DB_DATABASE'));
        $command = 'mysqldump --user='.$user.' --password='.$password.' --host='.$host.' '.$database.'  | gzip > '.$backup_path;

        $returnVar = null;
        $output = null;
        // echo $command;
        exec($command, $output, $returnVar);
    }
}
