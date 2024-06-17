<?php

namespace Modules\Xot\Filament\Resources\CacheResource\Pages;

use Modules\Xot\Filament\Resources\CacheResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCache extends EditRecord
{
    protected static string $resource = CacheResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
