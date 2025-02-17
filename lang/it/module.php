<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Modulo',
        'plural' => 'Moduli',
        'group' => [
            'name' => 'Sistema',
            'description' => 'Gestione dei moduli del sistema',
        ],
        'sort' => 67,
        'label' => 'Gestione Moduli',
        'icon' => 'xot-module',
    ],
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Nome',
        ],
        'description' => [
            'label' => 'Descrizione',
            'placeholder' => 'Descrizione',
        ],
        'is_visible' => [
            'label' => 'Visibile',
            'help' => 'Se selezionato, la pagina sarà visibile nella navigazione',
        ],
        'is_active' => [
            'label' => 'Attivo',
            'help' => 'Se selezionato, la pagina sarà attiva',
        ],
        'is_home' => [
            'label' => 'Home',
            'help' => 'Se selezionato, la pagina sarà la home',
        ],
        'status' => [
            'label' => 'Stato',
            'placeholder' => 'Stato',
        ],
        'priority' => [
            'label' => 'Priorità',
            'placeholder' => 'Priorità',
        ],
        'colors' => [
            'label' => 'Colori',
            'placeholder' => 'Colori',
        ],
        'key' => [
            'label' => 'Chiave Colore',
        ],
        'color' => [
            'label' => 'Colore',
        ],
        'value' => [
            'label' => 'Valore',
        ],
        'hex' => [
            'label' => 'Codice Hex',
        ],
        'icon' => [
            'label' => 'Icona',
            'placeholder' => 'Icona',
        ],
        'timezone' => [
            'label' => 'Fuso orario',
            'placeholder' => 'Fuso orario',
        ],
    ],
    'pages' => [
        'health_check_results' => [
            'buttons' => [
                'refresh' => 'Aggiorna',
            ],
            'heading' => 'Stato Moduli',
            'navigation' => [
                'group' => 'Sistema',
                'label' => 'Stato Moduli',
            ],
            'notifications' => [
                'check_results' => 'Risultati del controllo da',
            ],
        ],
    ],
];
