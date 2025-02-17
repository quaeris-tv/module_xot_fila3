<?php return array (
  'navigation' => 
  array (
    'name' => 'Comandi Artisan',
    'plural' => 'Comandi Artisan',
    'group' => 
    array (
      'name' => 'Sistema',
      'description' => 'Gestione dei comandi Artisan',
    ),
    'sort' => 28,
    'label' => 'Comandi Artisan',
    'icon' => 'heroicon-o-command-line',
  ),
  'pages' => 
  array (
    'artisan-commands' => 
    array (
      'title' => 'Gestione Comandi Artisan',
      'description' => 'Esegui e gestisci i comandi Artisan',
      'commands' => 
      array (
        'migrate' => 
        array (
          'label' => 'Migrazione Database',
          'description' => 'Esegue le migrazioni del database',
        ),
        'optimize' => 
        array (
          'label' => 'Ottimizzazione',
          'description' => 'Ottimizza le prestazioni dell\'applicazione',
        ),
        'cache' => 
        array (
          'label' => 'Gestione Cache',
          'description' => 'Comandi per la gestione della cache',
        ),
      ),
      'notifications' => 
      array (
        'success' => 'Comando eseguito con successo',
        'error' => 'Errore nell\'esecuzione del comando',
      ),
    ),
  ),
  'actions' => 
  array (
    'queue_restart' => 
    array (
      'label' => 'queue_restart',
    ),
    'event_cache' => 
    array (
      'label' => 'event_cache',
    ),
    'route_cache' => 
    array (
      'label' => 'route_cache',
    ),
  ),
  'title' => 'artisan commands manager',
);