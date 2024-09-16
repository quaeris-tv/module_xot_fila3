<?php

declare(strict_types=1);

namespace Modules\Xot\Traits;

use Illuminate\Support\Str;

/**
 * Trait FormRequestTrait.
 */
trait FormRequestTrait
{
    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        $pieces = explode('\\', self::class);
        $pack = mb_strtolower($pieces[1]);
        // dddx($pieces);
        $pieces = \array_slice($pieces, 3);
        $pieces = collect($pieces)->map(
            static fn ($item): string => Str::snake($item)
        )->all();
        $trad_name = $pack.'::'.implode('.', $pieces);
        $trad = trans($trad_name);
        if (! \is_array($trad)) {
            //    dddx($trad_name.' is not an array');
            $trad = [];
        }

        $tradGeneric = trans('ui::generic'); // deve funzionare anche senza il pacchetto "food", invece "extend" e' un pacchetto primario
        if (! \is_array($tradGeneric)) {
            $tradGeneric = [];
        }

        return array_merge($tradGeneric, $trad);
    }
}
