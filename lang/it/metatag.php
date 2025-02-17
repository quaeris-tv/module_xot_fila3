<?php return array (
  'resources' => 'Risorse',
  'pages' => 'Pagine',
  'widgets' => 'Widgets',
  'navigation' => 
  array (
    'name' => 'Metatag',
    'plural' => 'Metatag',
    'group' => 
    array (
      'name' => 'Sistema',
      'description' => 'Gestione dei meta tag del sito',
    ),
    'sort' => 4,
    'label' => 'Gestione Metatag',
    'icon' => 'heroicon-o-tag',
  ),
  'fields' => 
  array (
    'name' => 'Nome',
    'title' => 
    array (
      'label' => 'Titolo',
      'placeholder' => 'Titolo',
    ),
    'description' => 
    array (
      'label' => 'Descrizione',
      'placeholder' => 'Descrizione',
    ),
    'url' => 
    array (
      'label' => 'URL',
      'placeholder' => 'URL',
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
  ),
);