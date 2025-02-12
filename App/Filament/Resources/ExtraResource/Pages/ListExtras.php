<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ExtraResource\Pages;

use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
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
                ,

            'model_type' => TextColumn::make('model_type')
                ->searchable()
                ,

            'model_id' => TextColumn::make('model_id')
                ->sortable()
                ,

            'extra_attributes' => TextColumn::make('extra_attributes')
                ->searchable()
                ,
        ];
    }

    
}
