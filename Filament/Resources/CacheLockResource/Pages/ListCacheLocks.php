<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\CacheLockResource\Pages;

use Filament\Actions;
use Modules\Xot\Filament\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Resources\CacheLockResource;

class ListCacheLocks extends XotBaseListRecords
{
    protected static string $resource = CacheLockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
