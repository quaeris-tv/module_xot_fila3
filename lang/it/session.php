<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Sessioni',
        'plural' => 'Sessioni',
        'group' => [
            'name' => 'Sistema',
            'description' => 'Gestione delle sessioni utente',
        ],
        'sort' => 100,
        'label' => 'Gestione Sessioni',
        'icon' => 'heroicon-o-user-circle',
    ],
    'pages' => [
        'health_check_results' => [
            'buttons' => [
                'refresh' => 'Aggiorna',
            ],
            'heading' => 'Stato Sessioni',
            'navigation' => [
                'group' => 'Sistema',
                'label' => 'Stato Sessioni',
            ],
            'notifications' => [
                'check_results' => 'Risultati del controllo da',
            ],
        ],
    ],
];
