# Struttura del Modulo Xot

## Struttura delle Directory

La struttura base del modulo deve seguire questo pattern:

```
Modules/Xot/
├── app/
│   ├── Exceptions/        # Tutte le eccezioni e i gestori di eccezioni vanno qui
│   │   ├── Handlers/     # Gestori di eccezioni personalizzati
│   │   ├── Formatters/   # Formattatori di eccezioni
│   │   └── ...          # Altre classi di eccezioni
│   ├── Providers/        # Service providers
│   └── ...              # Altri namespace
├── config/              # File di configurazione
├── database/           # Migrations, factories, seeders
├── resources/         # Views, lang, assets
└── tests/            # Test files
```

## Regole Importanti

1. **Posizione delle Eccezioni**
   - TUTTE le eccezioni e i gestori di eccezioni DEVONO essere posizionati in `app/Exceptions/`
   - NON posizionare mai le eccezioni in `Modules/Xot/Exceptions/`
   - Il namespace corretto è `Modules\Xot\Exceptions\*`

2. **Struttura dei Namespace**
   - Il namespace base è `Modules\Xot`
   - Le classi in `app/` mantengono lo stesso namespace senza includere "app" nel path
   - Esempio: Un file in `app/Exceptions/Handler.php` avrà namespace `Modules\Xot\Exceptions`

3. **Best Practices**
   - Mantenere una struttura di directory pulita e organizzata
   - Seguire le convenzioni di Laravel per la struttura delle directory
   - Utilizzare i namespace appropriati che riflettono la struttura delle directory 