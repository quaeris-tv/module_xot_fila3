<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Modules\Xot\Filament\Resources\SessionResource\Pages;
use Modules\Xot\Models\Session;

class SessionResource extends Resource
{
    protected static ?string $model = Session::class;

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
            'index' => Pages\ListSessions::route('/'),
            'create' => Pages\CreateSession::route('/create'),
            'edit' => Pages\EditSession::route('/{record}/edit'),
        ];
    }
}
