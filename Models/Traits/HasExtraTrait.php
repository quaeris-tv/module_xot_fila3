<?php

declare(strict_types=1);

namespace Modules\Xot\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

trait HasExtraTrait
{
    public function extra(): MorphOne
    {
        $extra_class = Str::of(static::class)
            ->before('\Models\\')
            ->append('\Models\Extra')
            ->toString();

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
     * @param int|float|string|array|bool|null $value
     *
     * @return void
     */
    public function setExtra(string $name, $value)
    {
        $extra = $this->extra;
        if (null === $this->extra) {
            $extra = $this->extra()->create(['extra_attributes' => []]);
        }

        $extra?->extra_attributes->set($name, $value);
        $extra?->save();
    }
}
