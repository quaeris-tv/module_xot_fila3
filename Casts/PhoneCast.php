<?php

declare(strict_types=1);

namespace Modules\Xot\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Modules\Xot\ValueObjects\PhoneValueObject;

class PhoneCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
<<<<<<< HEAD
     * @param  array<string, mixed>  $attributes
=======
     * @param array<string, mixed> $attributes
>>>>>>> 9af88cd (.)
     */
    public function get($model, string $key, mixed $value, array $attributes): PhoneValueObject
    {
        if (! is_string($value)) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        return PhoneValueObject::fromString($value);
    }

    /**
     * Prepare the given value for storage.
     *
<<<<<<< HEAD
     * @param  array<string, mixed>  $attributes
=======
     * @param array<string, mixed> $attributes
>>>>>>> 9af88cd (.)
     */
    public function set($model, string $key, mixed $value, array $attributes): string
    {
        if (! $value instanceof PhoneValueObject) {
            throw new \InvalidArgumentException('The given value is not an Phone instance.');
        }

        return $value->toString();
    }
}
