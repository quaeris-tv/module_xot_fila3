<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Traits\Filament\HasCustomModelLabel;

abstract class XotBaseCreateRecord extends CreateRecord
{
    //use HasCustomModelLabel;

    /**
     * @var null|array<string, mixed>
     */
    public ?array $data = [];

    protected static bool $canCreateAnother = true;

    /**
     * Hook that is called before a record is created.
     */
    protected function beforeCreate(): void
    {
        // Add created_by and updated_by if the model uses the trait
        if (method_exists($this->getModel(), 'getCreatedByColumn')) {
            $this->data[$this->getModel()->getCreatedByColumn()] = Auth::id();
        }
        if (method_exists($this->getModel(), 'getUpdatedByColumn')) {
            $this->data[$this->getModel()->getUpdatedByColumn()] = Auth::id();
        }
    }

    /**
     * Hook that is called after a record is created.
     */
    protected function afterCreate(): void
    {
        // Emit event that can be caught by parent components
        $this->emit('recordCreated', $this->record->id);
    }

    /**
     * Get the form schema with common fields.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->columns($this->getFormColumns());
    }

    /**
     * Get the number of form columns.
     */
    protected function getFormColumns(): int|array
    {
        return 1;
    }

    /**
     * Get the form schema.
     * @return array<Forms\Components\Component>
     */
    protected function getFormSchema(): array
    {
        return [];
    }

    /**
     * Get the notifications that should be displayed when the record is created.
     *
     * @return array<\Filament\Notifications\Notification>
     */
    protected function getCreatedNotifications(): array
    {
        return [
            ...(parent::getCreatedNotifications()),
        ];
    }

    /**
     * Validate that the user has permission to create the record.
     */
    protected function authorizeAccess(): void
    {
        static::authorizeResourceAccess();

        if (! static::getResource()::canCreate()) {
            $this->unauthorize();
        }
    }

    /**
     * Handle the case when authorization fails.
     */
    protected function unauthorize(): void
    {
        abort(403, __('You are not authorized to create this resource.'));
    }
}
