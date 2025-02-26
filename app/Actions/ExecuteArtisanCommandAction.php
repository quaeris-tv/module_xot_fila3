<?php

declare(strict_types=1);

namespace Modules\Xot\Actions;

use Illuminate\Support\Facades\Cache;
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

    public function execute(string $command, string $processId): array
    {
        if (! $this->isCommandAllowed($command)) {
            throw new \RuntimeException("Comando non consentito: {$command}");
        }

        $output = [];
        $status = 'running';

        // Store process info in cache
        Cache::put("artisan.command.{$processId}", [
            'command' => $command,
            'status' => $status,
            'output' => [],
        ], now()->addHours(1));

        try {
            $process = Process::path(base_path())
                ->command("php artisan {$command}")
                ->timeout(300)
                ->start();

            // Cattura l'output in tempo reale
            while ($process->running()) {
                $data = $process->latestOutput();
                if (! empty($data)) {
                    $formattedData = trim($data);
                    if (! empty($formattedData)) {
                        $output[] = $formattedData;
                        $this->broadcastOutput($processId, $formattedData);
                        $this->updateCache($processId, $formattedData);
                    }
                }

                $errorData = $process->latestErrorOutput();
                if (! empty($errorData)) {
                    $formattedError = trim($errorData);
                    if (! empty($formattedError)) {
                        $output[] = '[ERROR] '.$formattedError;
                        $this->broadcastOutput($processId, '[ERROR] '.$formattedError, 'error');
                        $this->updateCache($processId, '[ERROR] '.$formattedError);
                    }
                }

                usleep(100000); // 100ms pause to prevent CPU overload
            }

            $result = $process->wait();

            // Capture any remaining output
            $finalOutput = trim($result->output());
            if (! empty($finalOutput)) {
                $output[] = $finalOutput;
                $this->broadcastOutput($processId, $finalOutput);
                $this->updateCache($processId, $finalOutput);
            }

            $finalErrorOutput = trim($result->errorOutput());
            if (! empty($finalErrorOutput)) {
                $output[] = '[ERROR] '.$finalErrorOutput;
                $this->broadcastOutput($processId, '[ERROR] '.$finalErrorOutput, 'error');
                $this->updateCache($processId, '[ERROR] '.$finalErrorOutput);
            }

            if ($result->successful()) {
                $status = 'completed';
                $this->broadcastOutput($processId, 'Comando completato con successo', 'completed');
            } else {
                $status = 'failed';
                $this->broadcastOutput($processId, $finalErrorOutput, 'error');
            }

            // Update final status in cache
            Cache::put("artisan.command.{$processId}", [
                'command' => $command,
                'status' => $status,
                'output' => $output,
            ], now()->addHours(1));

            return [
                'command' => $command,
                'output' => $output,
                'status' => $status,
                'exitCode' => $result->exitCode(),
            ];
        } catch (\Throwable $e) {
            $this->broadcastOutput($processId, $e->getMessage(), 'error');
            throw new \RuntimeException("Errore durante l'esecuzione del comando {$command}: {$e->getMessage()}", (int) $e->getCode(), $e);
        }
    }

    private function isCommandAllowed(string $command): bool
    {
        return in_array($command, $this->allowedCommands, true);
    }

    private function broadcastOutput(string $processId, string $output, string $type = 'output'): void
    {
        event(new CommandOutputEvent($processId, $output, $type));
    }

    private function updateCache(string $processId, string $output): void
    {
        $data = Cache::get("artisan.command.{$processId}", ['output' => []]);
        $data['output'][] = $output;
        Cache::put("artisan.command.{$processId}", $data, now()->addHours(1));
    }
}
