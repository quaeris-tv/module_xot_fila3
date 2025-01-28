# Laraxot Framework

## Panoramica
Laraxot è un framework basato su Laravel che fornisce funzionalità estese per lo sviluppo di applicazioni web modulari.

## Caratteristiche principali
- Sistema modulare
- Gestione avanzata dei temi
- Integrazione con pannello amministrativo
- Sistema di permessi e ruoli
- API RESTful integrate

## Struttura dei moduli

## Note Generiche Laravel/Xot

## Struttura Progetto
- **Laravel**: Core applicazione
- **Modules**: Estensioni modulari
- **Themes**: Template frontend

## Configurazioni
- `.env.*`: File di configurazione ambiente
- `config/`: Configurazioni specifiche

## Comandi Utili
- `php artisan`: Gestione Laravel  
- `npm run dev`: Compilazione assets  
- `phpstan`: Analisi statica codice

## Comandi Personalizzati

### SearchTextInDbCommand
Permette di cercare testo nel database.
Utilizzo: 
```bash
php artisan search:text-in-db "testo da cercare"
```


## Filament Admin Panel

### Resources Base

#### XotBaseResource
Classe base per tutti i Resource Filament del progetto. Include già tutte le funzionalità necessarie per:
- Gestione avanzata delle colonne
- Azioni di massa (bulk actions)
- Filtri personalizzati
- Integrazione con il sistema di permessi
- Gestione ottimizzata delle relazioni

Esempio di utilizzo:
```php
use Modules\Xot\Filament\Resources\XotBaseResource;

class UserResource extends XotBaseResource
{
    protected function getListTableColumns(): array
    {
        return [
            // definizione delle colonne
        ];
    }
}
```

### Pagine Disponibili
- ListUsers: Gestione lista utenti
- ListDevices: Gestione lista dispositivi
- ListFeatures: Gestione lista funzionalità

## Filament Resources

### XotBaseViewRecord
Classe base per le pagine di visualizzazione dei record in Filament. Fornisce:
- Metodo `infolist()` per configurare la visualizzazione dei dati
- Metodo `getInfolistSchema()` per definire lo schema dei componenti

### Flusso di lavoro
1. Estendere XotBaseViewRecord per nuove risorse
2. Implementare getInfolistSchema() per definire i componenti
3. Utilizzare infolist() per applicare lo schema

## Filament Pages

### XotBaseListRecords
Classe base per tutte le pagine List dei Resource. È importante rispettare i livelli di accesso dei metodi quando si estende questa classe.

#### Metodi e Visibilità
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListSocialProviders extends XotBaseListRecords
{
    // CORRETTO: Il metodo deve essere public come nella classe padre
    public function getGridTableColumns(): array
    {
        return [
            // definizione delle colonne
        ];
    }

    // ERRATO: Non mantenere lo stesso livello di accesso della classe padre
    protected function getGridTableColumns(): array // Questo causerà un errore
    {
        return [];
    }
}
```

#### Errori Comuni
1. **Visibilità dei Metodi**
   - Errore: `Access level to [Method] must be public (as in class XotBaseListRecords)`
   - Soluzione: Mantenere sempre la stessa visibilità del metodo della classe padre
   - Esempio: Se un metodo è `public` in `XotBaseListRecords`, deve rimanere `public` nelle classi figlie

2. **Metodi Disponibili**
   - `getGridTableColumns()`: Deve essere `public`
   - `getListTableColumns()`: Deve essere `public`
   - `getTableColumns()`: Deve essere `public`

### Best Practices
- Controllare sempre la visibilità dei metodi nella classe padre prima di sovrascriverli
- Mantenere la coerenza con i tipi di ritorno
- Documentare eventuali modifiche alla logica del metodo

## Actions

### Errori Comuni nelle Actions

#### 1. Dichiarazione Duplicata del Metodo execute()
L'errore `Cannot redeclare [Class]::execute()` si verifica quando:
- Si sta implementando un'interfaccia che definisce `execute()`
- Si sta estendendo una classe base che già definisce `execute()`
- Si sta usando un trait che contiene `execute()`

```php
// ERRATO: Dichiarazione duplicata di execute()
class GetAllBlocksAction extends BaseAction
{
    use ExecutableTrait; // Il trait contiene già execute()

    public function execute() // Causa errore di ridichiarazione
    {
        // ...
    }
}

// CORRETTO: Scegliere uno solo dei modi per definire execute()
class GetAllBlocksAction extends BaseAction
{
    // Opzione 1: Usare il trait se contiene la logica necessaria
    use ExecutableTrait;

    // Opzione 2: Sovrascrivere il metodo del trait
    use ExecutableTrait {
        ExecutableTrait::execute as protected traitExecute;
    }
    
    public function execute()
    {
        // Nuova implementazione
    }

    // Opzione 3: Implementare direttamente senza trait
    public function execute()
    {
        return $this->getAllBlocks();
    }
}
```

### Best Practices per le Actions

1. **Controllo delle Dipendenze**
   - Verificare tutti i trait utilizzati
   - Controllare la classe base
   - Ispezionare le interfacce implementate

2. **Nomenclatura**
   - Suffisso `Action` per tutte le classi action
   - Nome descrittivo dell'operazione (es. `GetAllBlocksAction`)
   - Metodi interni con nomi chiari e specifici

3. **Struttura**
   - Una action = una responsabilità
   - Evitare logica complessa nel metodo `execute()`
   - Utilizzare metodi privati per suddividere la logica

4. **Type Safety**
   ```php
   class GetAllBlocksAction
   {
       public function execute(): Collection
       {
           return Block::query()
               ->active()
               ->get();
       }
   }
   ```

## Type Assertions
Use Assert class for type checking instead of manual type validation:

### Basic Example
```php
// Before
$relativePath = (string)config('modules.paths.generator.assets.path');
if (!is_string($relativePath)) {
    throw new \Exception('Invalid assets path configuration');
}

// After
Assert::string($relativePath = config('modules.paths.generator.assets.path'));
```

### Try/Catch Example
```php
// Before
try {
    $svgPath = module_path($this->name, $relativePath.'/../svg');
    $svgPath = (string)realpath($svgPath);
} catch (\Error $e) {
    $svgPath = base_path('Modules/'.$this->name.'/'.$relativePath.'/../svg');
}

// After
try {
    $svgPath = module_path($this->name, $relativePath.'/../svg');
    Assert::string($svgPath = realpath($svgPath));
} catch (\Error $e) {
    $svgPath = base_path('Modules/'.$this->name.'/'.$relativePath.'/../svg');
}
```

### Config Example
```php
// Before
$relativeConfigPath = (string)config('modules.paths.generator.config.path');
if (!is_string($relativeConfigPath)) {
    throw new \Exception('Invalid config path');
}

// After
Assert::string($relativeConfigPath = config('modules.paths.generator.config.path'));
```

Benefits:
- More concise code
- Better type safety
- Consistent error handling
- Improved static analysis
- Clearer error messages
- Better IDE support

## Note Analisi
- Tutti i moduli analizzati con PHPStan non presentano errori
- Configurazione phpstan.neon correttamente applicata
- Analisi eseguita da directory laravel con comando: `vendor/bin/phpstan analyse Modules --configuration=phpstan.neon`

## Sentiment Analysis

### Architecture
- **Contracts**: 
  - `SentimentAnalyzer` interface in `app/Contracts`
  
- **Implementations**:
  - `BasicSentimentAnalyzer` in `app/Actions`
  - `TransformersSentimentAnalyzer` in `app/Actions`
  
- **Main Action**:
  - `SentimentAction` in `app/Actions` that uses the appropriate implementation

### Features
- Provides both basic text pattern matching and advanced transformers-based analysis
- Automatically falls back to basic analysis if transformers package is not installed
- Requires `codewithkyrian/transformers` package for full functionality

### Installation
```bash
composer require codewithkyrian/transformers
```

### Notes
- IDE errors about undefined types/functions are expected until the package is installed
- The code will automatically fall back to basic text analysis if the package is not available

# Documentazione Modulo Laraxot

## Traits Filament

### NavigationLabelTrait
Gestisce le etichette di navigazione nel pannello Filament.

## Comandi Console

### SearchTextInDbCommand
```php
// Errore: Binary operation "." between non-falsy-string and mixed
$query = $table . "." . $column;

// Soluzione
$query = sprintf('%s.%s', (string)$table, (string)$column);
```

## PHPStan Analisi Moduli
Risultati dell'ultima analisi per modulo:
- **Xot**: Risolti tutti gli errori critici
- **Fixcity**: In fase di ottimizzazione
- **Media**: Nessun errore critico
- **UI**: Ottimizzato per PHPStan level 8
- **Tenant**: Risolti problemi di tipizzazione

## Ottimizzazioni Recenti

### Type Safety
- Implementata tipizzazione stretta per tutti i metodi pubblici
- Aggiunta validazione dei tipi nei costruttori
- Migliorata gestione delle relazioni Eloquent
- Implementati type hints per le collezioni

### Performance
- Ottimizzata la gestione delle query nel trait HasXotTable
- Implementato lazy loading per le relazioni pesanti
- Migliorata la cache delle query frequenti

## Trait HasXotTable

### Gestione Conflitti di Proprietà
Il trait può causare conflitti con le classi base di Filament. Ecco come gestirli:

```php
// Prima
trait HasXotTable
{
    protected $navigationSort = 0; // Conflitto con Filament\Pages\Page
}

// Dopo
trait HasXotTable
{
    protected $xotNavigationSort = 0; // Nome univoco per evitare conflitti
}
```

### Utilizzo Corretto
```php
abstract class XotBaseListRecords extends FilamentListRecords
{
    use HasXotTable;

    /**
     * @var int Sorting order for navigation
     */
    protected $xotNavigationSort = 0;
}
```

## Risoluzione Errori PHPStan

### 1. Errori nei Resource Filament

#### Array Keys nelle Table Columns
```php
// ERRATO: Array numerico
protected function getListTableColumns(): array
{
    return [
        TextColumn::make('name'),
        TextColumn::make('email'),
    ];
}

// CORRETTO: Array associativo con chiavi string
public function getListTableColumns(): array
{
    return [
        'name' => TextColumn::make('name'),
        'email' => TextColumn::make('email'),
    ];
}
```

#### Type Hints per Actions
```php
// ERRATO: Array numerico per bulk actions
protected function getTableBulkActions(): array
{
    return [
        DeleteBulkAction::make(),
    ];
}

// CORRETTO: Array associativo
public function getTableBulkActions(): array
{
    return [
        'delete' => DeleteBulkAction::make(),
    ];
}
```

### 2. Errori di Accesso agli Array Mixed

#### Gestione Response API
```php
// ERRATO: Accesso diretto a mixed
$lat = $response['results'][0]['geometry']['location']['lat'];

// CORRETTO: Validazione e cast
/** @var array{results: array{0: array{geometry: array{location: array{lat: float}}}}} $response */
$response = $this->validateResponse($response);
$lat = $response['results'][0]['geometry']['location']['lat'];

private function validateResponse(mixed $response): array
{
    if (!is_array($response)) {
        throw new InvalidArgumentException('Response must be an array');
    }
    // Validazione della struttura
    return $response;
}
```

### 3. Cast e Conversioni di Tipo

#### Float Cast da Mixed
```php
// ERRATO: Cast diretto da mixed
$latitude = (float)$data['latitude'];

// CORRETTO: Validazione e poi cast
$latitude = is_numeric($data['latitude']) 
    ? (float)$data['latitude']
    : throw new InvalidArgumentException('Latitude must be numeric');
```

### 4. Generics nelle Collections

#### Collection di Modelli
```php
// ERRATO: Type non specificato
/** @var Collection */
private $items;

// CORRETTO: Specificare il tipo generico
/** @var Collection<int, \App\Models\User> */
private $items;
```

### Best Practices PHPStan

1. **Form Schema**
   ```php
   // CORRETTO: Array associativo per form schema
   public function getFormSchema(): array
   {
       return [
           'name' => TextInput::make('name'),
           'active' => Toggle::make('active'),
       ];
   }
   ```

2. **Table Actions**
   ```php
   // CORRETTO: Array associativo per table actions
   public function getTableActions(): array
   {
       return [
           'edit' => EditAction::make(),
           'delete' => DeleteAction::make(),
       ];
   }
   ```

3. **Data Transfer Objects**
   ```php
   // CORRETTO: Constructor properties con type hints
   class CoordinatesData
   {
       public function __construct(
           public readonly float $latitude,
           public readonly float $longitude,
       ) {}
   }
   ```

### Note Importanti
- Tutti i metodi che restituiscono componenti Filament devono usare array associativi
- Validare sempre i dati esterni prima di accedervi
- Utilizzare strict_types=1 in tutti i file
- Documentare i tipi generici nelle collections

# Struttura Moduli Laraxot

## Convenzioni di Directory

### Struttura Standard
```
Modules/[ModuleName]/
├── app/
│   ├── Datas/           # Data Transfer Objects
│   ├── Enums/           # Enumerazioni
│   ├── Models/          # Modelli Eloquent
│   ├── Providers/       # Service Providers
│   ├── Services/        # Servizi
│   └── Filament/        # Componenti Filament
│       ├── Pages/       
│       ├── Resources/   
│       └── Widgets/     
├── config/              # Configurazioni
├── database/           
│   └── migrations/      # Migrazioni
├── routes/              # File delle rotte
└── docs/               # Documentazione
```

### Convenzioni Importanti
1. **Data Transfer Objects**
   - Posizione: `app/Datas/` (non `app/Data/`)
   - Suffisso: `Data` (es. `ReportData.php`)
   - Namespace: `Modules\[ModuleName]\Datas`

2. **Enumerazioni**
   - Posizione: `app/Enums/`
   - Suffisso: `Enum` (es. `StatusEnum.php`)
   - Namespace: `Modules\[ModuleName]\Enums`

3. **Models**
   - Posizione: `app/Models/`
   - Base: Estendere `XotBaseModel`
   - Namespace: `Modules\[ModuleName]\Models`

### Integrazione Media Library
- Utilizzare `HasMedia` e `InteractsWithMedia`
- Configurare collezioni in `registerMediaCollections()`
- Definire conversioni in `registerMediaConversions()`

### Note PHPStan
- Livello: 8 (massimo)
- Strict types obbligatorio
- Type hints completi
- PHPDoc per proprietà e metodi

### Best Practices
- Classi base astratte per funzionalità comuni
- Traits per codice condiviso
- Test automatici
- Gestione sicura dei tipi nullable
- Documentazione delle proprietà ereditate
- Separazione della logica in blocchi type-safe
- Uso di variabili intermedie per migliorare la leggibilità
- Importazione esplicita delle classi del framework

### Verifica Struttura Moduli
Prima di creare nuovi file:
1. Controllare `composer.json` del modulo
2. Verificare il namespace base definito
3. Rispettare la struttura PSR-4
4. Non assumere strutture comuni tra moduli

# Struttura Progetto Laraxot

## Percorsi Base
- **Root progetto**: `/`
- **Laravel**: `/laravel/`
- **Moduli**: `/laravel/Modules/`
- **Temi**: `/laravel/Themes/`
- **Public**: `/public_html/`

### Struttura Corretta
```
/                           # Root del progetto
├── laravel/                # Core Laravel
│   ├── Modules/           # Directory moduli
│   │   ├── Xot/
│   │   ├── Fixcity/
│   │   └── ...
│   ├── Themes/           # Directory temi
│   │   ├── Sixteen/
│   │   └── TwentyOne/
│   └── ...
├── public_html/          # Document root pubblico
└── docs/                # Documentazione
```

### Convenzioni Percorsi

#### ✅ Percorsi Corretti
```
laravel/Modules/Fixcity/app/Datas/ReportData.php
laravel/Modules/Fixcity/app/Models/Report.php
laravel/Themes/Sixteen/dist/
```

#### ❌ Percorsi Errati da Rimuovere
```
F:\var\www\fixcity\Modules\              # ❌ ERRATO: manca laravel\
F:\var\www\fixcity\Modules\Fixcity\     # ❌ ERRATO: manca laravel\
```

### Note Importanti
1. Tutti i riferimenti ai moduli devono partire da `laravel/Modules/`
2. I temi si trovano in `laravel/Themes/`
3. Gli assets pubblici vanno in `public_html/`
4. La documentazione va in `/docs/`

# Gestione Temi e Assets

## Struttura Temi
```
laravel/Themes/[ThemeName]/
├── resources/
│   ├── js/
│   ├── css/
│   └── views/
├── dist/                # Output compilato
├── vite.config.js       # Configurazione Vite
├── tailwind.config.js   # Configurazione Tailwind
├── package.json         # Dipendenze npm
└── theme.json          # Configurazione tema
```

## Build Process

### Setup Iniziale
```bash
cd laravel/Themes/[ThemeName]
npm install
```

### Comandi Build
```bash
# Development build
npm run dev

