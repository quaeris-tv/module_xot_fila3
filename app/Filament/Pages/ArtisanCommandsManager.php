<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Modules\Xot\Actions\ExecuteArtisanCommandAction;

/**
 * ---.
 */
class ArtisanCommandsManager extends XotBasePage
{
    public array $output = [];
    public string $currentCommand = '';
    public string $status = '';
    public bool $isRunning = false;
    public ?string $processId = null;

    protected $listeners = [
        'echo:private-command-output,CommandOutput' => 'handleBroadcastOutput',
        'refresh-component' => '$refresh',
    ];

    public function getPollingInterval(): ?string
    {
        return $this->isRunning ? '1s' : null;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('migrate')
                ->label(__('xot::artisan-commands-manager.commands.migrate.label'))
                ->icon('heroicon-o-circle-stack')
                ->color('primary')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->disabled(fn () => $this->isRunning)
                ->action(fn () => $this->executeCommand('migrate')),

            Action::make('filament_upgrade')
                ->label(__('xot::artisan-commands-manager.commands.filament_upgrade.label'))
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->disabled(fn () => $this->isRunning)
                ->action(fn () => $this->executeCommand('filament:upgrade')),

            Action::make('filament_optimize')
                ->label(__('xot::artisan-commands-manager.commands.filament_optimize.label'))
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->disabled(fn () => $this->isRunning)
                ->action(fn () => $this->executeCommand('filament:optimize')),

            Action::make('view_cache')
                ->label(__('xot::artisan-commands-manager.commands.view_cache.label'))
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->disabled(fn () => $this->isRunning)
                ->action(fn () => $this->executeCommand('view:cache')),

            Action::make('config_cache')
                ->label(__('xot::artisan-commands-manager.commands.config_cache.label'))
                ->icon('heroicon-o-cog-6-tooth')
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->disabled(fn () => $this->isRunning)
                ->action(fn () => $this->executeCommand('config:cache')),

            Action::make('route_cache')
                ->label(__('xot::artisan-commands-manager.commands.route_cache.label'))
                ->icon('heroicon-o-map')
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->disabled(fn () => $this->isRunning)
                ->action(fn () => $this->executeCommand('route:cache')),

            Action::make('event_cache')
                ->label(__('xot::artisan-commands-manager.commands.event_cache.label'))
                ->icon('heroicon-o-bell')
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->disabled(fn () => $this->isRunning)
                ->action(fn () => $this->executeCommand('event:cache')),

            Action::make('queue_restart')
                ->label(__('xot::artisan-commands-manager.commands.queue_restart.label'))
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->disabled(fn () => $this->isRunning)
                ->action(fn () => $this->executeCommand('queue:restart')),
        ];
    }

    public function executeCommand(string $command): void
    {
        $this->reset(['output', 'status']);
        $this->currentCommand = $command;
        $this->isRunning = true;

        try {
            $this->processId = uniqid('cmd_');
            app(ExecuteArtisanCommandAction::class)->execute($command, $this->processId);
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('xot::artisan-commands-manager.notifications.error'))
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->isRunning = false;
        }
    }

    public function getListeners()
    {
        return array_merge(parent::getListeners(), [
            "echo-private:command.{$this->processId},CommandOutput" => 'handleRealTimeOutput',
        ]);
    }

    public function handleRealTimeOutput($event)
    {
        if ($event['processId'] === $this->processId) {
            $this->output[] = $event['output'];

            if ('completed' === $event['type']) {
                $this->isRunning = false;
                $this->status = 'completed';
                Notification::make()
                    ->title(__('xot::artisan-commands-manager.notifications.success'))
                    ->success()
                    ->send();
            } elseif ('error' === $event['type']) {
                $this->isRunning = false;
                $this->status = 'failed';
                Notification::make()
                    ->title(__('xot::artisan-commands-manager.notifications.error'))
                    ->body($event['output'])
                    ->danger()
                    ->send();
            }
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('xot::filament.pages.artisan-commands-manager', [
            'output' => $this->output,
            'isRunning' => $this->isRunning,
            'currentCommand' => $this->currentCommand,
        ]);
    }
}
