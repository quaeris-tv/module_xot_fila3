<?php

declare(strict_types=1);

/**
 * @see https://github.com/paulvl/backup/blob/master/src/Console/Commands/MysqlDump.php
 */

namespace Modules\Xot\Console\Commands;

use Illuminate\Support\Str;
use Webmozart\Assert\Assert;
use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\File;
use Modules\Xot\Actions\Generate\GenerateModelByModelClass;

class GenerateModelByModelClassCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xot:generate-model {model_class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate a model from model_class';

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
        Assert::string($model_class = $this->argument('model_class'), '['.__LINE__.']['.__FILE__.']');

        app(GenerateModelByModelClass::class)
            ->setCustomReplaces(['DummyTable' => 'lime_survey_xxx'])
            ->execute($model_class);

    }
}