# Production build
npm run build
```

### Output Directory
- Build output va in: `laravel/Themes/[ThemeName]/dist/`
- Assets pubblici in: `public_html/themes/[ThemeName]/dist/`

### Configurazione Vite
```javascript
// laravel/Themes/[ThemeName]/vite.config.js
export default defineConfig({
    build: {
        manifest: true,
        outDir: 'dist',
        rollupOptions: {
            input: 'resources/js/app.js'
        }
    }
})
```

### Gestione DaisyUI
Per risolvere i warning CSS di DaisyUI:

```javascript
// laravel/Themes/[ThemeName]/tailwind.config.js
module.exports = {
    content: [
        './resources/**/*.{php,html,js,jsx,ts,tsx,vue}',
    ],
    plugins: [
        require('daisyui')
    ],
    daisyui: {
        themes: ['light', 'dark'],
        darkTheme: 'dark',
        logs: false // Disabilita i warning
    }
}
```

### Symlinks
Per collegare gli assets compilati al public:
```bash
ln -s /laravel/Themes/[ThemeName]/dist /public_html/themes/[ThemeName]/dist
```

### Note Importanti
1. Ogni tema deve avere il proprio `package.json`
2. Non mischiare assets di temi diversi
3. Mantenere la struttura delle directory consistente
4. Usare symlinks per gli assets pubblici
5. Configurare correttamente i path in vite.config.js

# Service Providers

## XotBaseServiceProvider

### Gestione Assets e SVG

#### Struttura Corretta
```
laravel/Modules/[ModuleName]/
├── app/
├── resources/
│   ├── js/
│   ├── css/
│   └── svg/           # Directory icone SVG
└── assets/
    └── svg/          # Directory alternativa icone SVG
```

### Registrazione Componenti Blade

## Autoregistrazione in XotBaseServiceProvider

I componenti Blade vengono autoregistrati grazie a `XotBaseServiceProvider`. Non è necessario registrarli manualmente.

### Come Funziona
```php
public function registerBladeComponents(): void
{
    Assert::string($relativePath = config('modules.paths.generator.component-class.path'));
    $componentClassPath = module_path($this->name, $relativePath);
    $namespace = $this->module_ns.'\View\Components';
    Blade::componentNamespace($namespace, $this->nameLower);

    app(RegisterBladeComponentsAction::class)
        ->execute(
            $componentClassPath,
            $this->module_ns
        );
}
```

### Struttura Corretta dei Componenti
```
laravel/Modules/[ModuleName]/
└── View/
    └── Components/
        └── Blocks/
            └── TicketList/
                └── Agid.php
```

### Convenzioni
1. I componenti devono essere nella directory `View/Components`
2. Il namespace deve corrispondere al percorso
3. Non serve registrazione manuale in ServiceProvider

### Uso dei Componenti
```blade
{{-- Il componente sarà disponibile automaticamente --}}
<x-fixcity::blocks.ticket_list.agid />
```

### ❌ Da Evitare
```php
// ❌ NON necessario - i componenti sono già autoregistrati
class FixcityServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::component('fixcity-ticket-list-agid', SomeComponent::class); // Non necessario
    }
}
```

# Gestione Errori nei Service Provider

## Best Practices

### 1. Validazione Configurazioni
```php
// ❌ Non fare
$path = config('some.config');
$realPath = realpath($path); // Può fallire

// ✅ Fare
$path = config('some.config');
Assert::string($path, 'Config must be string');
Assert::directory($path, 'Config must point to valid directory');
```

### 2. Gestione Percorsi
```php
// ❌ Non fare
$path = realpath($basePath . '/' . $relativePath);

// ✅ Fare
$path = sprintf('%s/%s', $basePath, $relativePath);
if (!is_dir($path)) {
    // Gestire il caso o usare un fallback
}
```

### 3. Ordine di Precedenza
Quando si cercano risorse (es. SVG):
1. Prima controllare in `resources/`
2. Poi in `assets/`
3. Infine usare il fallback configurato

### 4. Early Returns
```php
protected function loadSomething(string $path): void
{
    if (!is_dir($path)) {
        return;
    }
    // Procedere solo se il percorso è valido
}
```

### 5. Costanti per Percorsi
```php
class XotBaseServiceProvider extends ServiceProvider
{
    private const SVG_PATHS = [
        'resources/svg',
        'assets/svg',
    ];
    
    protected function getSvgPaths(): array
    {
        return array_map(
            fn(string $path) => sprintf('%s/%s', $this->getModulePath(), $path),
            self::SVG_PATHS
        );
    }
}
```

# Percorsi Importanti

## ⚠️ Attenzione ai Percorsi

### Percorso Base del Progetto
```
F:\var\www\fixcity\              # Root del progetto
└── laravel\                     # ⚠️ Tutti i moduli vanno qui dentro
    └── Modules\                 # Directory corretta per i moduli
```

### ✅ Percorsi Corretti
```
F:\var\www\fixcity\laravel\Modules\Fixcity\app\Datas\ReportData.php
F:\var\www\fixcity\laravel\Modules\Fixcity\app\Models\Report.php
F:\var\www\fixcity\laravel\Themes\Sixteen\dist\
```

### ❌ Percorsi Errati da Rimuovere
```
F:\var\www\fixcity\Modules\              # ❌ ERRATO: manca laravel\
F:\var\www\fixcity\Modules\Fixcity\     # ❌ ERRATO: manca laravel\
```

### Verifica Prima di Creare Nuovi File
1. Assicurarsi di essere in `F:\var\www\fixcity\laravel\Modules\`
2. Controllare il composer.json del modulo
3. Verificare il namespace corretto
4. Mai creare file direttamente in `F:\var\www\fixcity\Modules\`

# Comandi Artisan

## Posizione Corretta
```
F:\var\www\fixcity\
└── laravel\              # ⚠️ Directory dove si trova artisan
    ├── artisan           # Eseguibile artisan
    ├── Modules\         
    └── vendor\
```

## Esecuzione Comandi

### ✅ Modo Corretto
```bash
# Posizionarsi nella directory laravel
cd F:\var\www\fixcity\laravel

# Eseguire i comandi da qui
php artisan module:seed Fixcity
php artisan migrate
php artisan config:clear
```

### ❌ Modi Errati
```bash
# ❌ ERRATO: dalla root del progetto
cd F:\var\www\fixcity
php artisan module:seed Fixcity  # Non funzionerà

# ❌ ERRATO: dalla directory Modules
cd F:\var\www\fixcity\laravel\Modules
php artisan module:seed Fixcity  # Non funzionerà
```

## Comandi Comuni
```bash
# Dalla directory F:\var\www\fixcity\laravel
php artisan module:seed Fixcity          # Seeding modulo
php artisan module:make-model Report     # Creare model
php artisan module:make-factory Report   # Creare factory
php artisan module:make-seeder Report    # Creare seeder
```

## Note Importanti
1. **Directory di Lavoro**:
   - Tutti i comandi artisan devono essere eseguiti da `F:\var\www\fixcity\laravel`
   - Il file `artisan` si trova in questa directory
   - L'autoload e le configurazioni sono relative a questa directory

2. **Percorsi nei Comandi**:
   - I percorsi nei comandi sono relativi a `F:\var\www\fixcity\laravel`
   - Usare percorsi relativi quando possibile
   - Per percorsi assoluti, usare `base_path()` che punta a `laravel/`

3. **Best Practices**:
   - Mantenere un terminale aperto nella directory `laravel`
   - Usare alias o script per navigare velocemente alla directory corretta
   - Verificare sempre la directory corrente prima di eseguire comandi

# Struttura Moduli e Namespace

## Importanza del composer.json

### 1. Verifica Sempre il composer.json
Prima di creare qualsiasi file in un modulo, controllare sempre il `composer.json` per il mapping dei namespace:

```json
{
    "autoload": {
        "psr-4": {
            "Modules\\Fixcity\\": "app/"     // ✅ Il namespace punta a app/
            // oppure
            "Modules\\UI\\": ""              // ⚠️ Il namespace punta alla root del modulo
        }
    }
}
```

### 2. Esempi di Percorsi Basati sul PSR-4

#### Modulo con namespace in "app/"
```
Modules/Fixcity/composer.json:
"Modules\\Fixcity\\": "app/"

✅ Percorsi Corretti:
laravel/Modules/Fixcity/app/Providers/FixcityServiceProvider.php
laravel/Modules/Fixcity/app/View/Components/Blocks/TicketList/Agid.php
laravel/Modules/Fixcity/app/Models/Report.php

❌ Percorsi Errati:
laravel/Modules/Fixcity/Providers/FixcityServiceProvider.php
laravel/Modules/Fixcity/View/Components/Blocks/TicketList/Agid.php
```

#### Modulo con namespace nella root
```
Modules/UI/composer.json:
"Modules\\UI\\": ""

✅ Percorsi Corretti:
laravel/Modules/UI/Providers/UIServiceProvider.php
laravel/Modules/UI/View/Components/Button.php

❌ Percorsi Errati:
laravel/Modules/UI/app/Providers/UIServiceProvider.php
laravel/Modules/UI/app/View/Components/Button.php
```

### 3. Best Practices
1. **Prima di Creare File**:
   - Controllare `composer.json` del modulo
   - Verificare il mapping del namespace
   - Rispettare la struttura PSR-4 definita

2. **Errori Comuni da Evitare**:
   - Assumere che tutti i moduli usino la stessa struttura
   - Copiare percorsi da altri moduli senza verificare
   - Ignorare il mapping PSR-4 nel composer.json

3. **Validazione Percorsi**:
   ```php
   // ✅ Corretto: Usa il namespace definito in composer.json
   namespace Modules\Fixcity\View\Components;  // Sarà in app/View/Components
   
   // ❌ Errato: Ignora il namespace mapping
   namespace Modules\Fixcity\Components;       // Percorso non mappato
   ```

# Views e Temi

## Risoluzione Views
Quando si usa una notazione del tipo `pub_theme::path.to.view`:

1. **Identificazione Tema**:
   ```php
   // In XotBaseServiceProvider
   protected function registerTheme(): void
   {
       $theme_pub = config('xra.pub_theme');  // Legge il tema pubblico configurato
       $theme_pub ??= 'Sixteen';              // Default: Sixteen
   }
   ```

2. **Percorsi Corretti**:
   ```
   pub_theme::livewire.auth.login  →  Themes/Sixteen/resources/views/livewire/auth/login.blade.php
   pub_theme::components.header    →  Themes/Sixteen/resources/views/components/header.blade.php
   ```

3. **❌ Errori Comuni**:
   ```
   // ❌ ERRATO: Cercare le views del tema nel modulo
   Modules/Fixcity/resources/views/livewire/auth/login.blade.php
   
   // ✅ CORRETTO: Views del tema vanno nel tema
   Themes/Sixteen/resources/views/livewire/auth/login.blade.php
   ```

## Best Practices
1. **Prima di Modificare una View**:
   - Controllare se è una view di tema (`theme::`) o di modulo (`module::`)
   - Verificare il tema attivo in `config/xra.php`
   - Rispettare la struttura del tema

2. **Struttura Temi**:
   ```
   laravel/Themes/[ThemeName]/
   ├── resources/
   │   └── views/
   │       ├── livewire/      # Componenti Livewire
   │       ├── components/    # Blade Components
   │       └── layouts/       # Layout templates
   ```

## Risoluzione Temi in Laraxot

### 1. Configurazione
```php
// config/xra.php
return [
    'pub_theme' => 'Sixteen',     // Tema pubblico
    'adm_theme' => 'AdminLTE',    // Tema admin
];
```

### 2. Helper e Facades
```php
Theme::asset('pub_theme::css/app.css')     // Risolve assets del tema pubblico
Theme::view('pub_theme::components.header') // Risolve views del tema pubblico
```

### 3. Percorsi da Controllare
1. Prima: `Themes/[ThemeName]/resources/views/`
2. Poi: `vendor/[vendor]/[theme-package]/resources/views/`
3. Infine: fallback al tema di default

# Conversione Bootstrap Italia a Filament/Tailwind

## Principi di Conversione

### 1. Classi Bootstrap → Tailwind
```html
<!-- Bootstrap Italia -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

<!-- Tailwind -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-center">
        <div class="w-full md:w-2/3">
```

### 2. Componenti Bootstrap → Filament
```html
<!-- Bootstrap Italia Input Group -->
<div class="form-group">
    <label class="form-label">Email</label>
    <div class="input-group">
        <span class="input-group-text">
            <svg class="icon"><use href="#it-mail"></use></svg>
        </span>
        <input type="email" class="form-control">
    </div>
</div>

<!-- Filament Input -->
<x-filament::input.wrapper>
    <x-filament::input
        type="email"
        prefix-icon="heroicon-m-envelope"
        :label="__('Email')"
    />
</x-filament::input.wrapper>
```

### 3. Icone Bootstrap Italia → Heroicons
```html
<!-- Bootstrap Italia -->
<svg class="icon"><use href="#it-mail"></use></svg>

<!-- Heroicons in Filament -->
<x-heroicon-m-envelope class="w-5 h-5" />
```

### 4. Alerts e Feedback
```html
<!-- Bootstrap Italia -->
<div class="alert alert-danger">{{ $message }}</div>

<!-- Filament -->
<x-filament::alert type="danger" icon="heroicon-m-x-circle">
    {{ $message }}
</x-filament::alert>
```

### 5. Buttons e Actions
```html
<!-- Bootstrap Italia -->
<button class="btn btn-primary">
    <span class="spinner-border spinner-border-sm"></span>
    Loading...
</button>

<!-- Filament -->
<x-filament::button
    type="submit"
    :disabled="$this->isLoading"
    wire:loading.attr="disabled">
    <x-filament::loading-indicator wire:loading />
    {{ __('Submit') }}
</x-filament::button>
```

## Best Practices

1. **Layout**:
   - Usare il sistema grid di Tailwind
   - Sfruttare i preset di spaziatura Filament
   - Mantenere la responsività

2. **Forms**:
   - Utilizzare `x-filament::form`
   - Wrapper per input consistenti
   - Validazione integrata

3. **Componenti**:
   - Preferire componenti Filament quando possibile
   - Estendere solo se necessario
   - Mantenere la consistenza visiva

4. **Accessibilità**:
   - Preservare gli attributi ARIA
   - Mantenere la keyboard navigation
   - Rispettare i contrasti

# Filament Components Best Practices

## 1. Sempre Usare Componenti Filament
```blade
<!-- ✅ CORRETTO: Usare sempre componenti Filament -->
<x-filament::input.wrapper>
    <x-filament::input
        type="email"
        wire:model="email"
        :label="__('Email')"
    />
</x-filament::input.wrapper>

<!-- ❌ ERRATO: Non usare HTML nativo -->
<input type="email" wire:model="email" class="..." />
```

## 2. Design System AGID con Filament

### Layout Card AGID
```blade
<div class="it-card-wrapper">
    <x-filament::card>
        <!-- Contenuto -->
    </x-filament::card>
</div>
```

### Form AGID Style
```blade
<x-filament::form wire:submit="save">
    <!-- Input con icona (stile AGID) -->
    <x-filament::input.wrapper>
        <x-filament::input.prefix>
            <x-filament::icon
                name="heroicon-m-envelope"
                class="h-5 w-5 text-gray-400"
            />
        </x-filament::input.prefix>

        <x-filament::input
            type="email"
            wire:model="email"
        />
    </x-filament::input.wrapper>

    <!-- Button AGID style -->
    <x-filament::button
        type="submit"
        class="btn btn-primary"
    >
        {{ __('Invia') }}
    </x-filament::button>
</x-filament::form>
```

## 3. Componenti Filament Disponibili

### Form Elements
- `<x-filament::input.wrapper>`
- `<x-filament::input>`
- `<x-filament::select>`
- `<x-filament::checkbox>`
- `<x-filament::toggle>`

### Buttons & Links
- `<x-filament::button>`
- `<x-filament::link>`

### Layout
- `<x-filament::card>`
- `<x-filament::section>`

### Icons & Loading
- `<x-filament::icon>`
- `<x-filament::loading-indicator>`

## 4. Best Practices

1. **Consistenza**:
   - Usare SEMPRE componenti Filament
   - Mai mixare con HTML nativo
   - Mantenere lo stile AGID

2. **Accessibilità**:
   - Usare label e hint
   - Gestire stati di errore
   - Mantenere focus states

3. **Validazione**:
   - Usare il sistema di validazione Filament
   - Mostrare feedback appropriati
   - Gestire stati loading

# Filament 3 Forms e Componenti

## Componenti Forms vs UI

### ❌ Errato: Componenti UI diretti
```blade
<!-- ❌ NON FUNZIONA: questi componenti non esistono -->
<x-filament::checkbox>
<x-filament::input>
<x-filament::card>
```

### ✅ Corretto: Usare Forms Components
```blade
<!-- ✅ CORRETTO: Usare i componenti Forms -->
<x-filament-forms::field-wrapper>
    <x-filament-forms::checkbox
        wire:model="remember"
        label="Remember me"
    />
</x-filament-forms::field-wrapper>
```

## Namespace Corretti

1. **Forms Components**:
```blade
<x-filament-forms::field-wrapper>
<x-filament-forms::text-input>
<x-filament-forms::checkbox>
<x-filament-forms::select>
```

2. **Actions/Buttons**:
```blade
<x-filament::button>
<x-filament::link>
```

3. **Notifications**:
```blade
<x-filament-notifications::notification>
```

## Best Practices

1. **Form Fields**:
   ```blade
   <x-filament-forms::field-wrapper
       :label="__('Email')"
       :helper-text="__('We\'ll never share your email')"
       :hint="__('Enter your email')"
       :state-path="'email'"
   >
       <x-filament-forms::text-input
           type="email"
           wire:model="email"
           required
       />
   </x-filament-forms::field-wrapper>
   ```

2. **Validation States**:
   ```blade
   <x-filament-forms::field-wrapper
       :state-path="'email'"
       :error="$errors->first('email')"
   >
       <!-- input -->
   </x-filament-forms::field-wrapper>
   ```

3. **Loading States**:
   ```blade
   <x-filament::button
       wire:loading.attr="disabled"
       wire:target="save"
   >
       <x-filament::loading-indicator
           wire:loading
           wire:target="save"
       />
       {{ __('Save') }}
   </x-filament::button>
   ```

# Filament 3 Plugins e Notifiche

## 1. Installazione Pacchetti Necessari
```bash
composer require filament/support:"^3.0"
composer require filament/forms:"^3.0"
composer require filament/notifications:"^3.0"
```

## 2. Messaggi di Errore e Feedback

### ❌ Componenti Non Disponibili
```blade
<!-- ❌ ERRATO: questi componenti non esistono -->
<x-filament::alert>
<x-filament::notification>
```

### ✅ Modo Corretto
```blade
<!-- ✅ CORRETTO: Usare Notification API per feedback dinamici -->
@php
    Notification::make()
        ->title('Error message')
        ->danger()
        ->send();
