<?php return array (
  'resources' => 'Risorse',
  'pages' => 'Pagine',
  'widgets' => 'Widgets',
  'navigation' => 
  array (
    'name' => 'Log',
    'plural' => 'Logs',
    'group' => 
    array (
      'name' => 'Sistema',
      'description' => 'Visualizzazione dei log di sistema',
    ),
    'sort' => 35,
    'label' => 'Visualizza Log',
    'icon' => 'xot-log',
  ),
  'fields' => 
  array (
    'name' => 'Nome',
    'guard_name' => 'Guard',
    'permissions' => 'Permessi',
    'updated_at' => 'Aggiornato il',
    'first_name' => 'Nome',
    'last_name' => 'Cognome',
    'select_all' => 
    array (
      'name' => 'Seleziona Tutti',
      'message' => '',
    ),
  ),
  'actions' => 
  array (
    'import' => 
    array (
      'fields' => 
      array (
        'import_file' => 'Seleziona un file XLS o CSV da caricare',
      ),
    ),
    'export' => 
    array (
      'filename_prefix' => 'Log al',
      'columns' => 
      array (
        'name' => 'Nome log',
        'parent_name' => 'Nome log superiore',
      ),
    ),
  ),
);