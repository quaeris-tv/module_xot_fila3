<?php

declare(strict_types=1);

namespace Modules\Xot\Models\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Xot\Models\Extra;

trait HasExtraTrait
{
    public function extra(): MorphOne
    {
        return $this->morphOne(Extra::class, 'model');
    }

    /**
     * @return mixed|null
     */
    public function getExtra(string $name)
    {
        if (null == $this->extra) {
            $res = $this->extra()->create([]);
            $res->save();

            return null;
        }

        return $this->extra->extra_attributes->get($name);
    }

    public function getExtraBool(string $name): bool
    {
        $extra = $this->getExtra($name);

        return boolval($extra);
    }

    /**
     * @param int|float|string|array|bool|null $value
     *
     * @return void
     */
    public function setExtra(string $name, $value)
    {
        $extra = $this->extra;
        if (null == $this->extra) {
            $res = $this->extra()->create([]);
            $res->save();
        }

        $extra?->extra_attributes->set($name, $value);
        $extra?->save();
    }
}
