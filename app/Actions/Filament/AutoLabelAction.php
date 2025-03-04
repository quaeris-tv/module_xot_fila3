<?php

/**
 * -WIP.
 */

declare(strict_types=1);

namespace Modules\Xot\Actions\Filament;

use Illuminate\Support\Arr;
use Modules\Lang\Actions\SaveTransAction;
use Modules\Xot\Actions\GetTransKeyAction;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class AutoLabelAction
{
    use QueueableAction;

    /**
     * Undocumented function.
     * return number of input added.
     */
    public function execute($component)
    {
        $backtrace = debug_backtrace();
        Assert::string($class = Arr::get($backtrace, '5.class'));
        $trans_key = app(GetTransKeyAction::class)->execute($class);
        $label_key = $trans_key.'.fields.'.$component->getName().'.label';
        $label = trans($label_key);
        if (is_string($label)) {
            if ($label_key == $label) {
                $label_value = $component->getName();
                $label_key1 = $trans_key.'.fields.'.$component->getName();
                $label1 = trans($label_key1);
                if ($label_key1 != $label1) {
                    $label_value = $label1;
                }

                app(SaveTransAction::class)->execute($label_key, $label_value);
            }
            $component->label($label);
        }

        return $component;
    }
}
