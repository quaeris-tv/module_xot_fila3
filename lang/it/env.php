<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Ambiente',
        'plural' => 'Ambiente',
        'group' => [
            'name' => 'Sistema',
<<<<<<< HEAD
            'description' => 'Gestione delle variabili d\'ambiente e configurazione del sistema',
        ],
        'label' => 'env',
        'sort' => 12,
        'icon' => 'xot-env',
    ],
    'fields' => [
        'key' => [
            'label' => 'Chiave',
            'placeholder' => 'Inserisci la chiave (es. APP_NAME)',
            'help' => 'Nome della variabile d\'ambiente in maiuscolo',
        ],
        'value' => [
            'label' => 'Valore',
            'placeholder' => 'Inserisci il valore',
            'help' => 'Valore della variabile d\'ambiente',
        ],
        'type' => [
            'label' => 'Tipo',
            'placeholder' => 'Seleziona il tipo di variabile',
            'help' => 'Tipo di dato della variabile',
            'options' => [
                'string' => 'Testo',
                'integer' => 'Numero intero',
                'float' => 'Numero decimale',
                'boolean' => 'Booleano',
                'array' => 'Array',
                'null' => 'Nullo',
            ],
        ],
        'environment' => [
            'label' => 'Ambiente',
            'placeholder' => 'Seleziona l\'ambiente di applicazione',
            'help' => 'Ambiente in cui la variabile è attiva',
            'options' => [
                'local' => 'Sviluppo locale',
                'testing' => 'Test',
                'staging' => 'Pre-produzione',
                'production' => 'Produzione',
                'all' => 'Tutti gli ambienti',
            ],
        ],
        'is_sensitive' => [
            'label' => 'Dato Sensibile',
            'help' => 'Indica se il valore contiene dati sensibili da mascherare',
        ],
        'description' => [
            'label' => 'Descrizione',
            'placeholder' => 'Inserisci una descrizione',
            'help' => 'Descrizione dettagliata dello scopo della variabile',
        ],
        'group' => [
            'label' => 'Gruppo',
            'placeholder' => 'Seleziona il gruppo',
            'help' => 'Gruppo funzionale della variabile',
            'options' => [
                'app' => 'Applicazione',
                'database' => 'Database',
                'mail' => 'Email',
                'queue' => 'Code',
                'cache' => 'Cache',
                'services' => 'Servizi',
                'other' => 'Altro',
            ],
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Nuova Variabile',
            'success' => 'Variabile d\'ambiente creata con successo',
            'error' => 'Errore durante la creazione della variabile',
        ],
        'edit' => [
            'label' => 'Modifica',
            'success' => 'Variabile d\'ambiente aggiornata con successo',
            'error' => 'Errore durante l\'aggiornamento della variabile',
        ],
        'delete' => [
            'label' => 'Elimina',
            'success' => 'Variabile d\'ambiente eliminata con successo',
            'error' => 'Errore durante l\'eliminazione della variabile',
        ],
        'backup' => [
            'label' => 'Backup',
            'success' => 'Backup del file .env creato con successo',
            'error' => 'Errore durante la creazione del backup',
        ],
        'restore' => [
            'label' => 'Ripristina',
            'success' => 'File .env ripristinato con successo',
            'error' => 'Errore durante il ripristino del file',
        ],
        'encrypt' => [
            'label' => 'Cripta',
            'success' => 'Valore criptato con successo',
            'error' => 'Errore durante la criptazione',
        ],
    ],
    'messages' => [
        'validation' => [
            'key' => [
                'required' => 'La chiave è obbligatoria',
                'unique' => 'Questa chiave è già in uso',
                'regex' => 'La chiave può contenere solo lettere maiuscole, numeri e underscore',
                'reserved' => 'Questa chiave è riservata dal sistema',
            ],
            'value' => [
                'required' => 'Il valore è obbligatorio',
                'type' => 'Il valore deve essere del tipo :type',
            ],
            'environment' => [
                'required' => 'L\'ambiente è obbligatorio',
                'exists' => 'L\'ambiente selezionato non è valido',
            ],
        ],
        'warnings' => [
            'production_edit' => 'Attenzione: stai modificando variabili d\'ambiente in produzione',
            'backup_recommended' => 'Si consiglia di effettuare un backup prima di procedere',
            'sensitive_data' => 'Attenzione: questo valore contiene dati sensibili',
            'restart_required' => 'Potrebbe essere necessario riavviare l\'applicazione',
        ],
        'info' => [
            'env_loaded' => 'File .env caricato correttamente',
            'backup_created' => 'Backup creato in :path',
            'changes_saved' => 'Modifiche salvate nel file .env',
=======
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
>>>>>>> 086d1e724c9461c982fe9b9cebfb2696790ff71e
        ],
    ],
    'title' => 'env',
];
