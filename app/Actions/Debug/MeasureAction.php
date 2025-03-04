<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Debug;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MeasureAction
{
    public function execute(\Closure $closure, string $label = ''): mixed
    {
        $start = microtime(true);
        $memory_start = memory_get_usage();

        $result = $closure();

        $end = microtime(true);
        $memory_end = memory_get_usage();

        $execution_time = ($end - $start) * 1000; // Convert to milliseconds
        $memory_usage = ($memory_end - $memory_start) / 1024; // Convert to KB

        $metrics = [
            'label' => $label,
            'execution_time' => round($execution_time, 2).' ms',
            'memory_usage' => round($memory_usage, 2).' KB',
            // 'peak_memory' => round(memory_get_peak_usage() / 1024 / 1024, 2).' MB',
        ];

        Notification::make()
            ->title('Performance Metrics '.$label)
            ->body($metrics['execution_time'].'  '.$metrics['memory_usage'])
            ->success()
            ->persistent()
            ->send();

        // Log::debug('Performance Metrics', $metrics);

        return $result;
    }
}
