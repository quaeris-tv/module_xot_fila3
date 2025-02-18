<?php return array (
  'navigation' => 
  array (
    'name' => 'Extra',
    'plural' => 'Extra',
    'group' => 
    array (
      'name' => 'Sistema',
      'description' => 'Gestione delle funzionalità aggiuntive del sistema',
    ),
    'label' => 'extra',
    'sort' => 13,
    'icon' => 'xot-extra',
  ),
  'fields' => 
  array (
    'name' => 
    array (
      'label' => 'Nome',
      'placeholder' => 'Inserisci il nome dell\'extra',
      'help' => 'Nome identificativo dell\'extra',
    ),
    'description' => 
    array (
      'label' => 'Descrizione',
      'placeholder' => 'Inserisci una descrizione dettagliata',
      'help' => 'Descrizione completa delle funzionalità dell\'extra',
    ),
    'type' => 
    array (
      'label' => 'Tipo',
      'placeholder' => 'Seleziona il tipo di extra',
      'help' => 'Categoria funzionale dell\'extra',
      'options' => 
      array (
        'config' => 'Configurazione',
        'asset' => 'Risorsa',
        'plugin' => 'Plugin',
        'theme' => 'Tema',
        'widget' => 'Widget',
        'service' => 'Servizio',
        'integration' => 'Integrazione',
        'utility' => 'Utilità',
      ),
    ),
    'status' => 
    array (
      'label' => 'Stato',
      'help' => 'Stato attuale dell\'extra',
      'options' => 
      array (
        'active' => 'Attivo',
        'inactive' => 'Inattivo',
        'pending' => 'In attesa',
        'error' => 'Errore',
        'updating' => 'In aggiornamento',
      ),
    ),
    'version' => 
    array (
      'label' => 'Versione',
      'placeholder' => 'Inserisci la versione (es. 1.0.0)',
      'help' => 'Versione semantica dell\'extra',
    ),
    'dependencies' => 
    array (
      'label' => 'Dipendenze',
      'placeholder' => 'Seleziona le dipendenze richieste',
      'help' => 'Altri extra o componenti necessari',
    ),
    'priority' => 
    array (
      'label' => 'Priorità',
      'placeholder' => 'Seleziona la priorità',
      'help' => 'Priorità di caricamento dell\'extra',
      'options' => 
      array (
        'high' => 'Alta',
        'medium' => 'Media',
        'low' => 'Bassa',
      ),
    ),
    'settings' => 
    array (
      'label' => 'Impostazioni',
      'placeholder' => 'Configura le impostazioni',
      'help' => 'Configurazioni specifiche dell\'extra',
    ),
    'created_at' => 
    array (
      'label' => 'Data Creazione',
      'help' => 'Data e ora di creazione dell\'extra',
    ),
    'updated_at' => 
    array (
      'label' => 'Ultimo Aggiornamento',
      'help' => 'Data e ora dell\'ultima modifica',
    ),
    'id' => 
    array (
      'label' => 'id',
    ),
    'model_type' => 
    array (
      'label' => 'model_type',
    ),
    'model_id' => 
    array (
      'label' => 'model_id',
    ),
    'extra_attributes' => 
    array (
      'label' => 'extra_attributes',
    ),
    'create' => 
    array (
      'label' => 'create',
    ),
    'edit' => 
    array (
      'label' => 'edit',
    ),
    'openFilters' => 
    array (
      'label' => 'openFilters',
    ),
    'applyFilters' => 
    array (
      'label' => 'applyFilters',
    ),
    'resetFilters' => 
    array (
      'label' => 'resetFilters',
    ),
    'reorderRecords' => 
    array (
      'label' => 'reorderRecords',
    ),
    'toggleColumns' => 
    array (
      'label' => 'toggleColumns',
    ),
  ),
  'actions' => 
  array (
    'install' => 
    array (
      'label' => 'Installa',
      'success' => 'Extra installato con successo',
      'error' => 'Errore durante l\'installazione',
    ),
    'uninstall' => 
    array (
      'label' => 'Disinstalla',
      'success' => 'Extra disinstallato con successo',
      'error' => 'Errore durante la disinstallazione',
    ),
    'activate' => 
    array (
      'label' => 'Attiva',
      'success' => 'Extra attivato con successo',
      'error' => 'Errore durante l\'attivazione',
    ),
    'deactivate' => 
    array (
      'label' => 'Disattiva',
      'success' => 'Extra disattivato con successo',
      'error' => 'Errore durante la disattivazione',
    ),
    'update' => 
    array (
      'label' => 'Aggiorna',
      'success' => 'Extra aggiornato con successo',
      'error' => 'Errore durante l\'aggiornamento',
    ),
    'configure' => 
    array (
      'label' => 'Configura',
      'success' => 'Configurazione salvata con successo',
      'error' => 'Errore durante il salvataggio della configurazione',
    ),
  ),
  'messages' => 
  array (
    'validation' => 
    array (
      'name' => 
      array (
        'required' => 'Il nome è obbligatorio',
        'unique' => 'Questo nome è già in uso',
        'regex' => 'Il nome può contenere solo lettere, numeri e trattini',
      ),
      'type' => 
      array (
        'required' => 'Il tipo è obbligatorio',
        'in' => 'Il tipo selezionato non è valido',
      ),
      'version' => 
      array (
        'required' => 'La versione è obbligatoria',
        'regex' => 'Il formato della versione deve essere X.Y.Z',
      ),
      'priority' => 
      array (
        'required' => 'La priorità è obbligatoria',
        'in' => 'La priorità selezionata non è valida',
      ),
    ),
    'errors' => 
    array (
      'dependency_missing' => 'Dipendenza mancante: :name',
      'incompatible_version' => 'Versione incompatibile: :name richiede :version',
      'installation_failed' => 'Installazione fallita: :reason',
      'configuration_invalid' => 'Configurazione non valida: :reason',
      'system_incompatible' => 'Sistema incompatibile: richiede :requirement',
    ),
    'warnings' => 
    array (
      'update_available' => 'È disponibile un aggiornamento alla versione :version',
      'dependency_update' => 'La dipendenza :name richiede un aggiornamento',
      'backup_recommended' => 'Si consiglia di effettuare un backup prima di procedere',
    ),
    'info' => 
    array (
      'dependencies_ok' => 'Tutte le dipendenze sono soddisfatte',
      'configuration_valid' => 'La configurazione è valida',
      'system_compatible' => 'Il sistema è compatibile',
    ),
  ),
  'model' => 
  array (
    'label' => 'extra.model',
  ),
);