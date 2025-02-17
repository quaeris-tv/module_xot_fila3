<?php

declare(strict_types=1);

return [
    'resources' => 'Risorse',
    'pages' => 'Pagine',
    'widgets' => 'Widgets',
    'navigation' => [
        'name' => 'Metatag',
        'plural' => 'Metatag',
        'group' => [
            'name' => 'Sistema',
            'description' => 'Gestione dei meta tag del sito',
        ],
        'sort' => 55,
        'label' => 'Gestione Metatag',
        'icon' => 'heroicon-o-tag',
    ],
    'fields' => [
        'name' => 'Nome',
        'title' => [
            'label' => 'Titolo',
            'placeholder' => 'Titolo',
        ],
        'description' => [
            'label' => 'Descrizione',
            'placeholder' => 'Descrizione',
        ],
        'url' => [
            'label' => 'URL',
            'placeholder' => 'URL',
        ],
    ],
    'actions' => [
        'import' => [
            'fields' => [
                'import_file' => 'Seleziona un file XLS o CSV da caricare',
            ],
        ],
    ],
];
