<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\LogResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Xot\Filament\Resources\LogResource;

class ListLogs extends ListRecords
{
    protected static string $resource = LogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
