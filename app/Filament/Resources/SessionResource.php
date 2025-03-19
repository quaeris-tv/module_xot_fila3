<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Modules\Xot\Filament\Resources\SessionResource\Pages;
use Modules\Xot\Models\Session;
use Modules\Xot\Filament\Resources\XotBaseResource\RelationManager\XotBaseRelationManager;

class SessionResource extends XotBaseResource
{
    protected static ?string $model = Session::class;

    public static function getFormSchema(): array
    {
        return [
            'id' => TextInput::make('id')
                ->required()
                ->maxLength(255),

            'user_id' => TextInput::make('user_id')
                ->numeric(),

            'ip_address' => TextInput::make('ip_address')
                ->maxLength(45),

            'user_agent' => TextInput::make('user_agent')
                ->maxLength(255),

            'payload' => KeyValue::make('payload')
                ->columnSpanFull(),

            'last_activity' => TextInput::make('last_activity')
                ->required()
                ->numeric(),
        ];
    }


}
