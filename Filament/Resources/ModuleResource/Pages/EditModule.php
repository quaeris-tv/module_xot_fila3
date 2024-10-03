<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ModuleResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Xot\Filament\Resources\ModuleResource;

class EditModule extends EditRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
