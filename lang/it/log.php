<?php

declare(strict_types=1);

return [
    'resources' => 'Risorse',
    'pages' => 'Pagine',
    'widgets' => 'Widgets',
    'navigation' => [
        'name' => 'Log',
        'plural' => 'Logs',
        'group' => [
            'name' => 'Sistema',
            'description' => 'Visualizzazione dei log di sistema',
        ],
        'sort' => 91,
        'label' => 'Visualizza Log',
        'icon' => 'xot-log',
    ],
    'fields' => [
        'name' => 'Nome',
        'guard_name' => 'Guard',
        'permissions' => 'Permessi',
        'updated_at' => 'Aggiornato il',
        'first_name' => 'Nome',
        'last_name' => 'Cognome',
        'select_all' => [
            'name' => 'Seleziona Tutti',
            'message' => '',
        ],
    ],
    'actions' => [
        'import' => [
            'fields' => [
                'import_file' => 'Seleziona un file XLS o CSV da caricare',
            ],
        ],
        'export' => [
            'filename_prefix' => 'Log al',
            'columns' => [
                'name' => 'Nome log',
                'parent_name' => 'Nome log superiore',
            ],
        ],
    ],
];
