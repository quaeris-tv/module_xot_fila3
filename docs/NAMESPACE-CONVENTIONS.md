# Convenzioni per i Namespace nei Moduli Laraxot

Questo documento definisce le convenzioni per i namespace nei moduli del framework Laraxot PTVX, un aspetto fondamentale per garantire la compatibilità con PHPStan livello 9 e la coerenza del codice.

## Regola Fondamentale: Omettere "app" nel Namespace

Anche se i file sono fisicamente collocati nella directory `app` del modulo, il namespace **NON** deve includere questo segmento.

### ✅ CORRETTO
```php
namespace Modules\Rating\Models;
namespace Modules\Rating\Http\Controllers;
namespace Modules\Rating\Providers;
namespace Modules\Rating\Datas;
namespace Modules\Rating\Actions;
namespace Modules\Rating\Console\Commands;
```

### ❌ ERRATO
```php
namespace Modules\Rating\App\Models;
namespace Modules\Rating\App\Http\Controllers;
namespace Modules\Rating\App\Providers;
namespace Modules\Rating\App\Datas;
namespace Modules\Rating\App\Actions;
namespace Modules\Rating\App\Console\Commands;
```

## Attenzione: Errore comune con il namespace delle Actions

Un errore particolarmente frequente riguarda le Actions. La convenzione corretta è la seguente:

- ✅ **CORRETTO**: `namespace Modules\Xot\Actions;`
- ❌ **ERRATO**: `namespace Modules\Xot\app\Actions;`

Anche se il file si trova nel percorso fisico `Modules/Xot/app/Actions/`, il namespace non deve mai includere il segmento `app`.

Questo errore causa spesso problemi di PHPStan come:
```
Class 'Modules\Xot\app\Actions\MyAction' not found.
```

La correzione è sempre la stessa: rimuovere il segmento `app` dal namespace.

## Struttura Completa dei Namespace per Componenti Comuni

### Modelli

```php
<?php

declare(strict_types=1);

namespace Modules\Rating\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    // Implementazione
}
```

### Controller

```php
<?php

declare(strict_types=1);

namespace Modules\Rating\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RatingController extends Controller
{
    // Implementazione
}
```

### Data Objects

```php
<?php

declare(strict_types=1);

namespace Modules\Rating\Datas;

use Spatie\LaravelData\Data;

class RatingData extends Data
{
    // Implementazione
}
```

### Actions

```php
<?php

declare(strict_types=1);

namespace Modules\Rating\Actions;

use Spatie\QueueableAction\QueueableAction;

class CreateRatingAction
{
    use QueueableAction;
    
    // Implementazione
}
```

### Console Commands

```php
<?php

declare(strict_types=1);

namespace Modules\Rating\Console\Commands;

use Illuminate\Console\Command;

class RatingCommand extends Command
{
    protected $signature = 'rating:process';
    
    // Implementazione
}
```

### Service Providers

```php
<?php

declare(strict_types=1);

namespace Modules\Rating\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class RatingServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Rating';
    protected string $module_dir = __DIR__;
    protected string $module_ns = __NAMESPACE__;
    
    // Implementazione
}
```

### Route Service Providers

```php
<?php

declare(strict_types=1);

namespace Modules\Rating\Providers;

use Modules\Xot\Providers\XotBaseRouteServiceProvider;

class RouteServiceProvider extends XotBaseRouteServiceProvider 
{
    protected string $moduleNamespace = 'Modules\Rating\Http\Controllers';
    protected string $module_dir = __DIR__;
    protected string $module_ns = __NAMESPACE__;
    public string $name = 'Rating';
    
    // Implementazione
}
```

## Corrispondenza tra Struttura delle Directory e Namespace

| Directory fisica                             | Namespace corretto                   |
|---------------------------------------------|-------------------------------------|
| `Modules/Rating/app/Models/`                | `Modules\Rating\Models`             |
| `Modules/Rating/app/Http/Controllers/`      | `Modules\Rating\Http\Controllers`   |
| `Modules/Rating/app/Providers/`             | `Modules\Rating\Providers`          |
| `Modules/Rating/app/Datas/`                 | `Modules\Rating\Datas`              |
| `Modules/Rating/app/Actions/`               | `Modules\Rating\Actions`            |
| `Modules/Rating/app/Console/Commands/`      | `Modules\Rating\Console\Commands`   |
| `Modules/Rating/app/Filament/Resources/`    | `Modules\Rating\Filament\Resources` |
| `Modules/Rating/app/Filament/Pages/`        | `Modules\Rating\Filament\Pages`     |

## Namespace nei Moduli con Sottodirectory

Per moduli con strutture più complesse che utilizzano sottodirectory, mantenere la coerenza dei namespace:

```php
// File fisico: Modules/Rating/app/Models/Concerns/HasRatings.php
namespace Modules\Rating\Models\Concerns;

// File fisico: Modules/Rating/app/Http/Controllers/Api/RatingController.php
namespace Modules\Rating\Http\Controllers\Api;

// File fisico: Modules/Rating/app/Console/Commands/Generators/MakeRatingCommand.php
namespace Modules\Rating\Console\Commands\Generators;
```

## Import e Use Statements

Utilizzare sempre import completi e qualificati per evitare ambiguità:

```php
// CORRETTO
use Modules\Rating\Models\Rating;
use Modules\User\Models\User;

// EVITARE
use Modules\Rating\Models\Rating as RatingModel;
```

## Namespace in composer.json

Quando si definisce l'autoloading in `composer.json`, assicurarsi che la mappatura rifletta questa convenzione:

```json
"autoload": {
    "psr-4": {
        "Modules\\Rating\\": "Modules/Rating/app/"
    }
}
```

## Risoluzione dei Problemi PHPStan con i Namespace

I problemi PHPStan relativi ai namespace possono essere identificati da messaggi come:

```
Class Modules\Rating\App\Models\Rating not found.
```

La soluzione è sempre correggere il namespace rimuovendo il segmento `App`:

```php
// Da
namespace Modules\Rating\App\Models;

// A
namespace Modules\Rating\Models;
```

Per i comandi console, un errore comune è:

```
Class Modules\Rating\App\Console\Commands\RatingCommand not found.
```

La correzione è:

```php
// Da
namespace Modules\Rating\App\Console\Commands;

// A
namespace Modules\Rating\Console\Commands;
```

## Vantaggi di questa Convenzione

1. **Coerenza**: Uniformità in tutto il codebase
2. **Compatibilità PHPStan**: Evita errori di classe non trovata
3. **Semplicità**: Namespace più brevi e leggibili
4. **Riflettività**: Il namespace riflette la struttura logica del modulo, non la sua struttura fisica
5. **Standard Laravel**: Allineato alle convenzioni di Laravel

Seguire queste convenzioni di namespace aiuterà a mantenere un codebase coerente e a evitare errori comuni durante l'analisi statica del codice con PHPStan. 