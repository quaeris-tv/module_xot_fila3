<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'cache',
        'plural' => 'cache',
        'group' => [
            'name' => 'Admin',
        ],
        'icons' => [
            'view' => 'xot::view-cache',
            'config' => 'xot::config-cache',
            'route' => 'xot::route-cache',
            'event' => 'xot::event-cache',
        ],
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
        'artisan-commands' => [
            'commands' => [
                'view_cache' => [
                    'label' => 'Cache Views',
                    'description' => 'Genera la cache delle viste',
                ],
                'config_cache' => [
                    'label' => 'Cache Config',
                    'description' => 'Genera la cache della configurazione',
                ],
                'route_cache' => [
                    'label' => 'Cache Routes',
                    'description' => 'Genera la cache delle route',
                ],
                'event_cache' => [
                    'label' => 'Cache Events',
                    'description' => 'Genera la cache degli eventi',
                ],
            ],
        ],
    ],
];
