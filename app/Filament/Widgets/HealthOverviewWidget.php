<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Health\ResultStores\ResultStore;

class HealthOverviewWidget extends BaseWidget
{
    public function iconColor(string $status): string
    {
        return match ($status) {
            \Spatie\Health\Enums\Status::ok()->value => 'success',
            \Spatie\Health\Enums\Status::warning()->value => 'warning',
            \Spatie\Health\Enums\Status::skipped()->value => 'warning',
            \Spatie\Health\Enums\Status::failed()->value, \Spatie\Health\Enums\Status::crashed()->value => 'danger',
            default => 'secondary',
        };
    }

    protected function getStats(): array
    {
        $stats = [];

        $checkResults = app(ResultStore::class)->latestResults();
        if (null === $checkResults) {
            return $stats;
        }
        foreach ($checkResults->storedCheckResults as $result) {
            $label = $result->label;
            $value = $result->shortSummary;
            $stats[] = Stat::make($label, $value)
                ->description($result->notificationMessage.' '.$result->status)
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($this->iconColor($result->status));
        }

        /*
        return [
            Stat::make('Unique views', '192.1k'),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Average time on page', '3:12'),
        ];
        */
        return $stats;
    }
}
