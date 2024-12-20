<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ExtraResource\Pages;

use Filament\Actions;
use Filament\Tables;
use Modules\Xot\Filament\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Resources\ExtraResource;

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

    /**
     * @return array<\Filament\Tables\Filters\BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [];
    }

    /**
     * Undocumented function
     *
     * @return array<\Filament\Tables\Actions\Action|\Filament\Tables\Actions\ActionGroup>
     */
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
