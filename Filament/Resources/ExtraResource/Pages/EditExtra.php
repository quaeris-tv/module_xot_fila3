<?php

namespace Modules\Xot\Filament\Resources\ExtraResource\Pages;

use Modules\Xot\Filament\Resources\ExtraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExtra extends EditRecord
{
    protected static string $resource = ExtraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
