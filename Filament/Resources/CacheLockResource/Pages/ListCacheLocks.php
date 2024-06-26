<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\CacheLockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Xot\Filament\Resources\CacheLockResource;

class ListCacheLocks extends ListRecords
{
    protected static string $resource = CacheLockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
