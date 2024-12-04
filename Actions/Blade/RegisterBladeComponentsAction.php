<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Blade;

use Illuminate\Support\Facades\Blade;
use Modules\Xot\Actions\File\GetComponentsAction;
use Spatie\QueueableAction\QueueableAction;

class RegisterBladeComponentsAction
{
    use QueueableAction;

    public function execute(string $path, string $namespace, string $prefix = ''): void
    {
        $comps = app(GetComponentsAction::class)
            ->execute($path, $namespace.'\View\Components', $prefix);
        foreach ($comps as $comp) {
            Blade::component($comp->comp_name, $comp->comp_ns);
        }
    }
}
