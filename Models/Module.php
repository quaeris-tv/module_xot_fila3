<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Nwidart\Modules\Facades\Module as ModuleFacade;
use Sushi\Sushi;

class Module extends Model
{
    use Sushi;

    protected $fillable = [
        'name',
        // 'alias',
        // 'description',
        'status',
        'priority',
        'path',
    ];

    protected function casts(): array
    {
        return [

        'status' => 'boolean',
        'priority' => 'integer',

       ];
    }

    /**
     * @return array
     */
    public function getRows()
    {
        $modules = ModuleFacade::all();
        $modules = Arr::map($modules, function ($module) {
            return [
                'name' => $module->getName(),
                // 'alias' => $module->getAlias(),
                'description' => $module->getDescription(),
                'status' => $module->isEnabled(),
                'priority' => $module->get('priority', 0),
                'path' => $module->getPath(),
            ];
        });

        return array_values($modules);
    }
}
