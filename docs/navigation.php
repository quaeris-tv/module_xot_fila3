<?php

declare(strict_types=1);

return [
    /*
    'Getting Started' => [
        'url' => 'docs/getting-started',
        'children' => [
            'Customizing Your Site' => 'docs/customizing-your-site',
            'Navigation' => 'docs/navigation',
            'Algolia DocSearch' => 'docs/algolia-docsearch',
            'Custom 404 Page' => 'docs/custom-404-page',
        ],
    ],
    'Jigsaw Docs' => 'https://jigsaw.tighten.co/docs/installation',
    */
    'Base' => [
        'url' => '#',
        'children' => [
            'Installazione' => 'docs/base/installation',
            'Errori Comuni' => 'docs/base/issues',
            'Struttura' => 'docs/base/structure',
            'Url Not Found' => 'docs/base/url-not-found',
        ],
    ],
    'Tools controllo codice' => [
        // 'url' => 'docs/tools/code-check',
        'children' => [
            'Duplicazione del codice' => 'docs/tools/code-duplication',
        ],
    ],
    'Push automatico in hostinger' => [
        'url' => 'docs/automatic-push',
    ],
    'Config' => [
        'url' => '#',
        'children' => [
            'Configurazione modules.php' => 'docs/config/modules',
        ],
    ],
    'Model Actions' => [
        'url' => 'docs/model',
        'children' => [
            'Destroy' => 'docs/model/action/destroy',
            'Detach' => 'docs/model/action/detach',
            'Filter Relations' => 'docs/model/action/filter-relations',
            'Store' => 'docs/model/action/store',
            'Update' => 'docs/model/action/update',
        ],
    ],
    'Services' => [
        'url' => 'docs/service',
        'children' => [
            'Model' => 'docs/service/model',
            'Panel' => 'docs/service/panel',
            'Profile' => 'docs/service/profile',
        ],
    ],
    'Custom Relation' => 'docs/custom-relation',
    'Links' => [
        'url' => '#',
        'children' => [
            'Filter' => 'docs/links/filter',
        ],
    ],
    'No Console' => [
        'url' => 'docs/no_console',
        'children' => [
            'Eseguire comandi su un modulo' => 'docs/no_console/on-module',
        ],
    ],
    'Pacchetti utilizzati / composer.json' => [
        'url' => 'docs/package',
        // 'children' => [
        //     'https://github.com/staudenmeir/laravel-adjacency-list' => 'docs/package/staudenmeir/laravel-adjacency-list',
        // ],
    ],
    'Activity Log' => [
        'url' => 'docs/activity/configurazione',
        'children' => [
            'Event Sourcing' => 'docs/activity/event-sourcing',
            'Proiettori' => 'docs/activity/proiettori',
            'Aggregati' => 'docs/activity/aggregati',
            'Reattori' => 'docs/activity/reattori',
        ],
    ],
    'Filament' => [
        // 'url' => 'docs/filament',
        'children' => [
            'Installazione' => 'docs/filament/installation',
            'Moduli' => 'docs/filament/modules',
            'Creazione di una Resource' => 'docs/filament/resource',
            'Nested Resource' => 'docs/filament/nested-resource',
            'Creazioni Pdf da azione' => 'docs/filament/actions/pdf',
            'Ripristinare cartella vendor' => 'docs/filament/vendor',
        ],
    ],
    // 'Documentazione jigsaw' =>
];
