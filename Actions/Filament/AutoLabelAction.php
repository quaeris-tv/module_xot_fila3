<?php

/**
 * -WIP.
 */

declare(strict_types=1);

namespace Modules\Xot\Actions\Filament;

use function Safe\file;

use Illuminate\Support\Arr;

use Illuminate\Support\Str;
use Webmozart\Assert\Assert;
use Modules\Xot\Actions\GetTransKeyAction;
use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\Finder\SplFileInfo as File;

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
            $component->label($label);
        }
        

        return $component;
    }

    
}