@endphp

<!-- ✅ CORRETTO: Per errori statici usare HTML con stile Filament -->
<div class="rounded-lg bg-danger-50 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <x-heroicon-m-x-circle class="h-5 w-5 text-danger-400"/>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-danger-800">
                {{ $message }}
            </h3>
        </div>
    </div>
</div>
```

## 3. Best Practices per Feedback

### Errori di Validazione Form
```blade
<x-filament-forms::field-wrapper
    :label="__('Email')"
    :error="$errors->first('email')"  <!-- Errori inline -->
>
    <x-filament-forms::text-input
        type="email"
        wire:model="email"
    />
</x-filament-forms::field-wrapper>
```

### Messaggi di Sistema
```php
// Nel componente Livewire
Notification::make()
    ->title('Action completed')
    ->success()
    ->send();
```

### Errori Generici
```blade
@if ($errors->any())
    <div class="rounded-lg bg-danger-50 p-4 my-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <x-heroicon-m-x-circle class="h-5 w-5 text-danger-400"/>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-danger-800">
                    {{ __('Si sono verificati degli errori') }}
                </h3>
                <div class="mt-2 text-sm text-danger-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
```

# Filament Forms in Livewire 3 con PHP 8 Strict Types

## Gestione Proprietà

### ❌ Modo Errato
```php
// ❌ ERRATO: Proprietà tipizzate senza gestione null
class Login extends Component implements HasForms
{
    public string $email = '';    // Può causare problemi con form vuoto
    public string $password = ''; // Può causare problemi con form vuoto
    public bool $remember = false;
}
```

### ✅ Modo Corretto
```php
// ✅ CORRETTO: Usare validazione e regole
class Login extends Component implements HasForms
{
    use InteractsWithForms;

    /**
     * @var array<string, mixed>
     */
    protected $rules = [
        'email' => ['required', 'email'],
        'password' => ['required'],
        'remember' => ['boolean'],
    ];
}
```

## Form Schema e Validazione

### 1. Schema del Form
```php
protected function getFormSchema(): array
{
    return [
        TextInput::make('email')
            ->email()
            ->required(),
        TextInput::make('password')
            ->password()
            ->required(),
        Checkbox::make('remember')  // Campo remember per "ricordami"
            ->label(__('Ricordami')),
    ];
}
```

### 2. Validazione
```php
public function authenticate()
{
    $data = $this->validate();

    // Estrai remember dal data array
    $remember = $data['remember'] ?? false;
    unset($data['remember']);

    if (Auth::attempt($data, $remember)) {
        session()->regenerate();
        return redirect()->intended();
    }
}
```

## Best Practices

1. **Regole di Validazione**:
   ```php
   /**
    * @var array<string, mixed>
    */
   protected $rules = [
       'remember' => ['boolean'],  // Permette true/false
   ];
   ```

2. **Separazione Dati**:
   ```php
   // Separa i dati di autenticazione da remember
   $credentials = [
       'email' => $data['email'],
       'password' => $data['password'],
   ];
   ```

3. **Type Safety**:
   ```php
   public bool $remember = false;  // Tipo esplicito
   ```

4. **Documentazione**:
   ```php
   /**
    * Handle the authentication attempt.
    *
    * @return \Illuminate\Http\RedirectResponse|void
    */
   public function authenticate()
   {
       // ...
   }
   ```

# Gestione Trait Conflicts in Laravel

## Risoluzione Conflitti tra Trait

### 1. Problema Comune
Quando due trait definiscono lo stesso metodo, si verifica un conflitto:

```php
// ❌ ERRATO: Conflitto tra metodi notifications()
class User extends Authenticatable
{
    use Notifiable;
    use InteractsWithComments;  // Conflitto!
}
```

### 2. Soluzione con insteadof e as
```php
// ✅ CORRETTO: Risoluzione esplicita dei conflitti
class BaseUser extends Authenticatable
{
    use Notifiable;
    use InteractsWithComments {
        // Usa notifications di InteractsWithComments
        InteractsWithComments::notifications insteadof Notifiable;
        // Rinomina notifications di Notifiable
        Notifiable::notifications as protected notificationRelation;
    }

    /**
     * Override per mantenere la type-safety
     *
     * @return MorphMany
     */
    public function notifications(): MorphMany
    {
        return $this->notificationRelation();
    }
}
```

## Best Practices

1. **Dichiarazione Esplicita dei Tipi**:
   ```php
   use Illuminate\Database\Eloquent\Relations\MorphMany;
   
   public function notifications(): MorphMany
   {
       // Return type dichiarato
   }
   ```

2. **Protezione dei Metodi**:
   ```php
   // Metodo originale reso protected
   Notifiable::notifications as protected notificationRelation;
   ```

3. **Documentazione PHPDoc**:
   ```php
   /**
    * Get the entity's notifications.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphMany
    */
   public function notifications(): MorphMany
   ```

## Errori da Evitare

### 1. Mancata Dichiarazione dei Tipi
```php
// ❌ ERRATO: Tipo di ritorno mancante
public function notifications()
{
    return $this->notificationRelation();
}

// ✅ CORRETTO: Tipo di ritorno dichiarato
public function notifications(): MorphMany
{
    return $this->notificationRelation();
}
```

### 2. Mancata Gestione dei Conflitti
```php
// ❌ ERRATO: Conflitti non gestiti
use Notifiable;
use InteractsWithComments;

// ✅ CORRETTO: Conflitti gestiti esplicitamente
use Notifiable;
use InteractsWithComments {
    InteractsWithComments::notifications insteadof Notifiable;
}
```

### 3. Type-Safety
```php
// ❌ ERRATO: Return type non specifico
public function notifications(): mixed

// ✅ CORRETTO: Return type specifico
public function notifications(): MorphMany
```

# Gestione Notifiche in Laravel

## Setup Base

### 1. Modello Base
```php
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;

abstract class BaseUser extends Authenticatable
{
    use Notifiable;

    public function notifications(): MorphMany
    {
        return $this->morphMany(
            config('notifications.notification_model', \Illuminate\Notifications\DatabaseNotification::class),
            'notifiable'
        );
    }
}
```

### 2. Configurazione Notifiche
```php
// config/notifications.php
return [
    'notification_model' => \Illuminate\Notifications\DatabaseNotification::class,
];
```

## Estensioni Opzionali

### 1. Con Spatie Comments
Se hai bisogno della funzionalità commenti:
```bash
# 1. Installa il pacchetto
composer require spatie/laravel-comments

# 2. Pubblica la configurazione
php artisan vendor:publish --provider="Spatie\Comments\CommentsServiceProvider"
```

Poi nel modello:
```php
use Spatie\Comments\Models\Concerns\InteractsWithComments;

class BaseUser extends Authenticatable
{
    use Notifiable;
    use InteractsWithComments {
        InteractsWithComments::notifications insteadof Notifiable;
        Notifiable::notifications as protected notificationRelation;
    }
}
```

### 2. Senza Spatie Comments
Se non serve la funzionalità commenti:
```php
class BaseUser extends Authenticatable
{
    use Notifiable;  // Solo notifiche base Laravel
}
```

## Best Practices

1. **Configurazione**:
   ```php
   // Usa config() per il modello di notifica
   config('notifications.notification_model', DefaultModel::class)
   ```

2. **Type Safety**:
   ```php
   public function notifications(): MorphMany
   {
       // Return type esplicito
   }
   ```

3. **Modularità**:
   - Installa solo i pacchetti necessari
   - Usa trait solo se servono
   - Mantieni le dipendenze minime

# Autenticazione Laravel con Remember Me

## Gestione Corretta del Remember Me

### 1. Form Schema
```php
protected function getFormSchema(): array
{
    return [
        TextInput::make('email')
            ->required(),
        TextInput::make('password')
            ->required(),
        Checkbox::make('remember')  // Campo remember per "ricordami"
            ->label(__('Ricordami')),
    ];
}
```

### 2. Validazione e Autenticazione
```php
/**
 * @var array<string, mixed>
 */
protected $rules = [
    'email' => ['required', 'email'],
    'password' => ['required'],
    'remember' => ['boolean'],  // Validazione per remember
];

public function authenticate()
{
    $data = $this->validate();

    // Estrai remember dal data array
    $remember = $data['remember'] ?? false;
    unset($data['remember']);

    if (Auth::attempt($data, $remember)) {
        session()->regenerate();
        return redirect()->intended();
    }
}
```

## Errori Comuni

### 1. Remember nella Query
```php
// ❌ ERRATO: Include remember nella query
Auth::attempt([
    'email' => $email,
    'password' => $password,
    'remember' => true  // Causerà errore SQL
]);

// ✅ CORRETTO: Remember come secondo parametro
Auth::attempt([
    'email' => $email,
    'password' => $password,
], $remember);
```

### 2. Gestione Stato Remember
```php
// ❌ ERRATO: Non gestire il default
$remember = $data['remember'];  // Potrebbe non esistere

// ✅ CORRETTO: Gestire il default
$remember = $data['remember'] ?? false;
```

## Best Practices

1. **Validazione**:
   ```php
   protected $rules = [
       'remember' => ['boolean'],  // Permette true/false
   ];
   ```

2. **Separazione Dati**:
   ```php
   // Separa i dati di autenticazione da remember
   $credentials = [
       'email' => $data['email'],
       'password' => $data['password'],
   ];
   ```

3. **Type Safety**:
   ```php
   public bool $remember = false;  // Tipo esplicito
   ```

4. **Documentazione**:
   ```php
   /**
    * Handle the authentication attempt.
    *
    * @return \Illuminate\Http\RedirectResponse|void
    */
   public function authenticate()
   {
       // ...
   }
   ```

# Setup Assets Filament 3

## 1. Installazione Pacchetti NPM

### Package.json
```json
{
    "devDependencies": {
        "@filamentphp/forms": "^3.0",
        "@filamentphp/support": "^3.0",
        "@filamentphp/notifications": "^3.0"
    }
}
```

### Installazione
```bash
# Nella directory del tema
cd laravel/Themes/Sixteen

# Installa le dipendenze
yarn install

# Build e copia
yarn run build && yarn run copy
```

## 2. Importazione CSS

### Ordine Corretto
```css
/* 1. Filament CSS - Ordine importante */
@import '@filamentphp/forms/dist/index.css';
@import '@filamentphp/notifications/dist/index.css';
@import '@filamentphp/support/dist/index.css';

/* 2. Tailwind */
@tailwind base;
@tailwind components;
@tailwind utilities;

/* 3. Custom Components */
@layer components {
    .filament-button {
        @apply inline-flex items-center justify-center py-2 px-4 font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors;
    }

    .filament-button-primary {
        @apply bg-primary-600 text-white hover:bg-primary-500 focus:ring-primary-500;
    }

    /* Stili per il form di login */
    .login-form {
        @apply space-y-6;
    }

    .login-button {
        @apply w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500;
    }
}

[x-cloak] {
    display: none;
}
```

## Errori Comuni

### 1. Import Errati
```css
/* ❌ ERRATO: Import diretto da vendor */
@import '../../vendor/filament/**/*.css';

/* ✅ CORRETTO: Import da node_modules */
@import '@filamentphp/forms/dist/index.css';
```

### 2. Ordine Import
```css
/* ❌ ERRATO: Tailwind prima di Filament */
@tailwind base;
@import '@filamentphp/forms/dist/index.css';

/* ✅ CORRETTO: Filament prima di Tailwind */
@import '@filamentphp/forms/dist/index.css';
@tailwind base;
```

### 3. Pacchetti Mancanti
```json
/* ❌ ERRATO: Pacchetti incompleti */
{
    "@filamentphp/forms": "^3.0"
}

/* ✅ CORRETTO: Tutti i pacchetti necessari */
{
    "@filamentphp/forms": "^3.0",
    "@filamentphp/support": "^3.0",
    "@filamentphp/notifications": "^3.0"
}
```

## Best Practices

1. **Script di Build**:
   ```json
   {
       "scripts": {
           "build": "vite build",
           "copy": "cp -r ./resources/dist/* ../../../public_html/themes/Sixteen/dist"
       }
   }
   ```

2. **Layer Components**:
   ```css
   @layer components {
       .filament-button {
           @apply /* ... */;
       }
   }
   ```

3. **Gestione Assets**:
   ```bash
   # Development
   yarn run dev

   # Production + Copy
   yarn run build && yarn run copy
   ```

4. **Verifica Installazione**:
   ```bash
   # Controlla le dipendenze installate
   yarn list | grep @filamentphp

   # Verifica node_modules
   ls node_modules/@filamentphp
   ```
```

# Vite e Filament Assets

## Setup Corretto

### 1. vite.config.js
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: './resources/dist',
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
});
```

### 2. CSS Imports
```css
/* ❌ ERRATO: Import da vendor */
@import '../../vendor/filament/**/*.css';

/* ✅ CORRETTO: Import da node_modules */
@import 'node_modules/@filamentphp/forms/dist/index.css';
@import 'node_modules/@filamentphp/notifications/dist/index.css';
@import 'node_modules/@filamentphp/support/dist/index.css';
```

## Errori Comuni

### 1. Import Pattern Errati
```javascript
// ❌ ERRATO: Glob pattern in vite.config.js
input: [
    '../../vendor/filament/**/*.css',  // Non funzionerà
]

// ✅ CORRETTO: Input specifici
input: [
    'resources/css/app.css',
    'resources/js/app.js',
]
```

### 2. Percorsi CSS Errati
```css
/* ❌ ERRATO: Import relativi */
@import '@filamentphp/forms/dist/index.css';

/* ✅ CORRETTO: Import da node_modules */
@import 'node_modules/@filamentphp/forms/dist/index.css';
```

## Build Process

### 1. Installazione
```bash
# Nella directory del tema
cd laravel/Themes/Sixteen

# Installa dipendenze
yarn install

# Pulisci cache
yarn cache clean

# Build e copia
yarn run build && yarn run copy
```

### 2. Verifica Build
```bash
# Controlla output directory
ls resources/dist/css

# Verifica file generati
ls public_html/themes/Sixteen/dist
```

## Best Practices

1. **Struttura Assets**:
   ```
   resources/
   ├── css/
   │   └── app.css    # Import da node_modules
   ├── js/
   │   └── app.js
   └── dist/          # Output compilato
   ```

2. **Ordine Import**:
   ```css
   /* 1. Vendor CSS da node_modules */
   @import 'node_modules/@filamentphp/forms/dist/index.css';
   
   /* 2. Tailwind */
   @tailwind base;
   
   /* 3. Custom CSS */
   @layer components {
       /* ... */
   }
   ```

3. **Script Package.json**:
   ```json
   {
       "scripts": {
           "build": "vite build",
           "copy": "cp -r ./resources/dist/* ../../../public_html/themes/Sixteen/dist"
       }
   }
   ```

4. **Verifica Installazione**:
   ```bash
   # Lista dipendenze
   yarn list | grep @filamentphp
   
   # Verifica node_modules
   ls node_modules/@filamentphp
   ```
```

# Configurazione Tailwind con Filament

## Setup Colori

### 1. tailwind.config.js
```javascript
import colors from 'tailwindcss/colors';

export default {
    theme: {
        extend: {
            colors: {
                primary: colors.blue,    // Colore primario
                secondary: colors.gray,  // Colore secondario
                success: colors.green,   // Successo
                warning: colors.yellow,  // Avviso
                danger: colors.red,      // Errore
            },
        },
    },
};
```

### 2. Utilizzo in CSS
```css
/* ❌ ERRATO: Usare primary senza configurazione */
.button {
    @apply bg-primary-600;  // Non funzionerà senza config
}

/* ✅ CORRETTO: Usare colori configurati */
.button {
    @apply bg-blue-600;  // Funziona sempre
}

/* ✅ CORRETTO: Dopo la configurazione */
.button {
    @apply bg-primary-600;  // Ora funziona
}
```

## Errori Comuni

### 1. Colori Non Configurati
```javascript
// ❌ ERRATO: Mancata configurazione colori
export default {
    theme: {
        extend: {}  // Mancano i colori
    }
}

// ✅ CORRETTO: Configurazione completa
export default {
    theme: {
        extend: {
            colors: {
                // ...colori custom
            }
        }
    }
}
```

### 2. Ordine Layer
```css
/* ❌ ERRATO: Components fuori dal layer */
.button {
    @apply bg-blue-600;  // Non dovrebbe essere qui
}

/* ✅ CORRETTO: Dentro @layer components */
@layer components {
    .button {
        @apply bg-blue-600;
    }
}
```

## Best Practices

