# Modulo Xot
Cuore pulsante che permette il funzionamento di tutti i moduli laraxot

## Aggiungere Modulo nella base del progetto
Dentro la cartella laravel/Modules

```bash
git submodule add https://github.com/laraxot/module_xot_fila3.git Xot
```

## Verificare che il modulo sia attivo
```bash
php artisan module:list
```
in caso abilitarlo
```bash
php artisan module:enable Xot
```
