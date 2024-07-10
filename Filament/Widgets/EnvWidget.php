<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Widgets;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Arr;
use Modules\Xot\Datas\EnvData;

class EnvWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'xot::filament.widgets.env';

    public ?array $data = [];

    public array $only = [];

    public function mount(): void
    {
        $this->data = EnvData::make()->toArray();

        $this->form->fill($this->data);
    }

    protected function getFormSchema(): array
    {
        $all = [
            'app_url' => TextInput::make('app_url')
                ->label('URL')
                ->placeholder('http://localhost')
                ->helperText('Required for file uploads and other internal configs')
                ->required(),
            'debugbar_enabled' => Toggle::make('debugbar_enabled')
                ->label('Is Enabled')
                ->helperText('Enable/Disable debug mode to help debug errors'),
            'google_maps_api_key' => TextInput::make('google_maps_api_key')
                ->placeholder('AIzaSyAuB_...')
                ->helperText('google maps api key'),
        ];

        $fields = Arr::only($all, $this->only);

        return $fields;
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->columns(1)
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
}
