<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Extra',
        'plural' => 'Extra',
        'group' => [
            'name' => 'Sistema',
            'description' => 'Funzionalità aggiuntive del sistema',
        ],
        'sort' => 59,
        'label' => 'Funzionalità Extra',
        'icon' => 'heroicon-o-puzzle-piece',
    ],
    'pages' => [
        'health_check_results' => [
            'buttons' => [
                'refresh' => 'Refresh',
            ],
            'heading' => 'Application Health',
            'navigation' => [
                'group' => 'Settings',
                'label' => 'Application Health',
            ],
            'notifications' => [
                'check_results' => 'Check results from',
            ],
        ],
    ],
];
