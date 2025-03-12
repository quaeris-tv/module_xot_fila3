<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\LogResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Modules\Xot\Filament\Resources\LogResource;

use function Safe\json_encode;

class ViewLog extends \Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord
{
    protected static string $resource = LogResource::class;

    public function getInfolistSchema(): array
    {
        return [
            Section::make('Informazioni Log')
                ->schema([
                    Grid::make(['default' => 3])
                        ->schema([
                            TextEntry::make('id')
                                ->label('ID'),
                            TextEntry::make('message')
                                ->label('Messaggio'),
                            TextEntry::make('level')
                                ->label('Livello'),
                            TextEntry::make('level_name')
                                ->label('Nome Livello'),
                            TextEntry::make('channel')
                                ->label('Canale'),
                            TextEntry::make('datetime')
                                ->label('Data e Ora')
                                ->dateTime(),
                            TextEntry::make('context')
                                ->label('Contesto')
                                ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT)),
                            TextEntry::make('extra')
                                ->label('Extra')
                                ->formatStateUsing(fn ($state) => json_encode($state, JSON_PRETTY_PRINT)),
                        ]),
                ]),
        ];
    }
}
