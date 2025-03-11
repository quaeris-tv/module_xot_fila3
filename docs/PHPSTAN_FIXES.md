# Correzioni PHPStan Livello 7 - Modulo Xot

Questo documento traccia gli errori PHPStan di livello 7 identificati nel modulo Xot e le relative soluzioni implementate.

## Errori Identificati

### 1. Errore in Helpers/Helper.php

```
Line 406: Call to function is_array() with array{0?: string, 1?: 'container'|'item', 2?: numeric-string} will always evaluate to true.
```

### 2. Errori in Actions/Filament/AutoLabelAction.php

```
Line 35: Call to an undefined method Filament\Forms\Components\Component::getName().
Line 39: Call to an undefined method Filament\Forms\Components\Component::getName().
Line 40: Call to an undefined method Filament\Forms\Components\Component::getName().
```

### 3. Errore in Actions/File/GetComponentsAction.php

```
Line 91: Parameter #1 $objectOrClass of class ReflectionClass constructor expects class-string<T of object>|T of object, string given.
```

### 4. Errore in Actions/Import/ImportCsvAction.php

```
Line 145: Class Modules\Xot\Datas\ColumnData constructor invoked with 1 parameter, 2 required.
```

### 5. Errore in Actions/Model/GetSchemaManagerByModelClassAction.php

```
Line 21: Call to an undefined method Illuminate\Database\Connection::getDoctrineSchemaManager().
```

### 6. Errore in Actions/Model/StoreAction.php

```
Line 42: Access to an undefined property Illuminate\Database\Eloquent\Relations\Relation::$relationship_type.
```

### 7. Errore in Actions/Model/Update/BelongsToAction.php

```
Line 35: Offset 0 does not exist on non-empty-array<string, mixed>.
```

### 8. Errore in Actions/Model/Update/BelongsToManyAction.php

```
Line 64: Call to function is_iterable() with non-empty-list will always evaluate to true.
```

### 9. Errore in Actions/Model/Update/RelationAction.php

```
Line 33: Access to an undefined property Illuminate\Database\Eloquent\Relations\Relation::$relationship_type.
```

### 10. Errori in Console/Commands/DatabaseSchemaExportCommand.php

```
Line 86: Function preg_match_all is unsafe to use. It can return FALSE instead of throwing an exception.
Line 174: Strict comparison using === between string and false will always evaluate to false.
Line 233: Unable to resolve the template type TKey in call to function collect
Line 233: Unable to resolve the template type TValue in call to function collect
Line 235: Unable to resolve the template type TKey in call to function collect
Line 235: Unable to resolve the template type TValue in call to function collect
```

### 11. Errori in Console/Commands/DatabaseSchemaExporterCommand.php

```
Line 87: Function json_encode is unsafe to use. It can return FALSE instead of throwing an exception.
Line 87: Parameter #2 $contents of static method Illuminate\Support\Facades\File::put() expects string, string|false given.
```

### 12. Errori in Console/Commands/GenerateDbDocumentationCommand.php

```
Line 40: Function json_decode is unsafe to use. It can return FALSE instead of throwing an exception.
Line 239: Function json_encode is unsafe to use. It can return FALSE instead of throwing an exception.
```

### 13. Errori in Console/Commands/GenerateFilamentResources.php

```
Line 20: Command "filament:generate-resources" does not have argument "module".
Line 21: Parameter #1 $name of static method Nwidart\Modules\Facades\Module::find() expects string, array|bool|string|null given.
Line 24: Part $moduleName (array|bool|string) of encapsed string cannot be cast to string.
Line 29: Part $moduleName (array|bool|string) of encapsed string cannot be cast to string.
Line 33: Part $moduleName (array|bool|string) of encapsed string cannot be cast to string.
Line 42: Parameter #1 $string of function strtolower expects string, array|bool|string|null given.
Line 46: Part $moduleName (array|bool|string) of encapsed string cannot be cast to string.
```

### 14. Errori in Console/Commands/GenerateModelsFromSchemaCommand.php