1. **Colori Semantici**:
   ```javascript
   colors: {
       primary: colors.blue,    // Azione principale
       secondary: colors.gray,  // Azione secondaria
       success: colors.green,   // Feedback positivo
       warning: colors.yellow,  // Attenzione
       danger: colors.red,      // Errore/Pericolo
   }
   ```

2. **Estensione Theme**:
   ```javascript
   theme: {
       extend: {  // Usa extend per non sovrascrivere
           colors: {
               // ...colori custom
           }
       }
   }
   ```

3. **Plugin Setup**:
   ```javascript
   plugins: [
       require('@tailwindcss/forms'),
       require('@tailwindcss/typography'),
       require('daisyui'),
   ]
   ```

4. **DaisyUI Config**:
   ```javascript
   daisyui: {
       themes: ['light', 'dark'],  // Temi supportati
   }
   ```
```

# Gestione Icone Moduli in Laraxot

## Struttura delle Icone

### 1. Posizioni Supportate
Le icone dei moduli possono essere posizionate in:
```
laravel/Modules/[ModuleName]/
├── resources/
│   └── svg/           # Directory principale per le icone SVG
│       └── [module].svg
└── assets/
    └── svg/          # Directory alternativa
        └── [module].svg
```

### 2. Convenzioni di Denominazione
- Il file SVG deve avere lo stesso nome del modulo (lowercase)
- Esempio: `Modules/Fixcity/resources/svg/fixcity.svg`

## Registrazione Automatica

### 1. XotBaseServiceProvider
Il `XotBaseServiceProvider` si occupa di registrare automaticamente le icone:

```php
namespace Modules\Xot\Providers;

abstract class XotBaseServiceProvider extends ServiceProvider
{
    protected function registerSvgPaths(): void
    {
        $moduleName = strtolower($this->name);
        
        // Percorsi possibili per le icone
        $paths = [
            module_path($this->name, 'resources/svg'),
            module_path($this->name, 'assets/svg'),
        ];

        foreach ($paths as $path) {
            if (is_dir($path)) {
                Blade::components([
                    "{$moduleName}-icon" => "svg::$moduleName",
                ]);
                
                $this->loadViewsFrom($path, 'svg');
                break;
            }
        }
    }
}
```

### 2. Utilizzo nei Template
Una volta registrata, l'icona può essere utilizzata nei template Blade:

```blade
{{-- Uso come componente --}}
<x-fixcity-icon class="w-6 h-6" />

{{-- Uso come vista --}}
@include('svg::fixcity')
```

## Best Practices

1. **Formato SVG**:
   ```xml
   <svg xmlns="http://www.w3.org/2000/svg" 
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor">
        <!-- paths -->
   </svg>
   ```

2. **Attributi Consigliati**:
   - `fill="none"` - Permette lo styling via CSS
   - `stroke="currentColor"` - Eredita il colore dal contesto
   - `stroke-width="1.5"` - Spessore linea consistente
   - `viewBox="0 0 24 24"` - Dimensioni standard

3. **Stili CSS**:
   ```css
   .module-icon {
       @apply w-6 h-6 text-current;
   }
   ```

## Troubleshooting

### 1. Icona Non Trovata
Se l'icona non viene visualizzata:
1. Verificare il percorso: `resources/svg/` o `assets/svg/`
2. Controllare il nome file (lowercase)
3. Verificare la registrazione nel ServiceProvider

### 2. Problemi di Stile
```blade
{{-- ❌ ERRATO: Dimensioni fisse --}}
<x-fixcity-icon width="24" height="24" />

{{-- ✅ CORRETTO: Classi Tailwind --}}
<x-fixcity-icon class="w-6 h-6 text-gray-500" />
```

### 3. Debug
```php
// Nel ServiceProvider
protected function registerSvgPaths(): void
{
    $paths = [
        module_path($this->name, 'resources/svg'),
        module_path($this->name, 'assets/svg'),
    ];

    foreach ($paths as $path) {
        if (is_dir($path)) {
            \Log::info("Loading SVG from: $path");
        }
    }
}
```

## Note Importanti

1. **Accessibilità**:
   ```xml
   <svg aria-hidden="true" role="img">
       <!-- paths -->
   </svg>
   ```

2. **Ottimizzazione**:
   - Usa SVGO per ottimizzare gli SVG
   - Rimuovi attributi non necessari
   - Mantieni viewBox per la scalabilità

3. **Convenzioni**:
   - Un'icona per modulo
   - Nome file lowercase
   - Stile consistente

# Estensione Classi Filament in Laraxot

## Principio Base
In Laraxot, non si estendono mai direttamente le classi Filament. Invece, si utilizzano sempre le classi base corrispondenti dal modulo Xot con il prefisso `XotBase`.

## Mappatura Classi

| Classe Filament | Classe Xot da Estendere |
|----------------|------------------------|
| `Filament\Resources\Resource` | `Modules\Xot\Filament\Resources\XotBaseResource` |
| `Filament\Resources\Pages\ListRecords` | `Modules\Xot\Filament\Resources\Pages\XotBaseListRecords` |
| `Filament\Resources\Pages\CreateRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord` |
| `Filament\Resources\Pages\EditRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord` |
| `Filament\Resources\RelationManagers\RelationManager` | `Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager` |

## Esempi di Implementazione

### 1. Resource
```php
// ❌ ERRATO
use Filament\Resources\Resource;
class MyResource extends Resource

// ✅ CORRETTO
use Modules\Xot\Filament\Resources\XotBaseResource;
class MyResource extends XotBaseResource
```

### 2. List Records
```php
// ❌ ERRATO
use Filament\Resources\Pages\ListRecords;
class ListMyRecords extends ListRecords

// ✅ CORRETTO
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
class ListMyRecords extends XotBaseListRecords
```

### 3. Relation Manager
```php
// ❌ ERRATO
use Filament\Resources\RelationManagers\RelationManager;
class MyRelationManager extends RelationManager

// ✅ CORRETTO
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
class MyRelationManager extends XotBaseRelationManager
```

## Vantaggi

1. **Consistenza**: Le classi XotBase forniscono un comportamento consistente in tutta l'applicazione

2. **Funzionalità Estese**: Le classi XotBase aggiungono funzionalità specifiche per Laraxot

3. **Manutenibilità**: Centralizza le modifiche comuni nel modulo Xot

4. **Type Safety**: Migliore supporto per il type hinting e l'analisi statica

## Best Practices

1. **Namespace**:
   ```php
   namespace Modules\MyModule\Filament\Resources;
   use Modules\Xot\Filament\Resources\XotBaseResource;
   ```

2. **Importazioni**:
   ```php
   // ✅ CORRETTO
   use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
   
   // ❌ ERRATO
   use Filament\Resources\Pages\ListRecords;
   ```

3. **Ereditarietà**:
   ```php
   // Mantieni la catena di ereditarietà
   MyResource extends XotBaseResource
   ```

## Note Importanti

1. **Compatibilità**:
   - Le classi XotBase sono compatibili con le interfacce Filament
   - Mantengono la stessa API pubblica delle classi Filament

2. **Override**:
   - Rispetta i livelli di accesso quando fai override dei metodi
   - Usa le annotazioni PHPDoc per la chiarezza

3. **Configurazione**:
   - Le classi XotBase possono avere configurazioni aggiuntive
   - Controlla la documentazione del modulo Xot per le opzioni

# XotBaseResource vs Resource Standard

## Differenze Chiave

### 1. Navigazione
```php
// ❌ ERRATO: Non definire proprietà di navigazione
class TicketResource extends XotBaseResource
{
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Segnalazioni';
}

// ✅ CORRETTO: XotBaseResource gestisce già la navigazione
class TicketResource extends XotBaseResource
{
    protected static ?string $model = Ticket::class;
}
```

### 2. Form Schema
```php
// ❌ ERRATO: Non usare form()
public static function form(Forms\Form $form): Forms\Form
{
    return $form->schema([...]);
}

// ✅ CORRETTO: Usare getFormSchema()
protected function getFormSchema(): array
{
    return [
        TextInput::make('title')->required(),
        // ...
    ];
}
```

### 3. Table Configuration
```php
// ❌ ERRATO: Non definire table() nel Resource
class TicketResource extends XotBaseResource
{
    public static function table(Table $table): Table {...}
}

// ✅ CORRETTO: Definire configurazione tabella nella ListRecords page
class ListTickets extends XotBaseListRecords
{
    protected function getTableColumns(): array {...}
    protected function getTableFilters(): array {...}
    protected function getTableActions(): array {...}
    protected function getTableBulkActions(): array {...}
}
```

## Metodi Disponibili

### XotBaseResource
1. **Schema Form**:
   ```php
   protected function getFormSchema(): array
   ```

2. **Relazioni**:
   ```php
   public static function getRelations(): array
   ```

3. **Pagine**:
   ```php
   public static function getPages(): array
   ```

### XotBaseListRecords
1. **Configurazione Tabella**:
   ```php
   protected function getTableColumns(): array
   protected function getTableFilters(): array
   protected function getTableActions(): array
   protected function getTableBulkActions(): array
   ```

## Best Practices

1. **Separazione delle Responsabilità**:
   - Resource: definisce solo model e form schema
   - ListRecords: gestisce tutta la configurazione della tabella
   - CreateRecord/EditRecord: gestiscono le operazioni CRUD

2. **Type Safety**:
   ```php
   // Usa sempre return type declarations
   protected function getFormSchema(): array
   protected function getTableColumns(): array
   ```

3. **Documentazione**:
   ```php
   /**
    * Get the form schema for the resource.
    *
    * @return array<int, \Filament\Forms\Components\Component>
    */
    protected function getFormSchema(): array
   ```

## Note Importanti

1. **Navigazione**:
   - XotBaseResource gestisce automaticamente la navigazione
   - Non definire $navigationIcon o $navigationGroup

2. **Table Configuration**:
   - Tutta la logica della tabella va nella classe ListRecords
   - Non definire configurazioni di tabella nel Resource

3. **Form Schema**:
   - Usa getFormSchema() invece di form()
   - Definisci lo schema come array di componenti

# XotBaseListRecords vs ListRecords Standard

## Differenze Chiave

### 1. Metodi per la Tabella
```php
// ❌ ERRATO: Metodi standard di Filament
protected function getTableColumns(): array
protected function getTableFilters(): array
protected function getTableActions(): array
protected function getTableBulkActions(): array

// ✅ CORRETTO: Metodi di XotBaseListRecords
protected function getListTableColumns(): array
protected function getListTableFilters(): array
protected function getListTableActions(): array
protected function getListTableBulkActions(): array
```

### 2. Implementazione Corretta
```php
class ListTickets extends XotBaseListRecords
{
    protected function getListTableColumns(): array
    {
        return [
            TextColumn::make('id')->sortable(),
            // ...altre colonne
        ];
    }

    protected function getListTableFilters(): array
    {
        return [
            SelectFilter::make('status'),
            // ...altri filtri
        ];
    }
}
```

## Metodi Disponibili in XotBaseListRecords

### 1. Colonne e Filtri
```php
/**
 * Get the table columns for the list view.
 *
 * @return array<int, Column>
 */
protected function getListTableColumns(): array

/**
 * Get the table filters for the list view.
 *
 * @return array<int, Filter>
 */
protected function getListTableFilters(): array
```

### 2. Azioni
```php
/**
 * Get the table actions for the list view.
 *
 * @return array<int, Action>
 */
protected function getListTableActions(): array

/**
 * Get the table bulk actions for the list view.
 *
 * @return array<int, BulkAction>
 */
protected function getListTableBulkActions(): array
```

## Best Practices

1. **Nomenclatura**:
   ```php
   // ✅ CORRETTO: Usa sempre il prefisso "List"
   protected function getListTableColumns(): array
   
   // ❌ ERRATO: Non usare i metodi standard di Filament
   protected function getTableColumns(): array
   ```

2. **Type Safety**:
   ```php
   /**
    * @return array<int, Column>
    */
   protected function getListTableColumns(): array
   {
       return [
           TextColumn::make('id')->sortable(),
       ];
   }
   ```

3. **Documentazione**:
   ```php
   /**
    * Get the table columns for the list view.
    *
    * @return array<int, \Filament\Tables\Columns\Column>
    */
   protected function getListTableColumns(): array
   ```

## Note Importanti

1. **Prefisso List**:
   - Tutti i metodi relativi alla tabella hanno il prefisso "List"
   - Questo distingue i metodi di XotBaseListRecords da quelli standard

2. **Ereditarietà**:
   - XotBaseListRecords estende la classe base di Filament
   - Aggiunge funzionalità specifiche per Laraxot
   - Mantiene la compatibilità con l'API di Filament

3. **Override**:
   - Rispetta i nomi dei metodi di XotBaseListRecords
   - Non fare override dei metodi standard di Filament
   - Usa le annotazioni PHPDoc per la chiarezza

4. **Configurazione**:
   - Le configurazioni della tabella vanno nei metodi "List"
   - Non usare i metodi standard di Filament
   - Rispetta la struttura di XotBaseListRecords

# Metodi Statici in XotBaseResource

## Differenze Chiave

### 1. Metodi Form Schema
```php
// ❌ ERRATO: Non definire getFormSchema come non statico
protected function getFormSchema(): array

// ✅ CORRETTO: getFormSchema deve essere statico
public static function getFormSchema(): array
```

### 2. Implementazione Corretta
```php
class TicketResource extends XotBaseResource
{
    protected static ?string $model = Ticket::class;

    // ✅ CORRETTO: Metodo statico
    public static function getFormSchema(): array 
    {
        return [
            TextInput::make('title')->required(),
            // ...
        ];
    }
}
```

## Errori Comuni

### 1. Visibilità e Staticità
```php
// ❌ ERRATO: protected e non statico
protected function getFormSchema(): array

// ❌ ERRATO: public ma non statico
public function getFormSchema(): array

// ✅ CORRETTO: public e statico
public static function getFormSchema(): array
```

### 2. Accesso a Proprietà
```php
// ❌ ERRATO: Accesso a $this in metodo statico
public static function getFormSchema(): array
{
    return [
        TextInput::make('name')
            ->default($this->getDefaultName())  // Non funziona!
    ];
}

// ✅ CORRETTO: Usa metodi statici o proprietà statiche
public static function getFormSchema(): array
{
    return [
        TextInput::make('name')
            ->default(static::getDefaultName())
    ];
}
```

## Best Practices

1. **Dichiarazione Metodi**:
   ```php
   /**
    * Get the form schema for the resource.
    *
    * @return array<int, \Filament\Forms\Components\Component>
    */
   public static function getFormSchema(): array
   {
       return [
           // schema components
       ];
   }
   ```

2. **Metodi Helper**:
   ```php
   // Se servono metodi helper, farli statici
   protected static function getDefaultName(): string
   {
       return 'Default Name';
   }
   ```

3. **Proprietà Statiche**:
   ```php
   protected static ?string $model = Ticket::class;
   ```

## Note Importanti

1. **Ereditarietà**:
   - Rispetta la staticità dei metodi della classe padre
   - Non cambiare la visibilità dei metodi ereditati
   - Mantieni la coerenza con XotBaseResource

2. **Type Safety**:
   ```php
   // Usa sempre return type declarations
   public static function getFormSchema(): array
   ```

3. **Documentazione**:
   ```php
   /**
    * @return array<int, \Filament\Forms\Components\Component>
    */
   public static function getFormSchema(): array
   ```

4. **Contesto Statico**:
   - Non usare $this nei metodi statici
   - Usa static:: o self:: per riferimenti alla classe
   - Accedi solo a proprietà e metodi statici
```

# Livelli di Accesso in XotBaseListRecords

## Principio Fondamentale
Quando si estende una classe base, i metodi sovrascritti devono mantenere lo stesso livello di accesso (o più permissivo) della classe padre.

## Esempi

### 1. Metodi della Tabella
```php
// ❌ ERRATO: Livello di accesso più restrittivo
protected function getListTableColumns(): array

// ✅ CORRETTO: Stesso livello di accesso della classe padre
public function getListTableColumns(): array
```

### 2. Implementazione Corretta
```php
class ListTickets extends XotBaseListRecords
{
    // ✅ CORRETTO: public come nella classe padre
    public function getListTableColumns(): array
    {
        return [
            TextColumn::make('id')->sortable(),
            // ...
        ];
    }

    // ❌ ERRATO: protected è più restrittivo
    protected function getListTableColumns(): array
    {
        // ...
    }
}
```

## Errori Comuni

### 1. Visibilità dei Metodi
```php
// In XotBaseListRecords (classe padre)
public function getListTableColumns(): array

// ❌ ERRATO: Non puoi restringere l'accesso
class MyListRecords extends XotBaseListRecords
{
    protected function getListTableColumns(): array  // Errore!
    {
        // ...
    }
}

// ✅ CORRETTO: Mantieni lo stesso livello di accesso
class MyListRecords extends XotBaseListRecords
{
    public function getListTableColumns(): array
    {
        // ...
    }
}
```

### 2. Documentazione
```php
/**
 * Get the table columns for the list view.
 * 
 * IMPORTANTE: Questo metodo deve essere public per rispettare
 * il contratto con la classe padre XotBaseListRecords.
 *
 * @return array<int, \Filament\Tables\Columns\Column>
 */
public function getListTableColumns(): array
```

## Best Practices

1. **Verifica della Classe Padre**:
   ```php
   // Prima di implementare, controlla la visibilità nella classe padre
   public function getListTableColumns(): array  // Deve essere public
   ```

2. **PHPDoc Completo**:
   ```php
   /**
    * @inheritdoc
    * @return array<int, \Filament\Tables\Columns\Column>
    */
   public function getListTableColumns(): array
   ```

