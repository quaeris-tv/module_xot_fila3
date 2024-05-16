<?php

declare(strict_types=1);

namespace Modules\Xot\Services;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class TypeGuesser
{
    private static string $default = 'word';

    /**
     * Create a new TypeGuesser instance.
     */
    public function __construct(protected Faker $faker)
    {
    }

    /**
     * @param int|null $size Length of field, if known
     */
    public function guess(string $name, Type $type, ?int $size = null): string
    {
        $name = Str::of($name)->lower();

        if ($name->endsWith('_id')) {
            return 'integer';
        }

        $name = $name->replace('_', '')->__toString();

        if (self::$default !== $typeNameGuess = $this->guessBasedOnName($name, $size)) {
            return $typeNameGuess;
        }

        if ($this->hasNativeResolverFor($name)) {
            return $name;
        }

        return $this->guessBasedOnType($type, $size);
    }

    /**
     * Check if faker instance has a native resolver for the given property.
     */
    private function hasNativeResolverFor(string $property): bool
    {
        try {
            $this->faker->getFormatter($property);
        } catch (\InvalidArgumentException) {
            return false;
        }

        return true;
    }

    /**
     * Try to guess the right faker method for the given type.
     */
    private function guessBasedOnType(Type $type, ?int $size): string
    {
        $typeName = $type->getName();

        return match ($typeName) {
            Types::BOOLEAN => 'boolean',
            Types::BIGINT, Types::INTEGER, Types::SMALLINT => 'randomNumber'.($size ? sprintf('(%s)', $size) : ''),
            Types::DATE_MUTABLE, Types::DATE_IMMUTABLE => 'date',
            Types::DATETIME_MUTABLE, Types::DATETIME_IMMUTABLE => 'dateTime',
            Types::DECIMAL, Types::FLOAT => 'randomFloat'.($size ? sprintf('(%s)', $size) : ''),
            Types::TEXT => 'text',
            Types::TIME_MUTABLE, Types::TIME_IMMUTABLE => 'time',
            default => self::$default,
        };
    }

    /**
     * Predicts county type by locale.
     */
    private function predictCountyType(): string
    {
        if ('en_US' === $this->faker->locale) {
            return "sprintf('%s County', \$faker->city)";
        }

        return 'state';
    }

    /**
     * Predicts country code based on $size.
     */
    private function predictCountryType(?int $size): string
    {
        return match ($size) {
            2 => 'countryCode',
            3 => 'countryISOAlpha3',
            5, 6 => 'locale',
            default => 'country',
        };
    }

    /**
     * Predicts type of title by $size.
     */
    private function predictTitleType(?int $size): string
    {
        if (null === $size || $size <= 10) {
            return 'title';
        }

        return 'sentence';
    }

    /**
     * Get type guess.
     */
    private function guessBasedOnName(string $name, ?int $size = null): string
    {
        return match ($name) {
            'login' => 'userName',
            'emailaddress' => 'email',
            'phone', 'telephone', 'telnumber' => 'phoneNumber',
            'town' => 'city',
            'zipcode' => 'postcode',
            'county' => $this->predictCountyType(),
            'country' => $this->predictCountryType($size),
            'currency' => 'currencyCode',
            'website' => 'url',
            'companyname', 'employer' => 'company',
            'title' => $this->predictTitleType($size),
            // 'firstname' => 'firstName()',
            default => self::$default,
        };
    }
}
