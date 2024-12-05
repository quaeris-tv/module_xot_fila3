<?php

/**
 * @see https://coderflex.com/blog/create-advanced-filters-with-filament
 */

declare(strict_types=1);

namespace Modules\Xot\Filament\Actions\Header;

// Header actions must be an instance of Filament\Actions\Action, or Filament\Actions\ActionGroup.
// use Filament\Tables\Actions\Action;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Modules\Xot\Actions\ModelClass\FakeSeederAction;

class FakeSeederHeaderAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->translateLabel()
            ->label('')
            ->tooltip(__('xot::actions.fake_seeder'))
            ->icon('fas-seedling')
            ->form([
                TextInput::make('qty')
                    ->required(),
            ])
            ->action(function (array $data, ListRecords $livewire) {
                $resource = $livewire->getResource();
                $modelClass = $resource::getModel();
                $qty = (int) $data['qty'];
                app(FakeSeederAction::class)
                        ->onQueue()
                        ->execute($modelClass, $qty);
                $title = 'On Queue '.$qty.' '.$modelClass;
                Notification::make()
                        ->title($title)
                        ->success()
                        ->send();
            })
            ->visible(false);
    }

    public static function getDefaultName(): ?string
    {
        return 'fake_seeder';
    }
}
