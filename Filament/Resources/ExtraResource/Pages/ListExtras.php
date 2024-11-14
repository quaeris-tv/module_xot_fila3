<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ExtraResource\Pages;

use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Modules\UI\Enums\TableLayoutEnum;
use Filament\Resources\Pages\ListRecords;
use Modules\Xot\Filament\Resources\ExtraResource;
use Modules\Xot\Filament\Pages\XotBaseListRecords;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;

class ListExtras extends XotBaseListRecords
{
    
    protected static string $resource = ExtraResource::class;

    public function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id'),
            Tables\Columns\TextColumn::make('model_type'),
            Tables\Columns\TextColumn::make('model_id'),
            Tables\Columns\TextColumn::make('extra_attributes'),
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
