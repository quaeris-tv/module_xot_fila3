<?php

return [
    'navigation' => [
        'name' => 'Env',
        'plural' => 'Env',
        'group' => [
            'name' => 'Admin',
        ],
        'sort' => 85,
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
