<?php

return [
    'navigation' => [
        'name' => 'extra',
        'plural' => 'estras',
        'group' => [
            'name' => 'Admin',
        ],
        'sort' => 98,
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
