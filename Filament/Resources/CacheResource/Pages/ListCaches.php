<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\CacheResource\Pages;

use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Stack;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\Xot\Filament\Actions\Header\ArtisanHeaderAction;
use Modules\Xot\Filament\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Resources\CacheResource;
use Modules\Xot\Filament\Widgets\Clock;

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
            Tables\Columns\TextColumn::make('key'),
            Tables\Columns\TextColumn::make('value'),
            Tables\Columns\TextColumn::make('exipiration'),
        ];
    }

    public function getGridTableColumns(): array
    {
        return [
            Stack::make([
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('value'),
                Tables\Columns\TextColumn::make('exipiration'),
            ]),
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
