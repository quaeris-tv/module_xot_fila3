<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Lock Cache',
        'plural' => 'Lock Cache',
        'group' => [
            'name' => 'Sistema',
            'description' => 'Gestione dei lock della cache',
        ],
        'sort' => 16,
        'label' => 'Lock Cache',
        'icon' => 'heroicon-o-lock-closed',
    ],
    'pages' => [
        'health_check_results' => [
            'buttons' => [
                'refresh' => 'Aggiorna',
            ],
            'heading' => 'Stato Lock Cache',
            'navigation' => [
                'group' => 'Sistema',
                'label' => 'Stato Lock Cache',
            ],
            'notifications' => [
                'check_results' => 'Risultati del controllo da',
            ],
        ],
    ],
];
