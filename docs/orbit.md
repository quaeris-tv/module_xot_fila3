---
title: Orbit
description: Salvataggio tramite Orbit
extends: _layouts.documentation
section: content
---

# Orbit {#orbit}

Pacchetto di riferimento [[Jigsaw documentation](https://github.com/ryangjchandler/orbit)](https://github.com/ryangjchandler/orbit)

Salva dentro dei file json le istanze di un modello, non utilizzando più le tabelle mysql.

Dopo aver installato il pacchetto, aggiungere le seguenti connessioni dentro la configurazione database.php

```php
    'orbit' => [
        'driver' => 'sqlite',
        'database' => TenantService::filePath('orbit.sqlite'),
        'foreign_key_constraints' => false,
    ],

    'orbit_meta' => [
        'driver' => 'sqlite',
        'database' => storage_path('framework/cache/orbit/orbit_meta.sqlite'),
        'foreign_key_constraints' => false,
    ],
```