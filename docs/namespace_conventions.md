# Convenzioni per i Namespace nei Moduli

## Struttura Base
I namespace nei moduli di OrisBroker Framework devono seguire una struttura precisa per mantenere la coerenza del codebase.

### Regola Fondamentale
Il namespace base di ogni modulo è `Modules\{ModuleName}`. È importante notare che **NON** si deve includere `app` nel namespace.

### Esempi Corretti
```php
namespace Modules\Xot\Console\Commands;
namespace Modules\Broker\Models;
namespace Modules\User\Services;
namespace Modules\Tenant\Repositories;
```

### Esempi Errati
```php
namespace Modules\Xot\Console\Commands;
namespace Modules\Broker\app\Models;
namespace Modules\User\app\Services;
namespace Modules\Tenant\app\Repositories;
```

## Struttura delle Directory
Anche se i file possono essere fisicamente collocati in una directory `app/`, il namespace non deve riflettere questa struttura.

### Esempio di Struttura Directory
```
Modules/
  Xot/
    app/
      Console/
        Commands/
          ImportMdbToMySQL.php  // namespace Modules\Xot\Console\Commands;
    Models/
    Services/
    Repositories/
```

## Motivazione
Questa convenzione:
- Mantiene i namespace puliti e coerenti
- Evita confusione con la struttura delle directory
- Facilita l'autoloading e la navigazione del codice
- Segue le best practice di Laravel per i moduli

## Note Importanti
- Questa convenzione si applica a TUTTI i moduli del framework
- Non ci sono eccezioni a questa regola
- I file possono essere fisicamente in `app/` ma il namespace non deve rifletterlo
- Questa convenzione è obbligatoria per mantenere la compatibilità con il framework