```
Line 85: Function json_decode is unsafe to use. It can return FALSE instead of throwing an exception.
Line 145: Parameter #1 $haystack of static method Illuminate\Support\Str::endsWith() expects string, int|string given.
Line 188: Function date is unsafe to use. It can return FALSE instead of throwing an exception.
Line 368: Function preg_replace is unsafe to use. It can return FALSE instead of throwing an exception.
Line 385: Function preg_replace is unsafe to use. It can return FALSE instead of throwing an exception.
Line 388: Function preg_match is unsafe to use. It can return FALSE instead of throwing an exception.
Line 416: Function preg_match is unsafe to use. It can return FALSE instead of throwing an exception.
Line 422: Function preg_match is unsafe to use. It can return FALSE instead of throwing an exception.
Line 435: Strict comparison using !== between null and mixed will always evaluate to true.
```

### 15. Errori in Console/Commands/GenerateResourceFormSchemaCommand.php

```
Line 48: Strict comparison using === between array and false will always evaluate to false.
Line 59: Strict comparison using === between string and false will always evaluate to false.
Line 63: Strict comparison using === between int and false will always evaluate to false.
Line 67: Strict comparison using === between int and false will always evaluate to false.
Line 85: Strict comparison using === between int and false will always evaluate to false.
```

### 16. Errori in Console/Commands/ImportMdbToMySQL.php

```
Line 104: Result of method Modules\Xot\Console\Commands\ImportMdbToMySQL::exportTablesToCSV() (void) is used.
Line 106: Argument of an invalid type null supplied for foreach, only iterables are supported.
```

### 17. Errori in Console/Commands/ImportMdbToSQLite.php

```
Line 90: Method Modules\Xot\Console\Commands\ImportMdbToSQLite::createTablesInSQLite() has no return type specified.
Line 114: Method Modules\Xot\Console\Commands\ImportMdbToSQLite::importDataToSQLite() has no return type specified.
```

### 18. Errore in Console/Commands/SearchStringInDatabaseCommand.php

```
Line 53: Parameter #1 $results of method Modules\Xot\Console\Commands\SearchStringInDatabaseCommand::formatResults() expects Illuminate\Support\Collection<int, object>, Illuminate\Support\Collection<int, stdClass> given.
```

### 19. Errore in Datas/XotData.php

```
Line 209: Method Modules\Xot\Datas\XotData::getProfileClass() should return class-string<Illuminate\Database\Eloquent\Model&Modules\Xot\Contracts\ProfileContract> but returns string.
```

### 20. Errore in Filament/Pages/ArtisanCommandsManager.php

```
Line 27: Property Modules\Xot\Filament\Pages\ArtisanCommandsManager::$listeners has no type specified.
```

### 21. Errore in Filament/Resources/XotBaseResource.php

```
Line 147: Method Modules\Xot\Filament\Resources\XotBaseResource::getRelations() should return array<class-string<Filament\Resources\RelationManagers\RelationManager>|Filament\Resources\RelationManagers\RelationGroup|Filament\Resources\RelationManagers\RelationManagerConfiguration> but returns array<class-string|Filament\Resources\RelationManagers\RelationGroup|Filament\Resources\RelationManagers\RelationManagerConfiguration>.
```

### 22. Errori in Filament/Resources/XotBaseResource/RelationManager/XotBaseRelationManager.php

```
Line 111: Static access to instance property Modules\Xot\Filament\Resources\XotBaseResource\RelationManager\XotBaseRelationManager::$resource.
Line 112: Dead catch - Exception is never thrown in the try block.
```

### 23. Errore in Filament/Widgets/XotBaseWidget.php

```
Line 33: Static property Modules\Xot\Filament\Widgets\XotBaseWidget::$view (view-string) does not accept string.
```

### 24. Errore in Services/ArtisanService.php

```
Line 146: Offset 1 on array{list<string>, list<string>} in isset() always exists and is not nullable.
```

## Soluzioni Implementate

### 1. Correzione in Helpers/Helper.php

Il problema è che PHPStan rileva che la chiamata a `is_array($matches)` sarà sempre vera perché `$matches` è già tipizzato come array. Abbiamo modificato il controllo per verificare se l'array non è vuoto invece di verificare se è un array:

```php
$pattern = '/(container|item)(\d+)/';
preg_match($pattern, $k, $matches);

if (!empty($matches) && isset($matches[1]) && isset($matches[2])) {
    $sk = $matches[1];
    $sv = $matches[2];
    // @phpstan-ignore offsetAccess.nonOffsetAccessible
    ${$sk}[$sv] = $v;
}
```

Questo controllo è più appropriato perché verifica che l'array `$matches` contenga effettivamente dei risultati, non solo che sia un array.

