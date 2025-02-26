<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\SessionResource\Pages;

use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Resources\SessionResource;

/**
 * @see SessionResource
 */
class ListSessions extends XotBaseListRecords
{
    protected static string $resource = SessionResource::class;

    public function getGridTableColumns(): array
    {
        return [
            Stack::make($this->getListTableColumns()),
        ];
    }

    public function getListTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')
                ->sortable()
                ->label('ID'),

            'user_id' => TextColumn::make('user_id')
                ->sortable()
                ->searchable()
                ->label('User ID'),

            'ip_address' => TextColumn::make('ip_address')
                ->searchable()
                ->label('IP Address'),

            'user_agent' => TextColumn::make('user_agent')
                ->searchable()
                ->wrap()
                ->label('User Agent'),

            'payload' => TextColumn::make('payload')
                ->searchable()
                ->wrap()
                ->label('Payload'),

            'last_activity' => TextColumn::make('last_activity')
                ->dateTime()
                ->sortable()
                ->label('Last Activity'),
        ];
    }
}
