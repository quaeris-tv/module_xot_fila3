<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\IconPosition;
use Livewire\Attributes\On;
use Modules\Xot\Actions\ExecuteArtisanCommandAction;

class ArtisanCommandsManager extends Page
{
    protected static ?string $navigationIcon = 'xot::terminal';
    protected static ?string $navigationGroup = 'Sistema';

    public array $output = [];
    public string $currentCommand = '';
    public string $status = '';

    public function mount(): void
    {
        $this->authorize('manage-artisan-commands');
    }

    public static function getNavigationLabel(): string
    {
        return __('xot::filament.pages.artisan-commands-manager.navigation_label');
    }

    public function getTitle(): string
    {
        return __('xot::filament.pages.artisan-commands-manager.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('migrate')
                ->label(__('xot::filament.pages.artisan-commands-manager.commands.migrate.label'))
                ->icon('xot::database-update')
                ->color('primary')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('migrate')),

            Action::make('filament_upgrade')
                ->label(__('xot::filament.pages.artisan-commands-manager.commands.filament_upgrade.label'))
                ->icon('xot::upgrade')
                ->color('warning')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('filament:upgrade')),

            Action::make('filament_optimize')
                ->label(__('xot::filament.pages.artisan-commands-manager.commands.filament_optimize.label'))
                ->icon('xot::optimize')
                ->color('success')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('filament:optimize')),

            Action::make('view_cache')
                ->label(__('xot::cache.pages.artisan-commands.commands.view_cache.label'))
                ->icon(__('xot::cache.navigation.icons.view'))
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('view:cache')),

            Action::make('config_cache')
                ->label(__('xot::cache.pages.artisan-commands.commands.config_cache.label'))
                ->icon(__('xot::cache.navigation.icons.config'))
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('config:cache')),

            Action::make('route_cache')
                ->label(__('xot::cache.pages.artisan-commands.commands.route_cache.label'))
                ->icon(__('xot::cache.navigation.icons.route'))
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('route:cache')),

            Action::make('event_cache')
                ->label(__('xot::cache.pages.artisan-commands.commands.event_cache.label'))
                ->icon(__('xot::cache.navigation.icons.event'))
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('event:cache')),

            Action::make('queue_restart')
                ->label(__('xot::filament.pages.artisan-commands-manager.commands.queue_restart.label'))
                ->icon('xot::queue-restart')
                ->color('danger')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('queue:restart')),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make()
                ->schema([
                    ViewField::make('output')
                        ->view('xot::filament.forms.components.terminal-output')
                        ->visible(fn () => filled($this->currentCommand))
                        ->extraAttributes([
                            'class' => 'terminal-output',
                            'wire:poll.visible' => 'refreshOutput',
                        ])
                        ->state(function () {
                            $statusBadge = match ($this->status) {
                                'completed' => view('filament::components.badge')->with([
                                    'color' => 'success',
                                    'label' => __('xot::filament.pages.artisan-commands-manager.status.completed'),
                                ])->render(),
                                'failed' => view('filament::components.badge')->with([
                                    'color' => 'danger',
                                    'label' => __('xot::filament.pages.artisan-commands-manager.status.failed'),
                                ])->render(),
                                default => '<div class="animate-spin w-4 h-4"></div>',
                            };

                            $header = filled($this->currentCommand)
                                ? "<div class='flex items-center justify-between mb-4'>
                                    <h3 class='text-lg font-medium'>Comando in esecuzione: {$this->currentCommand}</h3>
                                    <div>{$statusBadge}</div>
                                  </div>"
                                : '';

                            $output = empty($this->output)
                                ? "<div class='text-gray-400'>".__('xot::filament.pages.artisan-commands-manager.status.waiting').'</div>'
                                : implode("\n", array_map(fn ($line) => "<div class='whitespace-pre-wrap'>{$line}</div>", $this->output));

                            return $header.$output;
                        }),
                ])
                ->columnSpanFull(),
        ];
    }

    public function executeCommand(string $command): void
    {
        $this->reset(['output', 'status']);
        $this->currentCommand = $command;

        app(ExecuteArtisanCommandAction::class)->execute($command);

        Notification::make()
            ->title(__('xot::filament.pages.artisan-commands-manager.messages.command_started'))
            ->body(__('xot::filament.pages.artisan-commands-manager.messages.command_started_desc', ['command' => $command]))
            ->info()
            ->send();
    }

    #[On('artisan-command.output')]
    public function handleCommandOutput(string $command, string $output): void
    {
        if ($command === $this->currentCommand) {
            $this->output[] = $output;
        }
    }

    #[On('artisan-command.completed')]
    public function handleCommandCompleted(string $command): void
    {
        if ($command === $this->currentCommand) {
            $this->status = 'completed';
            Notification::make()
                ->title(__('xot::filament.pages.artisan-commands-manager.messages.command_completed'))
                ->body(__('xot::filament.pages.artisan-commands-manager.messages.command_completed_desc', ['command' => $command]))
                ->success()
                ->send();
        }
    }

    #[On('artisan-command.failed')]
    public function handleCommandFailed(string $command, string $error): void
    {
        if ($command === $this->currentCommand) {
            $this->status = 'failed';
            $this->output[] = $error;
            Notification::make()
                ->title(__('xot::filament.pages.artisan-commands-manager.messages.command_failed'))
                ->body(__('xot::filament.pages.artisan-commands-manager.messages.command_failed_desc', ['command' => $command]))
                ->danger()
                ->send();
        }
    }

    public function refreshOutput(): void
    {
        // Questo metodo viene chiamato dal polling e aggiorna automaticamente l'output
    }
}
