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
    protected static ?string $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $navigationLabel = 'Gestione Artisan';

    protected static ?string $title = 'Gestione Comandi Artisan';

    protected static ?int $navigationSort = 100;

    public array $output = [];

    public string $currentCommand = '';

    public string $status = '';

    public function mount(): void
    {
        $this->authorize('manage-artisan-commands');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('migrate')
                ->label('Migrate Database')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('migrate')),

            Action::make('filament_upgrade')
                ->label('Upgrade Filament')
                ->icon('heroicon-o-arrow-up-circle')
                ->color('warning')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('filament:upgrade')),

            Action::make('filament_optimize')
                ->label('Optimize Filament')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('filament:optimize')),

            Action::make('view_cache')
                ->label('Cache Views')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('view:cache')),

            Action::make('config_cache')
                ->label('Cache Config')
                ->icon('heroicon-o-cog')
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('config:cache')),

            Action::make('route_cache')
                ->label('Cache Routes')
                ->icon('heroicon-o-map')
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('route:cache')),

            Action::make('event_cache')
                ->label('Cache Events')
                ->icon('heroicon-o-bell')
                ->color('gray')
                ->size('lg')
                ->iconPosition(IconPosition::Before)
                ->action(fn () => $this->executeCommand('event:cache')),

            Action::make('queue_restart')
                ->label('Restart Queue')
                ->icon('heroicon-o-arrow-path-rounded-square')
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
                                    'label' => 'Completato',
                                ])->render(),
                                'failed' => view('filament::components.badge')->with([
                                    'color' => 'danger',
                                    'label' => 'Fallito',
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
                                ? "<div class='text-gray-400'>In attesa dell'output...</div>"
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
            ->title('Comando avviato')
            ->body("Esecuzione di: {$command}")
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
                ->title('Comando completato')
                ->body("Il comando {$command} è stato eseguito con successo")
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
                ->title('Errore')
                ->body("Il comando {$command} è fallito")
                ->danger()
                ->send();
        }
    }

    public function refreshOutput(): void
    {
        // Questo metodo viene chiamato dal polling e aggiorna automaticamente l'output
    }
}
