<?php
/**
 * @see https://github.com/shuvroroy/filament-spatie-laravel-health/tree/main
 */

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\UsedDiskSpaceCheck;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Facades\Health;
use Spatie\Health\ResultStores\ResultStore;

class HealthPage extends Page
{
    use NavigationLabelTrait;

    /**
     * @var array<string, string>
     */
    protected $listeners = ['refresh-component' => '$refresh'];

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static string $view = 'xot::filament.pages.health';

    protected function getActions(): array
    {
        return [
            Action::make('refresh')
                ->label('')
                ->tooltip('refresh')
                ->icon('heroicon-o-arrow-path')
                ->button()
                ->action('refresh'),
        ];
    }

    protected function getViewData(): array
    {
        $checkResults = app(ResultStore::class)->latestResults();

        return [
            'lastRanAt' => new Carbon($checkResults?->finishedAt),
            'checkResults' => $checkResults,
        ];
    }

    public function refresh(): void
    {
        Artisan::call(RunHealthChecksCommand::class);

        $res = Health::checks([
            UsedDiskSpaceCheck::new(),
            DatabaseCheck::new(),
        ]);

        $this->dispatch('refresh-component');

        Notification::make()
            ->title('Health check results refreshed')
            ->success()
            ->send();
    }
}