3. **Type Safety**:
   ```php
   // Mantieni i type hints e return types
   public function getListTableColumns(): array
   ```

## Note Importanti

1. **Regola PHP**:
   - Un metodo che estende un metodo della classe padre deve mantenere o allargare la visibilità
   - Non può restringere la visibilità

2. **Visibilità Permesse**:
   - Se il metodo padre è `public`, il metodo figlio deve essere `public`
   - Se il metodo padre è `protected`, il metodo figlio può essere `protected` o `public`
   - Se il metodo padre è `private`, non può essere esteso

3. **Controlli da Fare**:
   - Verifica sempre la visibilità dei metodi nella classe padre
   - Usa l'IDE per controllare la compatibilità
   - Mantieni la coerenza con l'API pubblica
```

# Namespace in Xot

## Struttura dei Namespace

### 1. Resources e Pages
```php
// Resources
use Modules\Xot\Filament\Resources\XotBaseResource;

// Pages
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
```

### 2. RelationManagers
```php
// ❌ ERRATO: Namespace non corretto
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

// ✅ CORRETTO: Namespace corretto
use Modules\Xot\Filament\RelationManagers\XotBaseRelationManager;
```

## Mappatura Namespace

| Componente | Namespace |
|------------|-----------|
| Resources | `Modules\Xot\Filament\Resources` |
| Pages | `Modules\Xot\Filament\Resources\Pages` |
| RelationManagers | `Modules\Xot\Filament\RelationManagers` |

## Esempi di Implementazione

### 1. Resource
```php
use Modules\Xot\Filament\Resources\XotBaseResource;

class TicketResource extends XotBaseResource
{
    // ...
}
```

### 2. Pages
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListTickets extends XotBaseListRecords
{
    // ...
}
```

### 3. RelationManager
```php
use Modules\Xot\Filament\RelationManagers\XotBaseRelationManager;

class CommentsRelationManager extends XotBaseRelationManager
{
    // ...
}
```

## Best Practices

1. **Import Espliciti**:
   ```php
   // ✅ CORRETTO: Import esplicito
   use Modules\Xot\Filament\RelationManagers\XotBaseRelationManager;
   
   // ❌ ERRATO: Import con namespace errato
   use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
   ```

2. **Organizzazione File**:
   ```
   Modules/YourModule/
   ├── Filament/
   │   ├── Resources/
   │   │   ├── YourResource.php
   │   │   ├── Pages/
   │   │   └── RelationManagers/
   │   └── Pages/
   ```

3. **Documentazione**:
   ```php
   /**
    * @extends \Modules\Xot\Filament\RelationManagers\XotBaseRelationManager
    */
   class CommentsRelationManager extends XotBaseRelationManager
   ```

## Note Importanti

1. **Struttura Xot**:
   - RelationManagers hanno un namespace dedicato
   - Non sono sotto Resources come in Filament standard
   - Mantengono la stessa API di Filament

2. **Compatibilità**:
   - Le classi base di Xot estendono quelle di Filament
   - Mantengono la stessa interfaccia pubblica
   - Aggiungono funzionalità specifiche per Xot

3. **Verifica Namespace**:
   - Controlla sempre il namespace corretto in Xot
   - Usa l'autocompletamento dell'IDE
   - Verifica che le classi siano trovate
```

# RelationManager in Filament/Xot

## Importante Nota sui Namespace

### ❌ Namespace Errati da Non Usare
```php
// ❌ ERRATO: Questi namespace non esistono
use Modules\Xot\Filament\RelationManagers\XotBaseRelationManager;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
```

### ✅ Namespace Corretti da Usare
```php
// ✅ CORRETTO: Usa il RelationManager standard di Filament
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    // ...
}
```

## Implementazione Corretta

### 1. Definizione Base
```php
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    
    // ... resto del codice
}
```

### 2. Form Schema
```php
public function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            RichEditor::make('content')
                ->required(),
            // ... altri campi
        ]);
}
```

### 3. Table Configuration
```php
public function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            TextColumn::make('user.name'),
            TextColumn::make('content'),
            // ... altre colonne
        ])
        ->filters([
            // ... filtri
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
}
```

## Best Practices

1. **Namespace e Import**:
   ```php
   use Filament\Resources\RelationManagers\RelationManager;
   use Filament\Forms\Components\RichEditor;
   use Filament\Tables\Columns\TextColumn;
   ```

2. **Type Hints**:
   ```php
   public function form(Forms\Form $form): Forms\Form
   public function table(Tables\Table $table): Tables\Table
   ```

3. **Relationship Definition**:
   ```php
   protected static string $relationship = 'comments';
   ```

## Note Importanti

1. **Estensione Corretta**:
   - Estendi sempre `Filament\Resources\RelationManagers\RelationManager`
   - Non cercare classi base in Xot per i RelationManager

2. **Configurazione**:
   - Definisci sempre la proprietà `$relationship`
   - Implementa i metodi `form()` e `table()`
   - Usa i componenti Filament standard

3. **Validazione**:
   ```php
   Forms\Components\RichEditor::make('content')
       ->required()
       ->maxLength(65535)
   ```

4. **Actions**:
   ```php
   ->actions([
       Tables\Actions\EditAction::make(),
       Tables\Actions\DeleteAction::make(),
   ])
   ```

# Pagine View in XotBaseResource

## Struttura delle Pagine View

### 1. Definizione Base
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewTicket extends XotBaseViewRecord
{
    protected static string $resource = TicketResource::class;
}
```

### 2. Registrazione nel Resource
```php
public static function getPages(): array
{
    return [
        'index' => Pages\ListTickets::route('/'),
        'create' => Pages\CreateTicket::route('/create'),
        'edit' => Pages\EditTicket::route('/{record}/edit'),
        'view' => Pages\ViewTicket::route('/{record}'),  // Pagina View
    ];
}
```

## Personalizzazione Vista

### 1. InfoList Schema Base
```php
public function getInfolistSchema(): array
{
    return [
        Section::make('Dettagli Ticket')
            ->schema([
                TextEntry::make('title')
                    ->label('Titolo'),
                TextEntry::make('content')
                    ->label('Contenuto')
                    ->markdown(),
                TextEntry::make('status')
                    ->badge(),
            ]),
    ];
}
```

### 2. Tabs e Sections
```php
public function getInfolistSchema(): array
{
    return [
        Tabs::make('Dettagli')
            ->tabs([
                Tab::make('Generale')
                    ->schema([
                        // schema generale
                    ]),
                Tab::make('Commenti')
                    ->schema([
                        // schema commenti
                    ]),
            ]),
    ];
}
```

## Best Practices

1. **Namespace**:
   ```php
   namespace Modules\YourModule\Filament\Resources\YourResource\Pages;
   
   use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
   ```

2. **Resource Reference**:
   ```php
   protected static string $resource = YourResource::class;
   ```

3. **View Customization**:
   ```php
   protected function getHeaderActions(): array
   {
       return [
           Actions\EditAction::make(),
           Actions\DeleteAction::make(),
       ];
   }
   ```

## Note Importanti

1. **Struttura File**:
   ```
   YourModule/
   ├── Filament/
   │   └── Resources/
   │       └── YourResource/
   │           └── Pages/
   │               └── ViewYourModel.php
   ```

2. **Convenzioni**:
   - Nome classe: `View{ModelName}`
   - Namespace: `...\Resources\{Resource}\Pages`
   - Estende: `XotBaseViewRecord`

3. **Funzionalità**:
   - Vista dettagliata record
   - Azioni intestazione
   - Schema InfoList personalizzabile
   - Tabs e sezioni

# RelationManager in Filament

## Struttura dei File

### 1. Posizione Corretta
```
Modules/YourModule/
└── app/
    └── Filament/
        └── Resources/
            └── RelationManagers/
                └── CommentsRelationManager.php
```

### 2. Namespace Corretto
```php
namespace Modules\YourModule\Filament\Resources\RelationManagers;
```

## Implementazione

### 1. Definizione Base
```php
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
}
```

### 2. Form Schema
```php
public function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            RichEditor::make('content')
                ->required()
                ->maxLength(65535),
        ]);
}
```

### 3. Table Configuration
```php
public function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            TextColumn::make('user.name'),
            TextColumn::make('content')->limit(50),
            TextColumn::make('created_at')->dateTime(),
        ])
        ->filters([
            // ...
        ])
        ->headerActions([
            CreateAction::make(),
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
}
```

## Registrazione nel Resource

```php
class TicketResource extends XotBaseResource
{
    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }
}
```

## Best Practices

1. **Namespace e Import**:
   ```php
   use Filament\Resources\RelationManagers\RelationManager;
   use Filament\Forms;
   use Filament\Tables;
   ```

2. **Relationship Definition**:
   ```php
   protected static string $relationship = 'comments';  // Nome esatto della relazione nel Model
   ```

3. **Type Safety**:
   ```php
   public function form(Forms\Form $form): Forms\Form
   public function table(Tables\Table $table): Tables\Table
   ```

## Errori Comuni

### 1. Namespace Errato
```php
// ❌ ERRATO
namespace Modules\YourModule\RelationManagers;

// ✅ CORRETTO
namespace Modules\YourModule\Filament\Resources\RelationManagers;
```

### 2. Nome Relazione Errato
```php
// ❌ ERRATO: Non corrisponde al nome della relazione nel Model
protected static string $relationship = 'comment';

// ✅ CORRETTO: Corrisponde esattamente al nome nel Model
protected static string $relationship = 'comments';
```

### 3. Registrazione Mancante
```php
// ❌ ERRATO: RelationManager non registrato
public static function getRelations(): array
{
    return [];
}

// ✅ CORRETTO: RelationManager registrato
public static function getRelations(): array
{
    return [
        RelationManagers\CommentsRelationManager::class,
    ];
}
```

## Note Importanti

1. **Relazioni nel Model**:
   - Verifica che la relazione esista nel Model
   - Il nome deve corrispondere esattamente
   - Usa il nome plurale per relazioni hasMany

2. **Validazione**:
   - Aggiungi sempre validazione nei form
   - Usa maxLength per i campi di testo
   - Gestisci i campi required

3. **Performance**:
   - Usa limit() per campi di testo lunghi
   - Configura correttamente i filtri
   - Ottimizza le query delle relazioni

4. **Sicurezza**:
   - Controlla i permessi per le azioni
   - Valida tutti gli input
   - Filtra i dati sensibili
```

# Struttura RelationManager in Filament

## Posizione Corretta dei File

### ✅ CORRETTO: Sotto il Resource specifico
```
Modules/YourModule/
└── app/
    └── Filament/
        └── Resources/
            └── TicketResource/           # Resource specifico
                └── RelationManagers/     # RelationManagers del Resource
                    └── CommentsRelationManager.php
```

### ❌ ERRATO: Direttamente sotto Resources
```
Modules/YourModule/
└── app/
    └── Filament/
        └── Resources/
            └── RelationManagers/        # Non mettere qui!
                └── CommentsRelationManager.php
```

## Namespace Corretto

### ✅ CORRETTO: Include il Resource nel namespace
```php
namespace Modules\Fixcity\Filament\Resources\TicketResource\RelationManagers;
```

### ❌ ERRATO: Namespace generico
```php
namespace Modules\Fixcity\Filament\Resources\RelationManagers;
```

## Best Practices

1. **Organizzazione**:
   - Ogni RelationManager deve essere sotto il suo Resource specifico
   - Il namespace deve riflettere la struttura delle directory
   - Mantieni la coerenza con la struttura Filament

2. **Registrazione**:
   ```php
   // In TicketResource
   public static function getRelations(): array
   {
       return [
           RelationManagers\CommentsRelationManager::class,
       ];
   }
   ```

3. **Importazioni**:
   ```php
   use Filament\Resources\RelationManagers\RelationManager;
   use Filament\Forms;
   use Filament\Tables;
   ```

## Note Importanti

1. **Struttura Directory**:
   - Segui la struttura standard di Filament
   - Mantieni i RelationManager vicini al loro Resource
   - Usa namespace appropriati che riflettono la struttura

2. **Convenzioni di Denominazione**:
   - NomeResource/RelationManagers/NomeRelationManager
   - Il namespace deve corrispondere al percorso del file
   - Usa nomi descrittivi e coerenti

# Gestione Allegati in Filament

## RelationManager per Allegati

### 1. Struttura Base
```php
namespace Modules\YourModule\Filament\Resources\YourResource\RelationManagers;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';
}
```

### 2. Form Schema per Allegati
```php
public function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            FileUpload::make('file')
                ->required()
                ->directory('custom/path')
                ->preserveFilenames()
                ->acceptedFileTypes(['application/pdf', 'image/*']),
                
            TextInput::make('description')
                ->maxLength(255),
        ]);
}
```

### 3. Table Configuration
```php
public function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            TextColumn::make('filename'),
            TextColumn::make('description'),
            TextColumn::make('created_at')->dateTime(),
        ])
        ->actions([
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ]);
}
```

## Best Practices

1. **Gestione File**:
   ```php
   FileUpload::make('file')
       ->directory('tickets/attachments')  // Directory dedicata
       ->preserveFilenames()              // Mantieni nomi file
       ->maxSize(10240)                   // Limite dimensione (10MB)
       ->acceptedFileTypes([              // Tipi permessi
           'application/pdf',
           'image/*',
           'application/msword',
           'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
       ])
   ```

2. **Validazione**:
   ```php
   TextInput::make('description')
       ->maxLength(255)
       ->rules(['string', 'max:255'])
   ```

3. **Sicurezza**:
   ```php
   // Limitare tipi di file
   ->acceptedFileTypes([...])
   
   // Limitare dimensione
   ->maxSize(10240)
   
   // Sanitizzare nomi file
   ->preserveFilenames(false)
   ```

## Note Importanti

1. **Storage**:
   - Usa directory dedicate per tipo di allegato
   - Implementa policy di pulizia
   - Gestisci permessi di accesso

2. **Performance**:
   - Limita dimensioni file
   - Ottimizza immagini
   - Usa lazy loading per preview

3. **Sicurezza**:
   - Valida tipi MIME
   - Scansiona virus/malware
   - Implementa permessi granulari

4. **UI/UX**:
   - Mostra preview quando possibile
   - Fornisci feedback upload
   - Permetti download facile

## Errori Comuni

### 1. Directory Non Sicure
```php
// ❌ ERRATO: Directory non strutturata
->directory('uploads')

// ✅ CORRETTO: Directory strutturata e dedicata
->directory('tickets/attachments/' . $ticketId)
```

### 2. Validazione Insufficiente
```php
// ❌ ERRATO: Accetta qualsiasi file
->acceptedFileTypes(['*/*'])

// ✅ CORRETTO: Tipi specifici
->acceptedFileTypes([
    'application/pdf',
    'image/*',
    'application/msword'
])
```

### 3. Gestione Nomi File
```php
// ❌ ERRATO: Nomi file non sanitizzati
->preserveFilenames()

// ✅ CORRETTO: Sanitizzazione nomi
->preserveFilenames(false)
->storeFileNamesIn('original_filename')
```

## Implementazione Model

```php
// In Ticket.php
public function attachments(): HasMany
{
    return $this->hasMany(Attachment::class);
}

// In Attachment.php
protected $fillable = [
    'file',
    'description',
    'original_filename',
    'mime_type',
    'size',
];

protected $casts = [
    'size' => 'integer',
    'created_at' => 'datetime',
];
```

# Azioni Filament

## Pattern per Azioni Personalizzate

### 1. Struttura Base
```
Module/
└── app/
    └── Filament/
        └── Resources/
            └── YourResource/
                ├── Actions/           # Directory dedicata alle azioni
                │   └── CustomAction.php
                └── Pages/
                    └── ListRecords.php
```

### 2. Definizione dell'Azione
```php
// Actions/CustomAction.php
class CustomAction 
{
    use QueueableAction;

    public function __construct(
        protected Faker\Generator $faker,  // Inietta dipendenze necessarie
    ) {}

    public function execute(array $data): mixed
    {
        // Logica dell'azione
    }
}
```

### 3. Integrazione nella Pagina
```php
// Pages/ListRecords.php

// ❌ ERRATO: Passaggio diretto dell'Action come parametro
->action(function (array $data, CustomAction $action) {
    $action->execute($data);
})

// ✅ CORRETTO: Risoluzione tramite container
->action(function (array $data) {
    $action = app(CustomAction::class);
    $action->execute($data);
})
```

### 4. Best Practices

1. **Dependency Injection**:
   ```php
   class CustomAction 
   {
       public function __construct(
           protected Service $service,
           protected Repository $repository,
       ) {}
   }
   ```

2. **Risoluzione delle Dipendenze**:
   ```php
   // ✅ CORRETTO: Lascia che il container gestisca le dipendenze
   $action = app(CustomAction::class);
   
   // ❌ ERRATO: Istanziazione manuale
   $action = new CustomAction();
   ```

3. **Gestione degli Stati**:
   ```php
   class CustomAction 
   {
       protected array $state = [];
       
       public function withState(array $state): self
       {
           $this->state = $state;
           return $this;
       }
   }
   ```

### 5. Note Importanti

1. **Risoluzione Azioni**:
   - Usa sempre il container per risolvere le azioni
   - Evita dependency injection diretta nei closure
   - Permetti al container di gestire le dipendenze

2. **Separazione delle Responsabilità**:
   - Azioni in file dedicati
   - Una singola responsabilità per azione
   - Logica di business isolata

3. **Testing**:
   ```php
   public function test_custom_action(): void
   {
       $action = app(CustomAction::class);
       $result = $action->execute(['param' => 'value']);
       
       $this->assertExpected($result);
   }
   ```

# Notifiche in Filament 3

## Differenze con Filament 2

### ❌ Filament 2 (Vecchio Modo)
```php
$this->notify('success', 'Messaggio');  // Non funziona più in Filament 3
```

### ✅ Filament 3 (Modo Corretto)
```php
use Filament\Notifications\Notification;

