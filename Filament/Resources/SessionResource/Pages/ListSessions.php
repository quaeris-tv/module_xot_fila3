<?php

namespace Modules\Xot\Filament\Resources\SessionResource\Pages;

use Modules\Xot\Filament\Resources\SessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSessions extends ListRecords
{
    protected static string $resource = SessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
