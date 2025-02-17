<?php return array (
  'navigation' => 
  array (
    'name' => 'Modulo',
    'plural' => 'Moduli',
    'group' => 
    array (
      'name' => 'Sistema',
      'description' => 'Gestione dei moduli del sistema',
    ),
    'sort' => 83,
    'label' => 'Gestione Moduli',
    'icon' => 'xot-module',
  ),
  'fields' => 
  array (
    'name' => 
    array (
      'label' => 'Nome',
      'placeholder' => 'Nome',
    ),
    'description' => 
    array (
      'label' => 'Descrizione',
      'placeholder' => 'Descrizione',
    ),
    'is_visible' => 
    array (
      'label' => 'Visibile',
      'help' => 'Se selezionato, la pagina sarà visibile nella navigazione',
    ),
    'is_active' => 
    array (
      'label' => 'Attivo',
      'help' => 'Se selezionato, la pagina sarà attiva',
    ),
    'is_home' => 
    array (
      'label' => 'Home',
      'help' => 'Se selezionato, la pagina sarà la home',
    ),
    'status' => 
    array (
      'label' => 'Stato',
      'placeholder' => 'Stato',
    ),
    'priority' => 
    array (
      'label' => 'Priorità',
      'placeholder' => 'Priorità',
    ),
    'colors' => 
    array (
      'label' => 'Colori',
      'placeholder' => 'Colori',
    ),
    'key' => 
    array (
      'label' => 'Chiave Colore',
    ),
    'color' => 
    array (
      'label' => 'Colore',
    ),
    'value' => 
    array (
      'label' => 'Valore',
    ),
    'hex' => 
    array (
      'label' => 'Codice Hex',
    ),
    'icon' => 
    array (
      'label' => 'Icona',
      'placeholder' => 'Icona',
    ),
    'timezone' => 
    array (
      'label' => 'Fuso orario',
      'placeholder' => 'Fuso orario',
    ),
  ),
  'pages' => 
  array (
    'health_check_results' => 
    array (
      'buttons' => 
      array (
        'refresh' => 'Aggiorna',
      ),
      'heading' => 'Stato Moduli',
      'navigation' => 
      array (
        'group' => 'Sistema',
        'label' => 'Stato Moduli',
      ),
      'notifications' => 
      array (
        'check_results' => 'Risultati del controllo da',
      ),
    ),
  ),
);