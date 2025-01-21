<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Icon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

/**
 * Base class for Filament actions.
 *
 * @property ?Model $record The associated record for this action
 * @method static static make(?string $name = null) Create a new instance of the action
 */
abstract class XotBaseAction extends Action
{
    protected const DEFAULT_ICON = 'heroicon-m-pencil';

    /**
     * Configure the action.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $icon = $this->resolveDefaultIcon();
        $this->icon($icon);
    }

    /**
     * Resolve the default icon for this action.
     */
    protected function resolveDefaultIcon(): string|Htmlable 
    {
        /** @var mixed $resolvedIcon */
        $resolvedIcon = FilamentIcon::resolve('actions.edit');
        
        if ($resolvedIcon instanceof \Filament\Support\Icons\Icon) {
            $iconName = $resolvedIcon->getName();
            Assert::stringNotEmpty($iconName);
            return $iconName;
        }
        
        return static::DEFAULT_ICON;
    }

    /**
     * Get the default name for this action.
     */
    public static function getDefaultName(): string
    {
        return 'edit';
    }

    /**
     * Get the associated record.
     */
    public function getRecord(): ?Model
    {
        /** @var Model|null $record */
        $record = $this->record;
        
        if ($record !== null) {
            Assert::isInstanceOf($record, Model::class);
        }
        
        return $record;
    }
} 