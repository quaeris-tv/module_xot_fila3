<?php return array (
  'navigation' => 
  array (
    'name' => 'Stato Sistema',
    'plural' => 'Stati Sistema',
    'group' => 
    array (
      'name' => 'Sistema',
      'description' => 'Monitoraggio dello stato del sistema',
    ),
    'sort' => 11,
    'label' => 'Monitoraggio Sistema',
    'icon' => 'xot-health',
  ),
  'fields' => 
  array (
    'name' => 'Nome',
    'status' => 'Stato',
    'notification_message' => 'Messaggio di notifica',
    'last_checked' => 'Ultimo controllo',
    'check_time' => 'Tempo di controllo',
    'check_result' => 'Risultato',
    'check_status' => 'Stato controllo',
    'check_message' => 'Messaggio controllo',
  ),
  'buttons' => 
  array (
    'check_health' => 'Controlla stato',
    'refresh' => 'Aggiorna',
  ),
  'messages' => 
  array (
    'checking' => 'Controllo in corso...',
    'check_complete' => 'Controllo completato',
    'system_healthy' => 'Il sistema è in buono stato',
    'system_issues' => 'Sono stati rilevati problemi nel sistema',
  ),
  'statuses' => 
  array (
    'ok' => 'OK',
    'warning' => 'Attenzione',
    'critical' => 'Critico',
    'unknown' => 'Sconosciuto',
  ),
);
