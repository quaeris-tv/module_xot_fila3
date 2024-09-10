<?php

declare(strict_types=1);

namespace Modules\Xot\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use Modules\Xot\Models\BaseExtra;
use Modules\Xot\Models\Extra;
use Webmozart\Assert\Assert;

trait HasExtraTrait
{
    /**
     * Retrieves the morphed one-to-one relationship between the current model and the Extra model.
     */
    public function extra(): MorphOne
    {
        $extra_class = Str::of(static::class)
            ->before('\Models\\')
            ->append('\Models\Extra')
            ->toString();
        Assert::classExists($extra_class);
        Assert::isAOf($extra_class, Model::class, '['.__LINE__.']['.class_basename($this).']['.$extra_class.']');
        // Assert::isInstanceOf($extra_class, BaseExtra::class, '['.__LINE__.']['.class_basename($this).']['.$extra_class.']');

        return $this->morphOne($extra_class, 'model');
    }

    /**
     * @return mixed|null
     */
    public function getExtra(string $name)
    {
        $value = $this->extra?->extra_attributes->get($name);

        return $value;
    }

    /**
     * @param  int|float|string|array|bool|null  $value
     * @return void
     */
    public function setExtra(string $name, $value)
    {
        $extra = $this->extra;
        if ($this->extra === null) {
            $extra = $this->extra()->firstOrCreate([], ['extra_attributes' => []]);
        }

        $extra?->extra_attributes->set($name, $value);
        $extra?->save();
    }
}
