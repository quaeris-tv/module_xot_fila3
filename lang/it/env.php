<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Ambiente',
        'plural' => 'Ambiente',
        'group' => [
            'name' => 'Sistema',
            'description' => 'Configurazione dell\'ambiente di sistema',
        ],
        'sort' => 10,
        'label' => 'Configurazione Ambiente',
        'icon' => 'xot-env',
    ],
    'pages' => [
        'env_editor' => [
            'title' => 'Editor Variabili Ambiente',
            'description' => 'Gestisci le variabili d\'ambiente del sistema',
            'buttons' => [
                'save' => 'Salva Modifiche',
                'refresh' => 'Aggiorna',
                'backup' => 'Backup File .env',
                'restore' => 'Ripristina Backup',
            ],
            'messages' => [
                'saved' => 'Variabili ambiente salvate con successo',
                'error' => 'Errore durante il salvataggio delle variabili ambiente',
                'backup_created' => 'Backup del file .env creato con successo',
                'backup_restored' => 'Backup del file .env ripristinato con successo',
            ],
        ],
        'sections' => [
            'app' => [
                'title' => 'Applicazione',
                'description' => 'Configurazioni base dell\'applicazione',
            ],
            'database' => [
                'title' => 'Database',
                'description' => 'Configurazioni di connessione al database',
            ],
            'mail' => [
                'title' => 'Email',
                'description' => 'Configurazioni per l\'invio delle email',
            ],
            'cache' => [
                'title' => 'Cache',
                'description' => 'Configurazioni del sistema di cache',
            ],
            'queue' => [
                'title' => 'Code',
                'description' => 'Configurazioni del sistema di code',
            ],
        ],
        'fields' => [
            'app_name' => [
                'label' => 'Nome Applicazione',
                'help' => 'Il nome della tua applicazione',
            ],
            'app_env' => [
                'label' => 'Ambiente',
                'help' => 'Ambiente di esecuzione (production, local, staging)',
            ],
            'app_debug' => [
                'label' => 'Debug Mode',
                'help' => 'Attiva/disattiva la modalità debug',
            ],
            'app_url' => [
                'label' => 'URL Applicazione',
                'help' => 'L\'URL base della tua applicazione',
            ],
            'db_connection' => [
                'label' => 'Tipo Database',
                'help' => 'Il tipo di database in uso',
            ],
            'db_host' => [
                'label' => 'Host Database',
                'help' => 'L\'indirizzo del server database',
            ],
            'db_port' => [
                'label' => 'Porta Database',
                'help' => 'La porta di connessione al database',
            ],
            'db_database' => [
                'label' => 'Nome Database',
                'help' => 'Il nome del database',
            ],
            'mail_mailer' => [
                'label' => 'Driver Email',
                'help' => 'Il sistema di invio email (smtp, sendmail, etc)',
            ],
            'mail_host' => [
                'label' => 'Host SMTP',
                'help' => 'L\'indirizzo del server SMTP',
            ],
            'cache_driver' => [
                'label' => 'Driver Cache',
                'help' => 'Il sistema di cache (file, redis, memcached)',
            ],
            'queue_connection' => [
                'label' => 'Driver Code',
                'help' => 'Il sistema di gestione code (sync, database, redis)',
            ],
        ],
        'warnings' => [
            'production' => 'Attenzione: modificare le variabili d\'ambiente in produzione può causare interruzioni del servizio',
            'backup' => 'Si consiglia di effettuare un backup prima di modificare le variabili d\'ambiente',
            'sensitive' => 'Alcune variabili contengono dati sensibili. Gestire con cautela.',
        ],
    ],
];