Notification::make()
    ->success()
    ->title('Titolo')
    ->body('Messaggio opzionale')
    ->send();
```

## Tipi di Notifica

```php
// Successo
Notification::make()
    ->success()
    ->title('Operazione completata')
    ->send();

// Errore
Notification::make()
    ->danger()
    ->title('Si è verificato un errore')
    ->send();

// Warning
Notification::make()
    ->warning()
    ->title('Attenzione')
    ->send();

// Info
Notification::make()
    ->info()
    ->title('Informazione')
    ->send();
```

## Personalizzazioni

### 1. Con Body
```php
Notification::make()
    ->success()
    ->title('Titolo')
    ->body('Descrizione dettagliata...')
    ->send();
```

### 2. Con Durata
```php
Notification::make()
    ->success()
    ->title('Titolo')
    ->duration(5000) // 5 secondi
    ->send();
```

### 3. Con Azioni
```php
Notification::make()
    ->success()
    ->title('Operazione completata')
    ->actions([
        Action::make('view')
            ->button()
            ->label('Visualizza')
            ->url(route('tickets.show', $ticket)),
    ])
    ->send();
```

## Best Practices

1. **Import Corretto**:
   ```php
   use Filament\Notifications\Notification;
   ```

2. **Chaining Methods**:
   ```php
   Notification::make()
       ->success()
       ->title('Titolo')
       ->body('Messaggio')
       ->duration(5000)
       ->send();
   ```

3. **Gestione Errori**:
   ```php
   try {
       // operazione
       Notification::make()
           ->success()
           ->title('Successo')
           ->send();
   } catch (\Exception $e) {
       Notification::make()
           ->danger()
           ->title('Errore')
           ->body($e->getMessage())
           ->send();
   }
   ```

## Note Importanti

1. **Differenze da Filament 2**:
   - Non usare più il metodo `notify()`
   - Usa sempre `Notification::make()`
   - Configura le notifiche usando il method chaining

2. **Persistenza**:
   - Le notifiche sono temporanee di default
   - Usa `persistent()` per notifiche permanenti
   - Configura la durata con `duration()`

3. **Accessibilità**:
   - Fornisci sempre un titolo chiaro
   - Usa il body per dettagli aggiuntivi
   - Scegli il tipo appropriato (success/danger/warning/info)
```

# Configurazione Resource in Laraxot

## Navigazione e Configurazione

### ❌ Modo Errato (Filament Standard)
```php
class TicketResource extends XotBaseResource
{
    // ❌ ERRATO: Non definire qui la navigazione
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Segnalazioni';
    
    // ❌ ERRATO: Non definire qui la configurazione tabella
    public static function table(Table $table): Table
    {
        return $table->columns([...]);
    }
}
```

### ✅ Modo Corretto (Laraxot)

1. **Configurazione nel file di lingua**:
```php
// lang/it/ticket.php
return [
    'navigation' => [
        'label' => 'Tickets',
        'group' => 'Segnalazioni',
        'icon' => 'heroicon-o-ticket',
        'sort' => 83,  // Ordine nel menu
    ],
];
```

2. **Resource Pulito**:
```php
class TicketResource extends XotBaseResource
{
    protected static ?string $model = Ticket::class;

    public static function getFormSchema(): array
    {
        return [...];
    }
}
```

3. **Configurazione Tabella in ListRecords**:
```php
class ListTickets extends XotBaseListRecords
{
    public function getListTableColumns(): array
    {
        return [...];
    }
}
```

## Note Importanti

1. **Separazione delle Responsabilità**:
   - Resource: definisce solo il modello e lo schema del form
   - Traduzioni: gestiscono la navigazione e le etichette
   - ListRecords: gestisce la configurazione della tabella

2. **Vantaggi**:
   - Configurazione centralizzata nelle traduzioni
   - Maggiore flessibilità nella personalizzazione
   - Supporto multilingua nativo
   - Coerenza in tutta l'applicazione

3. **File di Configurazione**:
   - Usa i file di lingua per le etichette
   - Mantieni il Resource pulito e focalizzato
   - Segui le convenzioni Laraxot
```

# Struttura Actions in Laraxot

## Posizione Corretta delle Actions

### ❌ ERRATO: Sotto il Resource
```
Modules/YourModule/
└── app/
    └── Filament/
        └── Resources/
            └── YourResource/
                └── Actions/           # ❌ NON QUI!
```

### ✅ CORRETTO: Direttamente sotto app/Actions
```
Modules/YourModule/
└── app/
    ├── Actions/                    # ✅ CORRETTO: Actions a livello app
    │   ├── GenerateTicketsAction.php
    │   └── Other/                 # Sottocartelle per organizzazione
    └── Filament/
        └── Resources/
```

## Motivazioni

1. **Riusabilità**:
   - Le Actions sono componenti di business logic
   - Possono essere usate da più contesti (non solo Filament)
   - Devono essere indipendenti dall'UI

2. **Separazione delle Responsabilità**:
   - Filament/Resources: gestione UI e presentazione
   - Actions: logica di business
   - Models: logica del dominio

3. **Testing**:
   - Actions facilmente testabili in isolamento
   - Nessuna dipendenza da Filament
   - Migliore organizzazione dei test

## Best Practices

1. **Namespace**:
```php
// ❌ ERRATO
namespace Modules\YourModule\Filament\Resources\YourResource\Actions;

// ✅ CORRETTO
namespace Modules\YourModule\Actions;
```

2. **Struttura**:
```php
namespace Modules\YourModule\Actions;

class GenerateTicketsAction
{
    use QueueableAction;
    
    public function execute(int $count): void
    {
        // Logica di business pura
    }
}
```

3. **Utilizzo**:
```php
// In Filament/Resources/Pages/ListRecords.php
use Modules\YourModule\Actions\GenerateTicketsAction;

protected function getHeaderActions(): array
{
    return [
        Action::make('generate')
            ->action(function (array $data) {
                app(GenerateTicketsAction::class)->execute($data['count']);
            })
    ];
}
```

## Note Importanti

1. **Indipendenza**:
   - Actions non devono dipendere da Filament
   - Evitare riferimenti all'UI nelle Actions
   - Mantenere la logica di business pura

2. **Organizzazione**:
   - Raggruppare Actions correlate in sottocartelle
   - Usare namespace appropriati
   - Seguire le convenzioni del modulo

3. **Convenzioni**:
   - Nome: `{Verb}{Noun}Action`
   - Namespace: `Modules\{Module}\Actions`
   - Un'azione = una responsabilità
```

# Gestione Relazioni in Filament Forms

## Problemi Comuni e Soluzioni

### 1. Errore "Call to a member function getResults() on null"

Questo errore si verifica quando:
- La relazione non è definita correttamente nel modello
- Il record correlato non esiste
- Il caricamento della relazione fallisce

#### Soluzione

1. **Definizione Corretta della Relazione**:
```php
// Nel modello
public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}
```

2. **Configurazione del Select nel Form**:
```php
Forms\Components\Select::make('category_id')
    ->relationship(
        name: 'category',
        titleAttribute: 'name',
    )
    ->searchable()
    ->preload()  // Precarica i dati
    ->required()
```

3. **Best Practices**:
   - Usa sempre `preload()` per relazioni piccole
   - Implementa `searchable()` per relazioni grandi
   - Verifica l'esistenza dei dati correlati
   - Gestisci i casi di dati mancanti

### 2. Ottimizzazione Performance

1. **Eager Loading**:
```php
protected function getTableQuery(): Builder
{
    return parent::getTableQuery()
        ->with(['category', 'user']);  // Eager load relazioni
}
```

2. **Lazy Loading Selettivo**:
```php
Forms\Components\Select::make('category_id')
    ->relationship(
        name: 'category',
        titleAttribute: 'name',
        modifyQueryUsing: fn (Builder $query) => $query->active()
    )
```

3. **Caching**:
```php
Forms\Components\Select::make('category_id')
    ->options(
        Cache::remember(
            'categories',
            now()->addHour(),
            fn() => Category::pluck('name', 'id')->toArray()
        )
    )
```

### 3. Validazione e Sicurezza

1. **Validazione Esistenza**:
```php
Forms\Components\Select::make('category_id')
    ->exists('categories')  // Verifica esistenza
```

2. **Autorizzazioni**:
```php
Forms\Components\Select::make('category_id')
    ->visible(fn () => auth()->user()->can('view', Category::class))
```

3. **Filtri**:
```php
Forms\Components\Select::make('category_id')
    ->relationship(
        name: 'category',
        modifyQueryUsing: fn (Builder $query) => $query->whereActive(true)
    )
```
```

# Gestione Trait Conflicts in Laravel

## Risoluzione Conflitti tra Trait

### 1. Problema Comune
Quando due trait definiscono lo stesso metodo, si verifica un conflitto:

```php
// ❌ ERRATO: Conflitto tra metodi notifications()
class User extends Authenticatable
{
    use Notifiable;
    use InteractsWithComments;  // Conflitto!
}
```

### 2. Soluzione con insteadof e as
```php
// ✅ CORRETTO: Risoluzione esplicita dei conflitti
class BaseUser extends Authenticatable
{
    use Notifiable;
    use InteractsWithComments {
        // Usa notifications di InteractsWithComments
        InteractsWithComments::notifications insteadof Notifiable;
        // Rinomina notifications di Notifiable
        Notifiable::notifications as protected notificationRelation;
    }

    /**
     * Override per mantenere la type-safety
     *
     * @return MorphMany
     */
    public function notifications(): MorphMany
    {
        return $this->notificationRelation();
    }
}
```

## Best Practices

1. **Dichiarazione Esplicita dei Tipi**:
   ```php
   use Illuminate\Database\Eloquent\Relations\MorphMany;
   
   public function notifications(): MorphMany
   {
       // Return type dichiarato
   }
   ```

2. **Protezione dei Metodi**:
   ```php
   // Metodo originale reso protected
   Notifiable::notifications as protected notificationRelation;
   ```

3. **Documentazione PHPDoc**:
   ```php
   /**
    * Get the entity's notifications.
    *
    * @return \Illuminate\Database\Eloquent\Relations\MorphMany
    */
   public function notifications(): MorphMany
   ```

## Errori da Evitare

### 1. Mancata Dichiarazione dei Tipi
```php
// ❌ ERRATO: Tipo di ritorno mancante
public function notifications()
{
    return $this->notificationRelation();
}

// ✅ CORRETTO: Tipo di ritorno dichiarato
public function notifications(): MorphMany
{
    return $this->notificationRelation();
}
```

### 2. Mancata Gestione dei Conflitti
```php
// ❌ ERRATO: Conflitti non gestiti
use Notifiable;
use InteractsWithComments;

// ✅ CORRETTO: Conflitti gestiti esplicitamente
use Notifiable;
use InteractsWithComments {
    InteractsWithComments::notifications insteadof Notifiable;
}
```

### 3. Type-Safety
```php
// ❌ ERRATO: Return type non specifico
public function notifications(): mixed

// ✅ CORRETTO: Return type specifico
public function notifications(): MorphMany
```

# Gestione Notifiche in Laravel

## Setup Base

### 1. Modello Base
```php
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;

abstract class BaseUser extends Authenticatable
{
    use Notifiable;

    public function notifications(): MorphMany
    {
        return $this->morphMany(
            config('notifications.notification_model', \Illuminate\Notifications\DatabaseNotification::class),
            'notifiable'
        );
    }
}
```

### 2. Configurazione Notifiche
```php
// config/notifications.php
return [
    'notification_model' => \Illuminate\Notifications\DatabaseNotification::class,
];
```

## Estensioni Opzionali

### 1. Con Spatie Comments
Se hai bisogno della funzionalità commenti:
```bash
# 1. Installa il pacchetto
composer require spatie/laravel-comments

# 2. Pubblica la configurazione
php artisan vendor:publish --provider="Spatie\Comments\CommentsServiceProvider"
```

Poi nel modello:
```php
use Spatie\Comments\Models\Concerns\InteractsWithComments;

class BaseUser extends Authenticatable
{
    use Notifiable;
    use InteractsWithComments {
        InteractsWithComments::notifications insteadof Notifiable;
        Notifiable::notifications as protected notificationRelation;
    }
}
```

### 2. Senza Spatie Comments
Se non serve la funzionalità commenti:
```php
class BaseUser extends Authenticatable
{
    use Notifiable;  // Solo notifiche base Laravel
}
```

## Best Practices

1. **Configurazione**:
   ```php
   // Usa config() per il modello di notifica
   config('notifications.notification_model', DefaultModel::class)
   ```

2. **Type Safety**:
   ```php
   public function notifications(): MorphMany
   {
       // Return type esplicito
   }
   ```

3. **Modularità**:
   - Installa solo i pacchetti necessari
   - Usa trait solo se servono
   - Mantieni le dipendenze minime

# Autenticazione Laravel con Remember Me

## Gestione Corretta del Remember Me

### 1. Form Schema
```php
protected function getFormSchema(): array
{
    return [
        TextInput::make('email')
            ->required(),
        TextInput::make('password')
            ->required(),
        Checkbox::make('remember')  // Campo remember per "ricordami"
            ->label(__('Ricordami')),
    ];
}
```

### 2. Validazione e Autenticazione
```php
/**
 * @var array<string, mixed>
 */
protected $rules = [
    'email' => ['required', 'email'],
    'password' => ['required'],
    'remember' => ['boolean'],  // Validazione per remember
];

public function authenticate()
{
    $data = $this->validate();

    // Estrai remember dal data array
    $remember = $data['remember'] ?? false;
    unset($data['remember']);

    if (Auth::attempt($data, $remember)) {
        session()->regenerate();
        return redirect()->intended();
    }
}
```

## Errori Comuni

### 1. Remember nella Query
```php
// ❌ ERRATO: Include remember nella query
Auth::attempt([
    'email' => $email,
    'password' => $password,
    'remember' => true  // Causerà errore SQL
]);

// ✅ CORRETTO: Remember come secondo parametro
Auth::attempt([
    'email' => $email,
    'password' => $password,
], $remember);
```

### 2. Gestione Stato Remember
```php
// ❌ ERRATO: Non gestire il default
$remember = $data['remember'];  // Potrebbe non esistere

// ✅ CORRETTO: Gestire il default
$remember = $data['remember'] ?? false;
```

## Best Practices

1. **Validazione**:
   ```php
   protected $rules = [
       'remember' => ['boolean'],  // Permette true/false
   ];
   ```

2. **Separazione Dati**:
   ```php
   // Separa i dati di autenticazione da remember
   $credentials = [
       'email' => $data['email'],
       'password' => $data['password'],
   ];
   ```

3. **Type Safety**:
   ```php
   public bool $remember = false;  // Tipo esplicito
   ```

4. **Documentazione**:
   ```php
   /**
    * Handle the authentication attempt.
    *
    * @return \Illuminate\Http\RedirectResponse|void
    */
   public function authenticate()
   {
       // ...
   }
   ```

# Setup Assets Filament 3

## 1. Installazione Pacchetti NPM

### Package.json
```json
{
    "devDependencies": {
        "@filamentphp/forms": "^3.0",
        "@filamentphp/support": "^3.0",
        "@filamentphp/notifications": "^3.0"
    }
}
```

### Installazione
```bash
# Nella directory del tema
cd laravel/Themes/Sixteen

# Installa le dipendenze
yarn install

# Build e copia
yarn run build && yarn run copy
```

## 2. Importazione CSS

### Ordine Corretto
```css
/* 1. Filament CSS - Ordine importante */
@import '@filamentphp/forms/dist/index.css';
@import '@filamentphp/notifications/dist/index.css';
@import '@filamentphp/support/dist/index.css';

/* 2. Tailwind */
@tailwind base;
@tailwind components;
@tailwind utilities;

/* 3. Custom Components */
@layer components {
    .filament-button {
        @apply inline-flex items-center justify-center py-2 px-4 font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors;
    }

    .filament-button-primary {
        @apply bg-primary-600 text-white hover:bg-primary-500 focus:ring-primary-500;
    }

    /* Stili per il form di login */
    .login-form {
        @apply space-y-6;
    }

    .login-button {
        @apply w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500;
    }
}

[x-cloak] {
    display: none;
}
```

## Errori Comuni

### 1. Import Errati
```css
/* ❌ ERRATO: Import diretto da vendor */
@import '../../vendor/filament/**/*.css';

/* ✅ CORRETTO: Import da node_modules */
@import '@filamentphp/forms/dist/index.css';
```

### 2. Ordine Import
```css
/* ❌ ERRATO: Tailwind prima di Filament */
@tailwind base;
@import '@filamentphp/forms/dist/index.css';

/* ✅ CORRETTO: Filament prima di Tailwind */
@import '@filamentphp/forms/dist/index.css';
@tailwind base;
```

### 3. Pacchetti Mancanti
```json
/* ❌ ERRATO: Pacchetti incompleti */
{
    "@filamentphp/forms": "^3.0"
}

