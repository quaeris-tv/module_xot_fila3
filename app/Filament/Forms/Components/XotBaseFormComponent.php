<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Forms\Components;

use Filament\Forms\Components\Field;
use Illuminate\Support\Str;
use Closure;

abstract class XotBaseFormComponent extends Field
{
    /**
     * @var array<string, mixed>
     */
    protected array $extraAttributes = [];

    /**
     * @var array<string, mixed>
     */
    protected array $extraValidationAttributes = [];

    /**
     * @var ?Closure
     */
    protected $formatStateUsing = null;

    /**
     * Add an extra HTML attribute.
     *
     * @param string|array<string, mixed> $key
     * @param mixed $value
     */
    public function extraAttributes(string|array $key, mixed $value = null): static
    {
        if (is_array($key)) {
            $this->extraAttributes = array_merge($this->extraAttributes, $key);
        } else {
            $this->extraAttributes[$key] = $value;
        }

        return $this;
    }

    /**
     * Add an extra validation attribute.
     *
     * @param string|array<string, mixed> $key
     * @param mixed $value
     */
    public function extraValidationAttributes(string|array $key, mixed $value = null): static
    {
        if (is_array($key)) {
            $this->extraValidationAttributes = array_merge($this->extraValidationAttributes, $key);
        } else {
            $this->extraValidationAttributes[$key] = $value;
        }

        return $this;
    }

    /**
     * Format the state value before display.
     */
    public function formatStateUsing(Closure $callback): static
    {
        $this->formatStateUsing = $callback;

        return $this;
    }

    /**
     * Get the formatted state value.
     *
     * @param mixed $state
     * @return mixed
     */
    protected function getFormattedState(mixed $state): mixed
    {
        if ($this->formatStateUsing) {
            return call_user_func($this->formatStateUsing, $state);
        }

        return $state;
    }

    /**
     * Get all the extra HTML attributes.
     *
     * @return array<string, mixed>
     */
    public function getExtraAttributes(): array
    {
        return $this->extraAttributes;
    }

    /**
     * Get all the extra validation attributes.
     *
     * @return array<string, mixed>
     */
    public function getExtraValidationAttributes(): array
    {
        return $this->extraValidationAttributes;
    }

    /**
     * Get the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function getValidationRules(): array
    {
        $rules = parent::getValidationRules();

        if (! $this->isRequired()) {
            $rules['nullable'] = [];
        }

        return $rules;
    }

    /**
     * Get the validation attribute.
     */
    public function getValidationAttribute(): string
    {
        return $this->getLabel() ?? Str::title(Str::snake($this->getName(), ' '));
    }

    /**
     * Determine if the field is required.
     */
    public function isRequired(): bool
    {
        return in_array('required', $this->getValidationRules());
    }
}
