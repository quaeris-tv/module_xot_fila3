<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Module;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class GetModuleNameFromModelAction
{
    use QueueableAction;

    public function execute(Model $model): string
    {
        $model_class = $model::class;
        $module = Str::between($model_class, 'Modules\\', '\Models\\');

        return (string) $module;
    }
}
