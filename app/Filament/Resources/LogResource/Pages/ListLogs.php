<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\LogResource\Pages;

use Filament\Tables\Columns\TextColumn;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\Xot\Filament\Resources\LogResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

/**
 * @see LogResource
 */
class ListLogs extends XotBaseListRecords
{
    public TableLayoutEnum $layoutView = TableLayoutEnum::LIST;

    protected static string $resource = LogResource::class;

    public function getListTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')
                ->sortable()
                ->label('ID'),

            'message' => TextColumn::make('message')
                ->searchable()
                ->wrap()
                ->label('Message'),

            'level' => TextColumn::make('level')
                ->searchable()
                ->sortable()
                ->label('Level'),

            'level_name' => TextColumn::make('level_name')
                ->searchable()
                ->sortable()
                ->label('Level Name'),

            'context' => TextColumn::make('context')
                ->searchable()
                ->wrap()
                ->label('Context'),

            'created_at' => TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->label('Created At'),
        ];
    }
}
