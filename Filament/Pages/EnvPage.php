<?php
/**
 * @see https://github.com/shuvroroy/filament-spatie-laravel-health/tree/main
 */

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Filament\Pages\Page;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;

class EnvPage extends Page
{
    use NavigationLabelTrait;

    protected static ?string $navigationIcon = 'heroicon-o-home';

<<<<<<< HEAD
    protected static string $view = 'xot::filament.pages.dashboard';
=======
        $this->form->fill($this->data);
    }

    protected function getFormSchema(): array
    {
        return [
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
>>>>>>> f9afc07 (.)
}