/* ✅ CORRETTO: Tutti i pacchetti necessari */
{
    "@filamentphp/forms": "^3.0",
    "@filamentphp/support": "^3.0",
    "@filamentphp/notifications": "^3.0"
}
```

## Best Practices

1. **Script di Build**:
   ```json
   {
       "scripts": {
           "build": "vite build",
           "copy": "cp -r ./resources/dist/* ../../../public_html/themes/Sixteen/dist"
       }
   }
   ```

2. **Layer Components**:
   ```css
   @layer components {
       .filament-button {
           @apply /* ... */;
       }
   }
   ```

3. **Gestione Assets**:
   ```bash
   # Development
   yarn run dev

   # Production + Copy
   yarn run build && yarn run copy
   ```

4. **Verifica Installazione**:
   ```bash
   # Controlla le dipendenze installate
   yarn list | grep @filamentphp

   # Verifica node_modules
   ls node_modules/@filamentphp
   ```
```

# Vite e Filament Assets

## Setup Corretto

### 1. vite.config.js
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: './resources/dist',
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
});
```

### 2. CSS Imports
```css
/* ❌ ERRATO: Import da vendor */
@import '../../vendor/filament/**/*.css';

/* ✅ CORRETTO: Import da node_modules */
@import 'node_modules/@filamentphp/forms/dist/index.css';
@import 'node_modules/@filamentphp/notifications/dist/index.css';
@import 'node_modules/@filamentphp/support/dist/index.css';
```

## Errori Comuni

### 1. Import Pattern Errati
```javascript
// ❌ ERRATO: Glob pattern in vite.config.js
input: [
    '../../vendor/filament/**/*.css',  // Non funzionerà
]

// ✅ CORRETTO: Input specifici
input: [
    'resources/css/app.css',
    'resources/js/app.js',
]
```

### 2. Percorsi CSS Errati
```css
/* ❌ ERRATO: Import relativi */
@import '@filamentphp/forms/dist/index.css';

/* ✅ CORRETTO: Import da node_modules */
@import 'node_modules/@filamentphp/forms/dist/index.css';
```

## Build Process

### 1. Installazione
```bash
# Nella directory del tema
cd laravel/Themes/Sixteen

# Installa dipendenze
yarn install

# Pulisci cache
yarn cache clean

# Build e copia
yarn run build && yarn run copy
```

### 2. Verifica Build
```bash
# Controlla output directory
ls resources/dist/css

# Verifica file generati
ls public_html/themes/Sixteen/dist
```

## Best Practices

1. **Struttura Assets**:
   ```
   resources/
   ├── css/
   │   └── app.css    # Import da node_modules
   ├── js/
   │   └── app.js
   └── dist/          # Output compilato
   ```

2. **Ordine Import**:
   ```css
   /* 1. Vendor CSS da node_modules */
   @import 'node_modules/@filamentphp/forms/dist/index.css';
   
   /* 2. Tailwind */
   @tailwind base;
   
   /* 3. Custom CSS */
   @layer components {
       /* ... */
   }
   ```

3. **Script Package.json**:
   ```json
   {
       "scripts": {
           "build": "vite build",
           "copy": "cp -r ./resources/dist/* ../../../public_html/themes/Sixteen/dist"
       }
   }
   ```

4. **Verifica Installazione**:
   ```bash
   # Lista dipendenze
   yarn list | grep @filamentphp
   
   # Verifica node_modules
   ls node_modules/@filamentphp
   ```
```

# Configurazione Tailwind con Filament

## Setup Colori

### 1. tailwind.config.js
```javascript
import colors from 'tailwindcss/colors';

export default {
    theme: {
        extend: {
            colors: {
                primary: colors.blue,    // Colore primario
                secondary: colors.gray,  // Colore secondario
                success: colors.green,   // Successo
                warning: colors.yellow,  // Avviso
                danger: colors.red,      // Errore
            },
        },
    },
};
```

### 2. Utilizzo in CSS
```css
/* ❌ ERRATO: Usare primary senza configurazione */
.button {
    @apply bg-primary-600;  // Non funzionerà senza config
}

/* ✅ CORRETTO: Usare colori configurati */
.button {
    @apply bg-blue-600;  // Funziona sempre
}

/* ✅ CORRETTO: Dopo la configurazione */
.button {
    @apply bg-primary-600;  // Ora funziona
}
```

## Errori Comuni

### 1. Colori Non Configurati
```javascript
// ❌ ERRATO: Mancata configurazione colori
export default {
    theme: {
        extend: {}  // Mancano i colori
    }
}

// ✅ CORRETTO: Configurazione completa
export default {
    theme: {
        extend: {
            colors: {
                // ...colori custom
            }
        }
    }
}
```

### 2. Ordine Layer
```css
/* ❌ ERRATO: Components fuori dal layer */
.button {
    @apply bg-blue-600;  // Non dovrebbe essere qui
}

/* ✅ CORRETTO: Dentro @layer components */
@layer components {
    .button {
        @apply bg-blue-600;
    }
}
```

## Best Practices

1. **Colori Semantici**:
   ```javascript
   colors: {
       primary: colors.blue,    // Azione principale
       secondary: colors.gray,  // Azione secondaria
       success: colors.green,   // Feedback positivo
       warning: colors.yellow,  // Attenzione
       danger: colors.red,      // Errore/Pericolo
   }
   ```

2. **Estensione Theme**:
   ```javascript
   theme: {
       extend: {  // Usa extend per non sovrascrivere
           colors: {
               // ...colori custom
           }
       }
   }
   ```

3. **Plugin Setup**:
   ```javascript
   plugins: [
       require('@tailwindcss/forms'),
       require('@tailwindcss/typography'),
       require('daisyui'),
   ]
   ```

4. **DaisyUI Config**:
   ```javascript
   daisyui: {
       themes: ['light', 'dark'],  // Temi supportati
   }
   ```
```

# Gestione Icone Moduli in Laraxot

## Struttura delle Icone

### 1. Posizioni Supportate
Le icone dei moduli possono essere posizionate in:
```
laravel/Modules/[ModuleName]/
├── resources/
│   └── svg/           # Directory principale per le icone SVG
│       └── [module].svg
└── assets/
    └── svg/          # Directory alternativa
        └── [module].svg
```

### 2. Convenzioni di Denominazione
- Il file SVG deve avere lo stesso nome del modulo (lowercase)
- Esempio: `Modules/Fixcity/resources/svg/fixcity.svg`

## Registrazione Automatica

### 1. XotBaseServiceProvider
Il `XotBaseServiceProvider` si occupa di registrare automaticamente le icone:

```php
namespace Modules\Xot\Providers;

abstract class XotBaseServiceProvider extends ServiceProvider
{
    protected function registerSvgPaths(): void
    {
        $moduleName = strtolower($this->name);
        
        // Percorsi possibili per le icone
        $paths = [
            module_path($this->name, 'resources/svg'),
            module_path($this->name, 'assets/svg'),
        ];

        foreach ($paths as $path) {
            if (is_dir($path)) {
                Blade::components([
                    "{$moduleName}-icon" => "svg::$moduleName",
                ]);
                
                $this->loadViewsFrom($path, 'svg');
                break;
            }
        }
    }
}
```

### 2. Utilizzo nei Template
Una volta registrata, l'icona può essere utilizzata nei template Blade:

```blade
{{-- Uso come componente --}}
<x-fixcity-icon class="w-6 h-6" />

{{-- Uso come vista --}}
@include('svg::fixcity')
```

## Best Practices

1. **Formato SVG**:
   ```xml
   <svg xmlns="http://www.w3.org/2000/svg" 
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor">
        <!-- paths -->
   </svg>
   ```

2. **Attributi Consigliati**:
   - `fill="none"` - Permette lo styling via CSS
   - `stroke="currentColor"` - Eredita il colore dal contesto
   - `stroke-width="1.5"` - Spessore linea consistente
   - `viewBox="0 0 24 24"` - Dimensioni standard

3. **Stili CSS**:
   ```css
   .module-icon {
       @apply w-6 h-6 text-current;
   }
   ```

## Troubleshooting

### 1. Icona Non Trovata
Se l'icona non viene visualizzata:
1. Verificare il percorso: `resources/svg/` o `assets/svg/`
2. Controllare il nome file (lowercase)
3. Verificare la registrazione nel ServiceProvider

### 2. Problemi di Stile
```blade
{{-- ❌ ERRATO: Dimensioni fisse --}}
<x-fixcity-icon width="24" height="24" />

{{-- ✅ CORRETTO: Classi Tailwind --}}
<x-fixcity-icon class="w-6 h-6 text-gray-500" />
```

### 3. Debug
```php
// Nel ServiceProvider
protected function registerSvgPaths(): void
{
    $paths = [
        module_path($this->name, 'resources/svg'),
        module_path($this->name, 'assets/svg'),
    ];

    foreach ($paths as $path) {
        if (is_dir($path)) {
            \Log::info("Loading SVG from: $path");
        }
    }
}
```

## Note Importanti

1. **Accessibilità**:
   ```xml
   <svg aria-hidden="true" role="img">
       <!-- paths -->
   </svg>
   ```

2. **Ottimizzazione**:
   - Usa SVGO per ottimizzare gli SVG
   - Rimuovi attributi non necessari
   - Mantieni viewBox per la scalabilità

3. **Convenzioni**:
   - Un'icona per modulo
   - Nome file lowercase
   - Stile consistente

# Estensione Classi Filament in Laraxot

## Principio Base
In Laraxot, non si estendono mai direttamente le classi Filament. Invece, si utilizzano sempre le classi base corrispondenti dal modulo Xot con il prefisso `XotBase`.

## Mappatura Classi

| Classe Filament | Classe Xot da Estendere |
|----------------|------------------------|
| `Filament\Resources\Resource` | `Modules\Xot\Filament\Resources\XotBaseResource` |
| `Filament\Resources\Pages\ListRecords` | `Modules\Xot\Filament\Resources\Pages\XotBaseListRecords` |
| `Filament\Resources\Pages\CreateRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord` |
| `Filament\Resources\Pages\EditRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord` |
| `Filament\Resources\RelationManagers\RelationManager` | `Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager` |

## Esempi di Implementazione

### 1. Resource
```php
// ❌ ERRATO
use Filament\Resources\Resource;
class MyResource extends Resource

// ✅ CORRETTO
use Modules\Xot\Filament\Resources\XotBaseResource;
class MyResource extends XotBaseResource
```

### 2. List Records
```php
// ❌ ERRATO
use Filament\Resources\Pages\ListRecords;
class ListMyRecords extends ListRecords

// ✅ CORRETTO
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
class ListMyRecords extends XotBaseListRecords
```

### 3. Relation Manager
```php
// ❌ ERRATO
use Filament\Resources\RelationManagers\RelationManager;
class MyRelationManager extends RelationManager

// ✅ CORRETTO
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
class MyRelationManager extends XotBaseRelationManager
```

## Vantaggi

1. **Consistenza**: Le classi XotBase forniscono un comportamento consistente in tutta l'applicazione

2. **Funzionalità Estese**: Le classi XotBase aggiungono funzionalità specifiche per Laraxot

3. **Manutenibilità**: Centralizza le modifiche comuni nel modulo Xot

4. **Type Safety**: Migliore supporto per il type hinting e l'analisi statica

## Best Practices

1. **Namespace**:
   ```php
   namespace Modules\MyModule\Filament\Resources;
   use Modules\Xot\Filament\Resources\XotBaseResource;
   ```

2. **Importazioni**:
   ```php
   // ✅ CORRETTO
   use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
   
   // ❌ ERRATO
   use Filament\Resources\Pages\ListRecords;
   ```

3. **Ereditarietà**:
   ```php
   // Mantieni la catena di ereditarietà
   MyResource extends XotBaseResource
   ```

## Note Importanti

1. **Compatibilità**:
   - Le classi XotBase sono compatibili con le interfacce Filament
   - Mantengono la stessa API pubblica delle classi Filament

2. **Override**:
   - Rispetta i livelli di accesso quando fai override dei metodi
   - Usa le annotazioni PHPDoc per la chiarezza

3. **Configurazione**:
   - Le classi XotBase possono avere configurazioni aggiuntive
   - Controlla la documentazione del modulo Xot per le opzioni

# XotBaseResource vs Resource Standard

## Differenze Chiave

### 1. Navigazione
```php
// ❌ ERRATO: Non definire proprietà di navigazione
class TicketResource extends XotBaseResource
{
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Segnalazioni';
}

// ✅ CORRETTO: XotBaseResource gestisce già la navigazione
class TicketResource extends XotBaseResource
{
    protected static ?string $model = Ticket::class;
}
```

### 2. Form Schema
```php
// ❌ ERRATO: Non usare form()
public static function form(Forms\Form $form): Forms\Form
{
    return $form->schema([...]);
}

// ✅ CORRETTO: Usare getFormSchema()
protected function getFormSchema(): array
{
    return [
        TextInput::make('title')->required(),
        // ...
    ];
}
```

### 3. Table Configuration
```php
// ❌ ERRATO: Non definire table() nel Resource
class TicketResource extends XotBaseResource
{
    public static function table(Table $table): Table {...}
}

// ✅ CORRETTO: Definire configurazione tabella nella ListRecords page
class ListTickets extends XotBaseListRecords
{
    protected function getTableColumns(): array {...}
    protected function getTableFilters(): array {...}
    protected function getTableActions(): array {...}
    protected function getTableBulkActions(): array {...}
}
```

## Metodi Disponibili

### XotBaseResource
1. **Schema Form**:
   ```php
   protected function getFormSchema(): array
   ```

2. **Relazioni**:
   ```php
   public static function getRelations(): array
   ```

3. **Pagine**:
   ```php
   public static function getPages(): array
   ```

### XotBaseListRecords
1. **Configurazione Tabella**:
   ```php
   protected function getTableColumns(): array
   protected function getTableFilters(): array
   protected function getTableActions(): array
   protected function getTableBulkActions(): array
   ```

## Best Practices

1. **Separazione delle Responsabilità**:
   - Resource: definisce solo model e form schema
   - ListRecords: gestisce tutta la configurazione della tabella
   - CreateRecord/EditRecord: gestiscono le operazioni CRUD

2. **Type Safety**:
   ```php
   // Usa sempre return type declarations
   protected function getFormSchema(): array
   protected function getTableColumns(): array
   ```

3. **Documentazione**:
   ```php
   /**
    * Get the form schema for the resource.
    *
    * @return array<int, \Filament\Forms\Components\Component>
    */
    protected function getFormSchema(): array
   ```

## Note Importanti

1. **Navigazione**:
   - XotBaseResource gestisce automaticamente la navigazione
   - Non definire $navigationIcon o $navigationGroup

2. **Table Configuration**:
   - Tutta la logica della tabella va nella classe ListRecords
   - Non definire configurazioni di tabella nel Resource

3. **Form Schema**:
   - Usa getFormSchema() invece di form()
   - Definisci lo schema come array di componenti

# XotBaseListRecords vs ListRecords Standard

## Differenze Chiave

### 1. Metodi per la Tabella
```php
// ❌ ERRATO: Metodi standard di Filament
protected function getTableColumns(): array
protected function getTableFilters(): array
protected function getTableActions(): array
protected function getTableBulkActions(): array

// ✅ CORRETTO: Metodi di XotBaseListRecords
protected function getListTableColumns(): array
protected function getListTableFilters(): array
protected function getListTableActions(): array
protected function getListTableBulkActions(): array
```

### 2. Implementazione Corretta
```php
class ListTickets extends XotBaseListRecords
{
    protected function getListTableColumns(): array
    {
        return [
            TextColumn::make('id')->sortable(),
            // ...altre colonne
        ];
    }

    protected function getListTableFilters(): array
    {
        return [
            SelectFilter::make('status'),
            // ...altri filtri
        ];
    }
}
```

## Metodi Disponibili in XotBaseListRecords

### 1. Colonne e Filtri
```php
/**
 * Get the table columns for the list view.
 *
 * @return array<int, Column>
 */
protected function getListTableColumns(): array

/**
 * Get the table filters for the list view.
 *
 * @return array<int, Filter>
 */
protected function getListTableFilters(): array
```

### 2. Azioni
```php
/**
 * Get the table actions for the list view.
 *
 * @return array<int, Action>
 */
protected function getListTableActions(): array

/**
 * Get the table bulk actions for the list view.
 *
 * @return array<int, BulkAction>
 */
protected function getListTableBulkActions(): array
```

## Best Practices

1. **Nomenclatura**:
   ```php
   // ✅ CORRETTO: Usa sempre il prefisso "List"
   protected function getListTableColumns(): array
   
   // ❌ ERRATO: Non usare i metodi standard di Filament
   protected function getTableColumns(): array
   ```

2. **Type Safety**:
   ```php
   /**
    * @return array<int, Column>
    */
   protected function getListTableColumns(): array
   {
       return [
           TextColumn::make('id')->sortable(),
       ];
   }
   ```

3. **Documentazione**:
   ```php
   /**
    * Get the table columns for the list view.
    *
    * @return array<int, \Filament\Tables\Columns\Column>
    */
   protected function getListTableColumns(): array
   ```

## Note Importanti

1. **Prefisso List**:
   - Tutti i metodi relativi alla tabella hanno il prefisso "List"
   - Questo distingue i metodi di XotBaseListRecords da quelli standard

2. **Ereditarietà**:
   - XotBaseListRecords estende la classe base di Filament
   - Aggiunge funzionalità specifiche per Laraxot
   - Mantiene la compatibilità con l'API di Filament

3. **Override**:
   - Rispetta i nomi dei metodi di XotBaseListRecords
   - Non fare override dei metodi standard di Filament
   - Usa le annotazioni PHPDoc per la chiarezza

4. **Configurazione**:
   - Le configurazioni della tabella vanno nei metodi "List"
   - Non usare i metodi standard di Filament
   - Rispetta la struttura di XotBaseListRecords

# Metodi Statici in XotBaseResource

## Differenze Chiave

### 1. Metodi Form Schema
```php
// ❌ ERRATO: Non definire getFormSchema come non statico
protected function getFormSchema(): array

