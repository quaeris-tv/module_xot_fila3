<?php

/**
 * -WIP.
 */

declare(strict_types=1);

namespace Modules\Xot\Actions\Filament;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Component;
use Illuminate\Support\Arr;
use Modules\Lang\Actions\SaveTransAction;
use Modules\Xot\Actions\GetTransKeyAction;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class AutoLabelAction
{
    use QueueableAction;

    /**
     * Get the component name based on its actual type.
     *
     * @param Field|Component $component
     * @return string
     */
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

    /**
     * Undocumented function.
     * return number of input added.
     *
     * @param Field|Component  $component
     * @return Field|Component
     */
    public function execute(Field|Component  $component): Field|Component
    {
        $backtrace = debug_backtrace();
        Assert::string($class = Arr::get($backtrace, '5.class'));
        $trans_key = app(GetTransKeyAction::class)->execute($class);

        // Get component name based on its actual class
        $componentName = $this->getComponentName($component);

        $label_key = $trans_key.'.fields.'.$componentName.'.label';
        $label = trans($label_key);
        if (is_string($label)) {
            if ($label_key == $label) {
                $label_value = $componentName;
                $label_key1 = $trans_key.'.fields.'.$componentName;
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
