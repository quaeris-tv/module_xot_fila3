<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\CacheResource\Pages;

use Filament\Actions;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\Xot\Filament\Actions\Header\ArtisanHeaderAction;
use Modules\Xot\Filament\Resources\CacheResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Widgets\Clock;


/**
 * @see CacheResource
 */
class ListCaches extends XotBaseListRecords
{
    public TableLayoutEnum $layoutView = TableLayoutEnum::LIST;

    protected static string $resource = CacheResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            // Clock::make(),
        ];
    }

    public function getListTableColumns(): array
    {
        return [
            'key' => TextColumn::make('key')
                ->searchable()
                ->sortable()
                ->wrap()
                ->label('Key'),

            'value' => TextColumn::make('value')
                ->searchable()
                ->wrap()
                ->label('Value'),

            'expiration' => TextColumn::make('expiration')
                ->dateTime()
                ->sortable()
                ->label('Expiration'),
        ];
    }

    public function getGridTableColumns(): array
    {
        return [
            Stack::make($this->getListTableColumns()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ArtisanHeaderAction::make('route:list'),
            ArtisanHeaderAction::make('icons:cache'),
            ArtisanHeaderAction::make('filament:cache-components'),
            ArtisanHeaderAction::make('filament:clear-cached-components'),
        ];
    }
}
