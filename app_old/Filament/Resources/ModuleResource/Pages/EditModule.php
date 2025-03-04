<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ModuleResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\File;
use Modules\Xot\Actions\Array\SaveArrayAction;
use Modules\Xot\Filament\Resources\ModuleResource;
use Modules\Xot\Models\Module;

/**
 * @property Module $record
 */
class EditModule extends \Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //    Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function afterSave(): void
    {
        $module = $this->record; // Ottiene il record corrente
        $config_path = $module->path.'/config/config.php';
        $data = File::getRequire($config_path);
        if (! is_array($data)) {
            $data = [];
        }
        $data = array_merge($data, $module->toArray());
        unset($data['path']);
        app(SaveArrayAction::class)->execute($data, $config_path);

        /*
        $configPath = config_path('modules/colors.php');

        // Prepara l'array di colori
        $colorsConfig = [
            $module->name => [
                'colors' => $module->colors,
                'icon' => $module->icon,
            ],
        ];

        // Se il file di configurazione esiste già, unisci i colori
        if (File::exists($configPath)) {
            $existingConfig = include $configPath;
            $colorsConfig = array_merge($existingConfig, $colorsConfig);
        }

        // Salva il nuovo file di configurazione
        File::put($configPath, '<?php return ' . var_export($colorsConfig, true) . ';');

        // Richiama il file di configurazione per essere sicuro che i colori siano caricati
        Config::set('modules.colors', $colorsConfig);
        */
    }
}
