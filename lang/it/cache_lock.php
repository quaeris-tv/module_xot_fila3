<?php return array (
  'navigation' => 
  array (
    'name' => 'Lock Cache',
    'plural' => 'Lock Cache',
    'group' => 
    array (
      'name' => 'Sistema',
      'description' => 'Gestione del sistema e delle sue risorse',
    ),
    'label' => 'locks',
    'sort' => 11,
    'icon' => 'xot-lock',
  ),
  'fields' => 
  array (
    'key' => 
    array (
      'label' => 'Chiave',
      'placeholder' => 'Inserisci la chiave del lock',
      'help' => 'Chiave univoca identificativa del lock',
    ),
    'owner' => 
    array (
      'label' => 'Proprietario',
      'placeholder' => 'Inserisci il proprietario del lock',
      'help' => 'Processo o utente proprietario del lock',
    ),
    'expiration' => 
    array (
      'label' => 'Scadenza',
      'placeholder' => 'Seleziona la data e ora di scadenza',
      'help' => 'Momento in cui il lock scadrà automaticamente',
    ),
    'status' => 
    array (
      'label' => 'Stato',
      'help' => 'Stato attuale del lock nel sistema',
      'options' => 
      array (
        'locked' => 'Bloccato',
        'unlocked' => 'Sbloccato',
        'expired' => 'Scaduto',
        'pending' => 'In Attesa',
        'error' => 'Errore',
      ),
    ),
    'created_at' => 
    array (
      'label' => 'Data Creazione',
      'help' => 'Data e ora di creazione del lock',
    ),
    'updated_at' => 
    array (
      'label' => 'Ultimo Aggiornamento',
      'help' => 'Data e ora dell\'ultima modifica del lock',
    ),
    'description' => 
    array (
      'label' => 'Descrizione',
      'placeholder' => 'Inserisci una descrizione',
      'help' => 'Descrizione opzionale del lock',
    ),
    'type' => 
    array (
      'label' => 'Tipo Lock',
      'placeholder' => 'Seleziona il tipo',
      'help' => 'Tipologia del lock',
      'options' => 
      array (
        'file' => 'File',
        'process' => 'Processo',
        'resource' => 'Risorsa',
        'custom' => 'Personalizzato',
      ),
    ),
    'create' => 
    array (
      'label' => 'create',
    ),
    'view' => 
    array (
      'label' => 'view',
    ),
    'edit' => 
    array (
      'label' => 'edit',
    ),
    'delete' => 
    array (
      'label' => 'delete',
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
    'lock' => 
    array (
      'label' => 'Blocca',
      'success' => 'Lock creato con successo',
      'error' => 'Errore durante la creazione del lock',
    ),
    'unlock' => 
    array (
      'label' => 'Sblocca',
      'success' => 'Lock rimosso con successo',
      'error' => 'Errore durante la rimozione del lock',
    ),
    'refresh' => 
    array (
      'label' => 'Aggiorna',
      'success' => 'Lock aggiornato con successo',
      'error' => 'Errore durante l\'aggiornamento del lock',
    ),
    'force_unlock' => 
    array (
      'label' => 'Forza Sblocco',
      'success' => 'Lock forzatamente rimosso',
      'error' => 'Impossibile forzare la rimozione del lock',
    ),
    'extend' => 
    array (
      'label' => 'Estendi',
      'success' => 'Scadenza del lock estesa con successo',
      'error' => 'Impossibile estendere la scadenza del lock',
    ),
  ),
  'messages' => 
  array (
    'validation' => 
    array (
      'key' => 
      array (
        'required' => 'La chiave è obbligatoria',
        'unique' => 'Questa chiave è già in uso',
        'regex' => 'La chiave può contenere solo lettere, numeri e trattini',
      ),
      'owner' => 
      array (
        'required' => 'Il proprietario è obbligatorio',
        'exists' => 'Il proprietario specificato non esiste',
      ),
      'expiration' => 
      array (
        'required' => 'La scadenza è obbligatoria',
        'date' => 'La data di scadenza non è valida',
        'after' => 'La data di scadenza deve essere successiva a ora',
        'before' => 'La data di scadenza non può superare 24 ore',
      ),
      'type' => 
      array (
        'required' => 'Il tipo di lock è obbligatorio',
        'in' => 'Il tipo selezionato non è valido',
      ),
    ),
    'errors' => 
    array (
      'already_locked' => 'Questa risorsa è già bloccata',
      'not_locked' => 'Questa risorsa non è bloccata',
      'not_owner' => 'Non sei il proprietario di questo lock',
      'expired' => 'Il lock è scaduto',
      'system_error' => 'Errore di sistema durante l\'operazione',
    ),
    'info' => 
    array (
      'auto_unlock' => 'Il lock verrà automaticamente rimosso alla scadenza',
      'force_required' => 'Potrebbe essere necessario forzare lo sblocco',
      'extend_available' => 'È possibile estendere la durata del lock',
    ),
  ),
  'model' => 
  array (
    'label' => 'cache lock.model',
  ),
);