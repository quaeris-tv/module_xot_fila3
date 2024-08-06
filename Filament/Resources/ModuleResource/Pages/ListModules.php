<?php

<<<<<<< HEAD
declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ModuleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;
use Modules\Xot\Filament\Resources\ModuleResource;
=======
namespace Modules\Xot\Filament\Resources\ModuleResource\Pages;

use Modules\Xot\Filament\Resources\ModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
>>>>>>> e01854d (📝 (EnvData.php): Update method signatures in EnvData class to specify return types for better type safety and clarity)

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

<<<<<<< HEAD
    public TableLayoutEnum $layoutView = TableLayoutEnum::GRID;

    protected function getTableHeaderActions(): array
    {
        return [
            TableLayoutToggleTableAction::make(),
        ];
    }

=======
>>>>>>> e01854d (📝 (EnvData.php): Update method signatures in EnvData class to specify return types for better type safety and clarity)
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
