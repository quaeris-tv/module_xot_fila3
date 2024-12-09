<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Blade;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Blade;
use Modules\Xot\Actions\File\GetComponentsAction;
use Modules\Xot\Datas\ComponentFileData;
use Spatie\QueueableAction\QueueableAction;

class RegisterBladeComponentsAction
{
    use QueueableAction;

    public function execute(string $path, string $namespace, string $prefix = ''): void
    {
        $comps = app(GetComponentsAction::class)
            ->execute($path, $namespace.'\View\Components', $prefix);

        if (0 == $comps->count()) {
            return;
        }
        foreach ($comps->items() as $comp) {
            if (! $comp instanceof ComponentFileData) {
                continue;
            }
            try {
                Blade::component($comp->name, $comp->ns);
            } catch (\Error $e) {
                Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->persistent()
                ->danger()
                ->send();
            }
        }
    }
}
