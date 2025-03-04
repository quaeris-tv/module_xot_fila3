<?php

declare(strict_types=1);

namespace Modules\Xot\Helpers;

use Illuminate\Translation\Translator;

/**
 * Helper per la gestione sicura degli oggetti Translator
 */
class TranslatorHelper
{
    /**
     * Converte in modo sicuro un oggetto Translator o qualsiasi altro valore in una stringa.
     *
     * @param  mixed  $value  Il valore da convertire in stringa
     * @param  string  $default  Valore predefinito se non è possibile convertire
     * @return string La stringa risultante
     */
    public static function toString($value, string $default = ''): string
    {
        // Se è già una stringa, restituiscila direttamente
        if (is_string($value)) {
            return $value;
        }

        // Se è null, restituisci stringa vuota o default
        if (is_null($value)) {
            return $default;
        }

        // Se è numerico, convertilo a stringa
        if (is_numeric($value)) {
            return (string) $value;
        }

        // Se è un oggetto Translator, gestiscilo in modo specifico
        if ($value instanceof Translator) {
            // Assumiamo che sia chiamato via __toString in un contesto
            // di stampa, quindi cerchiamo di ottenere il valore tradotto
            try {
                return (string) $value;
            } catch (\Throwable $e) {
                return 'Translator Object';
            }
        }

        // Se è un oggetto con metodo __toString, usalo
        if (is_object($value) && method_exists($value, '__toString')) {
            try {
                return $value->__toString();
            } catch (\Throwable $e) {
                return get_class($value);
            }
        }

        // Per oggetti senza metodo __toString, restituisci la classe
        if (is_object($value)) {
            return get_class($value);
        }

        // Per array, converti in JSON
        if (is_array($value)) {
            try {
                return json_encode($value) ?: $default;
            } catch (\Throwable $e) {
                return 'Array';
            }
        }

        // Per tutti gli altri tipi, tenta un cast a stringa
        try {
            return (string) $value;
        } catch (\Throwable $e) {
            return $default;
        }
    }
}
