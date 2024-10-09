<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Support\Colors\Color;
use Modules\UI\Filament\Forms\Components\IconPicker;
use Modules\Xot\Filament\Resources\ModuleResource\Pages;
use Modules\Xot\Models\Module;

class ModuleResource extends XotBaseResource
{
    protected static ?string $model = Module::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('description'),
                        IconPicker::make('icon'),
                        TextInput::make('priority'),
                        Toggle::make('status'),
                        /*
                        Forms\Components\Repeater::make('colors')
                        ->label('Colors')
                        ->schema([
                            TextInput::make('color')
                                ->label('Colore')
                                ->required(),
                        ])
                        ->minItems(1),
                        */
                        /*
                        KeyValue::make('colors')
                            ->reorderable()
                            ->columnSpanFull(),
                        */
                        Section::make()->schema([
                            Repeater::make('colors')
                                ->schema([
                                    TextInput::make('key')->required(),
                                    Select::make('value')
                                        ->options(function (): array {
                                            $colors = array_keys(Color::all());
                                            $colors = array_combine($colors, $colors);

                                            return $colors;
                                        }),
                                    ColorPicker::make('color'),
                                ])
                                ->columns(3),
                        ])->columnSpanFull(),
                    ])->columns(3),
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
            'index' => Pages\ListModules::route('/'),
            'create' => Pages\CreateModule::route('/create'),
            'edit' => Pages\EditModule::route('/{record}/edit'),
        ];
    }
}
