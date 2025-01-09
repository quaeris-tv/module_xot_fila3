<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\CacheLockResource\Pages;

use Filament\Actions;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Resources\CacheLockResource;
use Filament\Tables\Columns\TextColumn;

class ListCacheLocks extends XotBaseListRecords
{
    protected static string $resource = CacheLockResource::class;

    public function getListTableColumns(): array
    {
        return [
            'key' => TextColumn::make('key')
                ->searchable()
                ->sortable()
                ->wrap(),
            'owner' => TextColumn::make('owner')
                ->searchable()
                ->sortable()
                ->wrap(),
            'expiration' => TextColumn::make('expiration')
                ->numeric()
                ->sortable(),
        ];
    }
}
