<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Modules\Tenant\Services\TenantService;
use Modules\Xot\Datas\MetatagData;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;
use Webmozart\Assert\Assert;
use Filament\Support\Colors\Color;

/**
 * @property ComponentContainer $form
 */
class MetatagPage extends Page implements HasForms
{
    use InteractsWithForms;
    use NavigationLabelTrait;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'xot::filament.pages.metatag';

    public function mount(): void
    {
        Assert::isArray($data = config('metatag'));

        // @phpstan-ignore argument.type
        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        $metatag = MetatagData::make();

        return $form
            ->schema(
                [
                    TextInput::make('title')
                        ->required(),
                    TextInput::make('sitename'),
                    TextInput::make('subtitle'),
                    TextInput::make('generator'),
                    TextInput::make('charset'),
                    TextInput::make('author'),
                    TextInput::make('description'),
                    TextInput::make('keywords'),
                    /*
                FileUpload::make('logo_header')
                    ->preserveFilenames()
                    ->image()
                    ->imageEditor()
                    ->moveFiles()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('logo')
                    ->formatStateUsing(fn ($state): array =>[basename($state)])
                    //->formatStateUsing(fn ($state): array =>['/uploads/photos/pexels-giona-mason-19138633.jpg'])
                    ->dehydrateStateUsing(fn ($state) => collect($state)->map(function($item){
                        return Storage::disk('public')->url($item);
                    })->first() )
                                      ,
                */
                    TextInput::make('logo_header'),
                    TextInput::make('logo_header_dark')
                        ->helperText('logo for dark css'),
                    TextInput::make('logo_height'),
                    Repeater::make('colors')
                        ->schema([
                            Select::make('key')
                                ->label('Chiave')
                                ->required()
                                ->options($metatag->getFilamentColors()),
                            Select::make('color')
                                ->label('Colore')
                                ->options(array_combine(
                                    array_keys(Color::all()),
                                    array_keys(Color::all())
                                ))
                                ->reactive(),
                            ColorPicker::make('hex')
                                ->label('Colore personalizzato')
                                ->visible(fn (Get $get) => $get('color') === 'custom')
                                ->required(),
                        ])
                        ->columns(3),
                ]
            )->columns(2)
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        TenantService::saveConfig('metatag', $data);

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->submit('save'),
        ];
    }
}
