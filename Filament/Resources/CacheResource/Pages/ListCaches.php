<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\CacheResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;
use Modules\Xot\Filament\Actions\Header\ArtisanHeaderAction;
use Modules\Xot\Filament\Resources\CacheResource;
use Modules\Xot\Filament\Widgets\Clock;

class ListCaches extends ListRecords
{
    protected static string $resource = CacheResource::class;

    public TableLayoutEnum $layoutView = TableLayoutEnum::GRID;

    protected function getTableHeaderActions(): array
    {
        return [
            TableLayoutToggleTableAction::make(),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            Clock::make(),
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

    public function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('key'),
            Tables\Columns\TextColumn::make('value'),
            Tables\Columns\TextColumn::make('exipiration'),
        ];
    }

    public function getTableFilters(): array
    {
        return [];
    }

    public function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
        ];
    }

    public function getTableBuilkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns($this->getTableColumns())
            ->filters($this->getTableFilters())
            ->actions($this->getTableActions())
            ->bulkActions($this->getTableBulkActions());
    }
}
