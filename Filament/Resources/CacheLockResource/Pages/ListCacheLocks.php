<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\CacheLockResource\Pages;

use Filament\Actions;
use Modules\UI\Enums\TableLayoutEnum;
use Filament\Resources\Pages\ListRecords;
use Modules\Xot\Filament\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Resources\CacheLockResource;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;

class ListCacheLocks extends XotBaseListRecords
{
    
    protected static string $resource = CacheLockResource::class;

    protected function getTableHeaderActions(): array
    {
        return [
            TableLayoutToggleTableAction::make(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
