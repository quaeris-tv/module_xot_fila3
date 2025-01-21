<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\SessionResource\Pages;

use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
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

    /**
     * @return array<string, \Filament\Tables\Columns\Column>
     */
    public function getListTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id'),
            'user_id' => TextColumn::make('user_id'),
            'ip_address' => TextColumn::make('ip_address'),
            'user_agent' => TextColumn::make('user_agent'),
            'payload' => TextColumn::make('payload'),
            'last_activity' => TextColumn::make('last_activity'),
        ];
    }
}