// ✅ CORRETTO: getFormSchema deve essere statico
public static function getFormSchema(): array
```

### 2. Implementazione Corretta
```php
class TicketResource extends XotBaseResource
{
    protected static ?string $model = Ticket::class;

    // ✅ CORRETTO: Metodo statico
    public static function getFormSchema(): array 
    {
        return [
            TextInput::make('title')->required(),
            // ...
        ];
    }
}
```

## Errori Comuni

### 1. Visibilità e Staticità
```php
// ❌ ERRATO: protected e non statico
protected function getFormSchema(): array

// ❌ ERRATO: public ma non statico
public function getFormSchema(): array

// ✅ CORRETTO: public e statico
public static function getFormSchema(): array
```

### 2. Accesso a Proprietà
```php
// ❌ ERRATO: Accesso a $this in metodo statico
public static function getFormSchema(): array
{
    return [
        TextInput::make('name')
            ->default($this->getDefaultName())  // Non funziona!
    ];
}

// ✅ CORRETTO: Usa metodi statici o proprietà statiche
public static function getFormSchema(): array
{
    return [
        TextInput::make('name')
            ->default(static::getDefaultName())
    ];
}
```

## Best Practices

1. **Dichiarazione Metodi**:
   ```php
   /**
    * Get the form schema for the resource.
    *
    * @return array<int, \Filament\Forms\Components\Component>
    */
   public static function getFormSchema(): array
   {
       return [
           // schema components
       ];
   }
   ```

2. **Metodi Helper**:
   ```php
   // Se servono metodi helper, farli statici
   protected static function getDefaultName(): string
   {
       return 'Default Name';
   }
   ```

3. **Proprietà Statiche**:
   ```php
   protected static ?string $model = Ticket::class;
   ```

## Note Importanti

1. **Ereditarietà**:
   - Rispetta la staticità dei metodi della classe padre
   - Non cambiare la visibilità dei metodi ereditati
   - Mantieni la coerenza con XotBaseResource

2. **Type Safety**:
   ```php
   // Usa sempre return type declarations
   public static function getFormSchema(): array
   ```

3. **Documentazione**:
   ```php
   /**
    * @return array<int, \Filament\Forms\Components\Component>
    */
   public static function getFormSchema(): array
   ```

4. **Contesto Statico**:
   - Non usare $this nei metodi statici
   - Usa static:: o self:: per riferimenti alla classe
   - Accedi solo a proprietà e metodi statici
```

# Livelli di Accesso in XotBaseListRecords

## Principio Fondamentale
Quando si estende una classe base, i metodi sovrascritti devono mantenere lo stesso livello di accesso (o più permissivo) della classe padre.

## Esempi

### 1. Metodi della Tabella
```php
// ❌ ERRATO: Livello di accesso più restrittivo
protected function getListTableColumns(): array

// ✅ CORRETTO: Stesso livello di accesso della classe padre
public function getListTableColumns(): array
```

### 2. Implementazione Corretta
```php
class ListTickets extends XotBaseListRecords
{
    // ✅ CORRETTO: public come nella classe padre
    public function getListTableColumns(): array
    {
        return [
            TextColumn::make('id')->sortable(),
            // ...
        ];
    }

    // ❌ ERRATO: protected è più restrittivo
    protected function getListTableColumns(): array
    {
        // ...
    }
}
```

## Errori Comuni

### 1. Visibilità dei Metodi
```php
// In XotBaseListRecords (classe padre)
public function getListTableColumns(): array

// ❌ ERRATO: Non puoi restringere l'accesso
class MyListRecords extends XotBaseListRecords
{
    protected function getListTableColumns(): array  // Errore!
    {
        // ...
    }
}

// ✅ CORRETTO: Mantieni lo stesso livello di accesso
class MyListRecords extends XotBaseListRecords
{
    public function getListTableColumns(): array
    {
        // ...
    }
}
```

### 2. Documentazione
```php
/**
 * Get the table columns for the list view.
 * 
 * IMPORTANTE: Questo metodo deve essere public per rispettare
 * il contratto con la classe padre XotBaseListRecords.
 *
 * @return array<int, \Filament\Tables\Columns\Column>
 */
public function getListTableColumns(): array
```

## Best Practices

1. **Verifica della Classe Padre**:
   ```php
   // Prima di implementare, controlla la visibilità nella classe padre
   public function getListTableColumns(): array  // Deve essere public
   ```

2. **PHPDoc Completo**:
   ```php
   /**
    * @inheritdoc
    * @return array<int, \Filament\Tables\Columns\Column>
    */
   public function getListTableColumns(): array
   ```

3. **Type Safety**:
   ```php
   // Mantieni i type hints e return types
   public function getListTableColumns(): array
   ```

## Note Importanti

1. **Regola PHP**:
   - Un metodo che estende un metodo della classe padre deve mantenere o allargare la visibilità
   - Non può restringere la visibilità

2. **Visibilità Permesse**:
   - Se il metodo padre è `public`, il metodo figlio deve essere `public`
   - Se il metodo padre è `protected`, il metodo figlio può essere `protected` o `public`
   - Se il metodo padre è `private`, non può essere esteso

3. **Controlli da Fare**:
   - Verifica sempre la visibilità dei metodi nella classe padre
   - Usa l'IDE per controllare la compatibilità
   - Mantieni la coerenza con l'API pubblica
```

# Namespace in Xot

## Struttura dei Namespace

### 1. Resources e Pages
```php
// Resources
use Modules\Xot\Filament\Resources\XotBaseResource;

// Pages
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
```

### 2. RelationManagers
```php
// ❌ ERRATO: Namespace non corretto
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

// ✅ CORRETTO: Namespace corretto
use Modules\Xot\Filament\RelationManagers\XotBaseRelationManager;
```

## Mappatura Namespace

| Componente | Namespace |
|------------|-----------|
| Resources | `Modules\Xot\Filament\Resources` |
| Pages | `Modules\Xot\Filament\Resources\Pages` |
| RelationManagers | `Modules\Xot\Filament\RelationManagers` |

## Esempi di Implementazione

### 1. Resource
```php
use Modules\Xot\Filament\Resources\XotBaseResource;

class TicketResource extends XotBaseResource
{
    // ...
}
```

### 2. Pages
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListTickets extends XotBaseListRecords
{
    // ...
}
```

### 3. RelationManager
```php
use Modules\Xot\Filament\RelationManagers\XotBaseRelationManager;

class CommentsRelationManager extends XotBaseRelationManager
{
    // ...
}
```

## Best Practices

1. **Import Espliciti**:
   ```php
   // ✅ CORRETTO: Import esplicito
   use Modules\Xot\Filament\RelationManagers\XotBaseRelationManager;
   
   // ❌ ERRATO: Import con namespace errato
   use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
   ```

2. **Organizzazione File**:
   ```
   Modules/YourModule/
   ├── Filament/
   │   ├── Resources/
   │   │   ├── YourResource.php
   │   │   ├── Pages/
   │   │   └── RelationManagers/
   │   └── Pages/
   ```

3. **Documentazione**:
   ```php
   /**
    * @extends \Modules\Xot\Filament\RelationManagers\XotBaseRelationManager
    */
   class CommentsRelationManager extends XotBaseRelationManager
   ```

## Note Importanti

1. **Struttura Xot**:
   - RelationManagers hanno un namespace dedicato
   - Non sono sotto Resources come in Filament standard
   - Mantengono la stessa API di Filament

2. **Compatibilità**:
   - Le classi base di Xot estendono quelle di Filament
   - Mantengono la stessa interfaccia pubblica
   - Aggiungono funzionalità specifiche per Xot

3. **Verifica Namespace**:
   - Controlla sempre il namespace corretto in Xot
   - Usa l'autocompletamento dell'IDE
   - Verifica che le classi siano trovate
```

# RelationManager in Filament/Xot

## Importante Nota sui Namespace

### ❌ Namespace Errati da Non Usare
```php
// ❌ ERRATO: Questi namespace non esistono
use Modules\Xot\Filament\RelationManagers\XotBaseRelationManager;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
```

### ✅ Namespace Corretti da Usare
```php
// ✅ CORRETTO: Usa il RelationManager standard di Filament
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    // ...
}
```

## Implementazione Corretta

### 1. Definizione Base
```php
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    
    // ... resto del codice
}
```

### 2. Form Schema
```php
public function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            RichEditor::make('content')
                ->required(),
            // ... altri campi
        ]);
}
```

### 3. Table Configuration
```php
public function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            TextColumn::make('user.name'),
            TextColumn::make('content'),
            // ... altre colonne
        ])
        ->filters([
            // ... filtri
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
}
```

## Best Practices

1. **Namespace e Import**:
   ```php
   use Filament\Resources\RelationManagers\RelationManager;
   use Filament\Forms\Components\RichEditor;
   use Filament\Tables\Columns\TextColumn;
   ```

2. **Type Hints**:
   ```php
   public function form(Forms\Form $form): Forms\Form
   public function table(Tables\Table $table): Tables\Table
   ```

3. **Relationship Definition**:
   ```php
   protected static string $relationship = 'comments';
   ```

## Note Importanti

1. **Estensione Corretta**:
   - Estendi sempre `Filament\Resources\RelationManagers\RelationManager`
   - Non cercare classi base in Xot per i RelationManager

2. **Configurazione**:
   - Definisci sempre la proprietà `$relationship`
   - Implementa i metodi `form()` e `table()`
   - Usa i componenti Filament standard

3. **Validazione**:
   ```php
   Forms\Components\RichEditor::make('content')
       ->required()
       ->maxLength(65535)
   ```

4. **Actions**:
   ```php
   ->actions([
       Tables\Actions\EditAction::make(),
       Tables\Actions\DeleteAction::make(),
   ])
   ```

# Pagine View in XotBaseResource

## Struttura delle Pagine View

### 1. Definizione Base
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewTicket extends XotBaseViewRecord
{
    protected static string $resource = TicketResource::class;
}
```

### 2. Registrazione nel Resource
```php
public static function getPages(): array
{
    return [
        'index' => Pages\ListTickets::route('/'),
        'create' => Pages\CreateTicket::route('/create'),
        'edit' => Pages\EditTicket::route('/{record}/edit'),
        'view' => Pages\ViewTicket::route('/{record}'),  // Pagina View
    ];
}
```

## Personalizzazione Vista

### 1. InfoList Schema Base
```php
public function getInfolistSchema(): array
{
    return [
        Section::make('Dettagli Ticket')
            ->schema([
                TextEntry::make('title')
                    ->label('Titolo'),
                TextEntry::make('content')
                    ->label('Contenuto')
                    ->markdown(),
                TextEntry::make('status')
                    ->badge(),
            ]),
    ];
}
```

### 2. Tabs e Sections
```php
public function getInfolistSchema(): array
{
    return [
        Tabs::make('Dettagli')
            ->tabs([
                Tab::make('Generale')
                    ->schema([
                        // schema generale
                    ]),
                Tab::make('Commenti')
                    ->schema([
                        // schema commenti
                    ]),
            ]),
    ];
}
```

## Best Practices

1. **Namespace**:
   ```php
   namespace Modules\YourModule\Filament\Resources\YourResource\Pages;
   
   use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
   ```

2. **Resource Reference**:
   ```php
   protected static string $resource = YourResource::class;
   ```

3. **View Customization**:
   ```php
   protected function getHeaderActions(): array
   {
       return [
           Actions\EditAction::make(),
           Actions\DeleteAction::make(),
       ];
   }
   ```

## Note Importanti

1. **Struttura File**:
   ```
   YourModule/
   ├── Filament/
   │   └── Resources/
   │       └── YourResource/
   │           └── Pages/
   │               └── ViewYourModel.php
   ```

2. **Convenzioni**:
   - Nome classe: `View{ModelName}`
   - Namespace: `...\Resources\{Resource}\Pages`
   - Estende: `XotBaseViewRecord`

3. **Funzionalità**:
   - Vista dettagliata record
   - Azioni intestazione
   - Schema InfoList personalizzabile
   - Tabs e sezioni

# Estensione Modelli tra Moduli

## Caso Standard
Normalmente, i modelli estendono `XotBaseModel`:

```php
use Modules\Xot\Models\XotBaseModel;

class MyModel extends XotBaseModel
{
    // ...
}
```

## Caso Particolare: Estensione tra Moduli

Quando un modulo specializza funzionalità di un altro modulo, i suoi modelli devono estendere i modelli base del modulo originale.

### 1. Esempio con Ticket

```php
// ❌ ERRATO: Non estendere XotBaseModel in questo caso
use Modules\Xot\Models\XotBaseModel;
class Ticket extends XotBaseModel

// ✅ CORRETTO: Estendere il modello base del modulo Ticket
use Modules\Ticket\Models\Ticket as BaseTicket;
class Ticket extends BaseTicket
```

### 2. Best Practices per l'Alias

```php
// Usa un alias descrittivo che indica la classe base
use Modules\Ticket\Models\Ticket as BaseTicket;
use Modules\User\Models\User as BaseUser;

// Evita nomi generici
use Modules\Ticket\Models\Ticket as Model;  // ❌ ERRATO
```

### 3. Quando Usare Questo Pattern

- Quando il modulo è una specializzazione di un altro modulo
- Quando si vuole estendere funzionalità esistenti
- Quando si mantiene la compatibilità con il modulo base

### 4. Struttura Tipica

```php
<?php

namespace Modules\Fixcity\Models;

use Modules\Ticket\Models\Ticket as BaseTicket;

class Ticket extends BaseTicket
{
    // Solo override e personalizzazioni specifiche
    // Non ridefinire proprietà e metodi se non necessario
}
```

## Note Importanti

1. **Identificazione del Caso**:
   - Il modulo estende funzionalità di un altro modulo?
   - Esiste già un modello base in un altro modulo?
   - Serve mantenere compatibilità con il modulo base?

2. **Convenzioni di Naming**:
   - Usa il suffisso `Base` per l'alias
   - Mantieni il nome originale per la classe
   - Documenta la relazione nel PHPDoc

3. **Ereditarietà**:
   - Eredita tutto dal modello base
   - Override solo quando necessario
   - Mantieni la compatibilità delle interfacce
```

# Registrazione Componenti in Laraxot

## Autoregistrazione Componenti

In Laraxot, i componenti Livewire vengono autoregistrati grazie a XotBaseServiceProvider, eliminando la necessità di registrarli manualmente.

### ❌ ERRATO: Registrazione Manuale
```php
// ❌ Non necessario in Laraxot
class TicketServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Livewire::component('ticket-list', TicketList::class);
    }
}
```

### ✅ CORRETTO: Autoregistrazione
```php
// I componenti vengono autoregistrati se seguono la convenzione di naming e struttura cartelle
Modules/
└── Ticket/
    └── app/
        └── Livewire/
            └── TicketList.php  // Autoregistrato come 'ticket-list'
```

## Come Funziona

1. **Discovery dei Componenti**:
   - XotBaseServiceProvider scansiona automaticamente le directory dei moduli
   - Cerca componenti Livewire nella cartella `app/Livewire`
   - Registra i componenti usando la convenzione kebab-case

2. **Convenzioni di Naming**:
   ```
   Namespace: Modules\{Module}\Livewire
   Classe: TicketList
   Tag Livewire: <livewire:ticket-list />
   ```

3. **Struttura Directory**:
   ```
   Module/
   ├── app/
   │   └── Livewire/          # Directory per componenti Livewire
   │       └── TicketList.php
   └── resources/
       └── views/
           └── livewire/      # Views dei componenti
               └── ticket-list.blade.php
   ```

## Best Practices

1. **Organizzazione**:
   - Mantieni i componenti Livewire in `app/Livewire`
   - Usa sottodirectory per organizzare componenti correlati
   - Segui le convenzioni di naming di Laraxot

2. **Naming**:
   - CamelCase per le classi (es. `TicketList`)
   - kebab-case per i tag (es. `ticket-list`)
   - Nomi descrittivi e significativi

3. **Testing**:
   - I componenti autoregistrati sono più facili da testare
   - Non richiedono setup manuale nei test
   - Seguono le convenzioni di testing di Laraxot


# Laravel Xot Framework

## Componenti

### Volt Components
- I componenti Volt richiedono un singolo elemento root
- La struttura base è:
  ```php
  <?php
  use Livewire\Volt\Component;
  
  new class extends Component {
      // properties & methods
  }
  ?>
  
  @volt('component_name')
  <div>
      // single root element template
  </div>
  @endvolt()
  ```
- Usano WithPagination per la gestione delle pagine
- Il metodo `with()` passa dati al template

### Enums
- Implementano interfacce Filament (HasColor, HasIcon, HasLabel)
- Supportano traduzioni via trans()
- Forniscono metodi utility come getColorClass(), label()
- Usati per status, priority e type nei modelli

## Design System
- Basato su Tailwind CSS
- Usa componenti Filament UI
- Struttura gerarchica:
  - Breadcrumbs
  - Title section
  - Content area
  - Feedback section
  - Contact section 