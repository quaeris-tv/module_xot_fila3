<?php
<<<<<<< HEAD
=======
/**
 * @see https://github.com/shuvroroy/filament-spatie-laravel-health/tree/main
 */
>>>>>>> 5bbbe11 (.)

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

<<<<<<< HEAD
use Filament\Pages\Page;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;

class EnvPage extends Page
{
    use NavigationLabelTrait;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'xot::filament.pages.dashboard';
=======
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Modules\Xot\Datas\EnvData;
use Modules\Xot\Filament\Traits\NavigationPageLabelTrait;

class EnvPage extends Page implements HasForms
{
    use NavigationPageLabelTrait;
    use InteractsWithForms;
    protected static string $view = 'xot::filament.pages.env';
    public ?array $data = [];

    public function mount(): void
    {
        $this->data = EnvData::make()->toArray();

        $this->form->fill($this->data);
    }

    protected function getFormSchema(): array
    {
        return [
            /*
            Forms\Components\Repeater::make('players')
                ->schema([
                    Forms\Components\Select::make('name')
                        ->options(Player::pluck('name', 'id')->toArray())
                        ->reactive()
                        ->required()
                ])
                ->disableLabel()
                ->defaultItems(10)
                ->disableItemCreation()
                ->disableItemDeletion()
                ->disableItemMovement()
            */
            TextInput::make('app_url')
                ->label('URL')
                ->placeholder('http://localhost')
                ->helperText('Required for file uploads and other internal configs')
                ->required(),
            Toggle::make('debugbar_enabled')
                ->label('Is Enabled')
                ->helperText('Enable/Disable debug mode to help debug errors'),
        ];
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->columns(2)
            ->statePath('data');
    }

    public function submit()
    {
        $res = EnvData::make()->update($this->data);
        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
        /*
        dddx([
            'data' => $this->data,
            // 'data1' => $this->form->getState(),
        ]);
        */
    }
>>>>>>> 5bbbe11 (.)
}
