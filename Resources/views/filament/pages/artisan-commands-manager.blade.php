<x-filament-panels::page>
    <x-filament::section>
        <div class="space-y-4">
            @if($currentCommand)
                <div class="p-4 space-y-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium">
                            Comando in esecuzione: {{ $currentCommand }}
                        </h3>
                        <div>
                            @if($status === 'completed')
                                <x-filament::badge color="success">
                                    Completato
                                </x-filament::badge>
                            @elseif($status === 'failed')
                                <x-filament::badge color="danger">
                                    Fallito
                                </x-filament::badge>
                            @else
                                <x-filament::loading-indicator class="w-4 h-4"/>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 font-mono text-sm bg-black text-white rounded-lg overflow-auto max-h-96">
                        @foreach($output as $line)
                            <div class="whitespace-pre-wrap">{{ $line }}</div>
                        @endforeach

                        @if(empty($output))
                            <div class="text-gray-400">In attesa dell'output...</div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center text-gray-500 dark:text-gray-400">
                    <p>Seleziona un comando da eseguire utilizzando i pulsanti qui sopra.</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-panels::page> 