### 2. Correzione in Actions/Filament/AutoLabelAction.php

Il problema è che il codice chiamava il metodo `getName()` sui componenti Filament, ma non tutti i componenti hanno questo metodo. La soluzione è stata modificare il metodo `getComponentName()` per utilizzare un approccio più robusto:

```php
private function getComponentName(Field|Component $component): string
{
    // Per i componenti Field di Filament
    if (method_exists($component, 'getName')) {
        return $component->getName();
    }
    
    // Per i componenti generali di Filament che hanno getStatePath
    if (method_exists($component, 'getStatePath')) {
        return $component->getStatePath();
    }
    
    // Fallback a reflection per altri casi
    $reflectionClass = new \ReflectionClass($component);
    if ($reflectionClass->hasProperty('name') && $reflectionClass->getProperty('name')->isPublic()) {
        $property = $reflectionClass->getProperty('name');
        return (string) $property->getValue($component);
    }
    
    // Ultima risorsa
    return class_basename($component);
}
```

Questo approccio controlla esplicitamente se i metodi esistono prima di chiamarli, utilizzando vari fallback se il metodo principale non è disponibile.

### 3. Correzione in Actions/File/GetComponentsAction.php

L'errore riguardava l'utilizzo del costruttore di `ReflectionClass` che richiedeva un parametro di tipo `class-string<T of object>`, ma veniva passata una stringa generica. Abbiamo risolto questo problema aggiungendo un controllo che verifica se la classe esiste prima di istanziare la `ReflectionClass` e usando un'annotazione PHPDoc per indicare a PHPStan che la variabile è di tipo `class-string`:

```php
try {
    // Assicuriamoci che comp_ns sia una classe valida prima di creare la ReflectionClass
    if (!class_exists($tmp->comp_ns)) {
        throw new \Exception("La classe {$tmp->comp_ns} non esiste");
    }
    /** @var class-string $classString */
    $classString = $tmp->comp_ns;
    $reflection = new \ReflectionClass($classString);
    if ($reflection->isAbstract()) {
        continue;
    }
} catch (\Exception $e) {
    // gestione dell'errore
}
```

Questo approccio garantisce che venga passato al costruttore di `ReflectionClass` solo un nome di classe valido, evitando l'errore di tipo rilevato da PHPStan.

### 4. Correzione in Actions/Import/ImportCsvAction.php

L'errore riguardava la creazione di un oggetto `ColumnData` con un solo parametro, mentre il costruttore ne richiede due. Abbiamo risolto il problema fornendo entrambi i parametri richiesti:

```php
// Prima:
return new ColumnData($column);

// Dopo:
return new ColumnData(
    name: $column,
    type: 'string' // Tipo predefinito, modificare se necessario
);
```

Abbiamo aggiunto il parametro `type` con un valore predefinito 'string', che soddisfa il requisito del costruttore di `ColumnData`.

### 5. Correzione in Actions/Model/GetSchemaManagerByModelClassAction.php

L'errore riguardava la chiamata al metodo `getDoctrineSchemaManager()` che è stato deprecato nelle versioni recenti di Laravel. Abbiamo aggiornato il codice per utilizzare l'approccio più recente:

```php
// Prima:
return $connection->getDoctrineSchemaManager();

// Dopo:
return $connection->getDoctrineConnection()->createSchemaManager();
```

Questo approccio utilizza prima `getDoctrineConnection()` e poi chiama `createSchemaManager()` sul risultato, che è il modo attualmente supportato per ottenere lo schema manager di Doctrine.

### 6. Correzione in Actions/Model/StoreAction.php

L'errore riguardava l'accesso a una proprietà `relationship_type` che non esiste nella classe `Relation`. Abbiamo modificato il codice per determinare il tipo di relazione in base al nome della classe:

```php
// Prima:
$action_class = __NAMESPACE__.'\\Store\\'.$relation->relationship_type.'Action';

// Dopo:
// Ottieni il tipo di relazione dal nome della classe
$relationClass = get_class($relation);
$relationshipType = class_basename($relationClass);

$action_class = __NAMESPACE__.'\\Store\\'.$relationshipType.'Action';
```

Questo approccio utilizza `get_class()` e `class_basename()` per ottenere il nome della classe della relazione e lo utilizza come tipo di relazione, evitando di accedere a una proprietà non esistente.
