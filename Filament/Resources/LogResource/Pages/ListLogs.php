<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\LogResource\Pages;

use Filament\Actions;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\Xot\Filament\Resources\LogResource;
use Modules\Xot\Filament\Pages\XotBaseListRecords;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;

class ListLogs extends XotBaseListRecords
{
    public TableLayoutEnum $layoutView = TableLayoutEnum::LIST;

    protected static string $resource = LogResource::class;

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
