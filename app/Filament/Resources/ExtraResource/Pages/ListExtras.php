<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ExtraResource\Pages;

use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\ExtraResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

/**
 * @see ExtraResource
 */
class ListExtras extends XotBaseListRecords
{
    protected static string $resource = ExtraResource::class;

    public function getListTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')
                ->sortable()
                ->label('ID'),

            'model_type' => TextColumn::make('model_type')
                ->searchable()
                ->label('Model Type'),

            'model_id' => TextColumn::make('model_id')
                ->sortable()
                ->label('Model ID'),

            'extra_attributes' => TextColumn::make('extra_attributes')
                ->searchable()
                ->label('Extra Attributes'),
        ];
    }

    /**
     * @return array<Tables\Filters\BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [];
    }

    /**
     * @return array<Tables\Actions\Action|Tables\Actions\ActionGroup>
     */
    public function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
        ];
    }

    public function getTableBulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make(),
        ];
    }
}
