<?php

declare(strict_types=1);

namespace Modules\Xot\Actions;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Process;
use Spatie\QueueableAction\QueueableAction;

class ExecuteArtisanCommandAction
{
    use QueueableAction;

    private array $allowedCommands = [
        'migrate',
        'filament:upgrade',
        'filament:optimize',
        'view:cache',
        'config:cache',
        'route:cache',
        'event:cache',
        'queue:restart',
    ];

    public function execute(string $command): array
    {
        if (! $this->isCommandAllowed($command)) {
            throw new \RuntimeException("Comando non consentito: {$command}");
        }

        $process = Process::path(base_path())
            ->command("php artisan {$command}")
            ->timeout(300)
            ->start();

        $output = [];
        $status = 'running';

        foreach ($process->output() as $type => $data) {
            $output[] = $data;
            Event::dispatch('artisan-command.output', [$command, $data]);
        }

        $result = $process->wait();

        if ($result->successful()) {
            $status = 'completed';
            Event::dispatch('artisan-command.completed', [$command]);
        } else {
            $status = 'failed';
            Event::dispatch('artisan-command.failed', [$command, $result->errorOutput()]);
        }

        return [
            'command' => $command,
            'output' => $output,
            'status' => $status,
            'exitCode' => $result->exitCode(),
        ];
    }

    private function isCommandAllowed(string $command): bool
    {
        return in_array($command, $this->allowedCommands, true);
    }
} 