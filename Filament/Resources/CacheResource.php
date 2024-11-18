<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources;

use Filament\Forms\Form;
use Modules\Xot\Filament\Resources\CacheResource\Pages;
use Modules\Xot\Models\Cache;

class CacheResource extends XotBaseResource
{
    protected static ?string $model = Cache::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCaches::route('/'),
            'create' => Pages\CreateCache::route('/create'),
            'edit' => Pages\EditCache::route('/{record}/edit'),
        ];
    }
}
