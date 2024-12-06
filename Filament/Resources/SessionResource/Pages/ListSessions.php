<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\SessionResource\Pages;

use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Resources\SessionResource;

class ListSessions extends XotBaseListRecords
{
    protected static string $resource = SessionResource::class;

    public function getGridTableColumns(): array
    {
        return [
            Stack::make([
                TextColumn::make('id'),
                TextColumn::make('user_id'),
                TextColumn::make('ip_address'),
                // TextColumn::make('user_agent'),
                // TextColumn::make('payload'),
                TextColumn::make('last_activity'),
            ]),
        ];
    }

    public function getListTableColumns(): array
    {
        return [
            TextColumn::make('id'),
            TextColumn::make('user_id'),
            TextColumn::make('ip_address'),
            // TextColumn::make('user_agent'),
            // TextColumn::make('payload'),
            TextColumn::make('last_activity'),
        ];
    }
}
