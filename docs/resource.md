# XotBaseResource

## Overview
`XotBaseResource` è la classe base astratta per tutti i Resource Filament nel sistema. Estende `Filament\Resources\Resource` e fornisce funzionalità comuni e standardizzate.

## Struttura Base

```php
namespace Modules\Broker\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;

class MyResource extends XotBaseResource
{
    // Obbligatorio: definisce il modello associato
    public static function getModel(): string
    {
        return MyModel::class;
    }
    
    // Obbligatorio: definisce lo schema del form
    public static function getFormSchema(): array
    {
        return [
            // Schema del form
        ];
    }
}
```

## Funzionalità Chiave

1. **Gestione Modello Automatica**
   - Il modello viene determinato automaticamente dal namespace e nome della classe
   - Override possibile tramite `getModel()`

2. **Form Standardizzato**
   - NON implementare `form()` o `table()`
   - Implementare solo `getFormSchema()` che ritorna l'array dello schema
   - Il form viene costruito automaticamente da XotBaseResource

3. **Navigazione**
   - Gestione automatica del gruppo di navigazione
   - Badge con conteggio automatico degli elementi
   - Posizione sub-navigazione configurabile

4. **Pagine**
   - List Page (index)
   - Create Page
   - Edit Page
   - Generazione automatica dei percorsi

5. **Relation Managers**
   - Rilevamento automatico dei RelationManager nella cartella RelationManagers
   - Tabs combinate con il contenuto principale

## Metodi Principali

### Obbligatori da Implementare
- `getModel()`: Definisce il modello associato
- `getFormSchema()`: Definisce lo schema del form

### Ereditati (Non Sovrascrivere)
- `form()`: Usa getFormSchema()
- `table()`: Non implementare, usa List Page
- `getPages()`: Gestione automatica delle pagine
- `getRelations()`: Rilevamento automatico
- `getNavigationBadge()`: Conteggio automatico

### Opzionalmente Estendibili
- `extendFormCallback()`: Personalizzazione form
- `extendTableCallback()`: Personalizzazione tabella
- `getModuleName()`: Nome del modulo

## Best Practices

1. **Struttura Form**
   ```php
   public static function getFormSchema(): array
   {
       return [
           Forms\Components\TextInput::make('name')
               ->required(),
           // Altri campi
       ];
   }
   ```

2. **Relazioni**
   - Creare RelationManager separati nella sottocartella RelationManagers
   - Verranno rilevati e caricati automaticamente

3. **Navigazione**
   - La navigazione è gestita centralmente
   - Non sovrascrivere i metodi di navigazione
   - Usare NavigationLabelTrait per personalizzazioni

4. **Validazione**
   - Implementare le regole di validazione nello schema del form
   - Utilizzare i trait di validazione comuni quando possibile

## Note Importanti

1. NON implementare:
   - `table()`
   - `form()`
   - Metodi di navigazione diretti

2. SEMPRE implementare:
   - `getModel()`
   - `getFormSchema()`

3. La struttura delle cartelle deve seguire la convenzione:
   ```
   Modules/
   ├── ModuleName/
   │   ├── Filament/
   │   │   └── Resources/
   │   │       ├── MyResource.php
   │   │       └── RelationManagers/
   │   └── Models/
   │       └── MyModel.php
   ``` 