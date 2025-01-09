<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Resources\Pages\EditRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Xot\Traits\Filament\HasCustomModelLabel;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;

abstract class XotBaseEditRecord extends EditRecord
{
    use HasCustomModelLabel;

    /**
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * Hook that is called before a record is saved.
     */
    protected function beforeSave(): void
    {
        // Add updated_by if the model uses the trait
        if (method_exists($this->getRecord(), 'getUpdatedByColumn')) {
            $this->data[$this->getRecord()->getUpdatedByColumn()] = Auth::id();
        }
    }

    /**
     * Hook that is called after a record is saved.
     */
    protected function afterSave(): void
    {
        // Emit event that can be caught by parent components
        $this->emit('recordUpdated', $this->record->id);
    }

    /**
     * Get the form schema with common fields.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->columns($this->getFormColumns())
            ->statePath('data');
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
     * Get the page actions.
     *
     * @return array<Action>
     */
    protected function getActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    /**
     * Get the notifications that should be displayed when the record is saved.
     *
     * @return array<\Filament\Notifications\Notification>
     */
    protected function getSavedNotifications(): array
    {
        return [
            ...(parent::getSavedNotifications()),
        ];
    }

    /**
     * Validate that the user has permission to edit the record.
     */
    protected function authorizeAccess(): void
    {
        static::authorizeResourceAccess();

        $record = $this->getRecord();

        if (! static::getResource()::canEdit($record)) {
            $this->unauthorize();
        }
    }

    /**
     * Handle the case when authorization fails.
     */
    protected function unauthorize(): void
    {
        abort(403, __('You are not authorized to edit this resource.'));
    }

    /**
     * Get the heading of the page.
     */
    public function getHeading(): string
    {
        return __('Edit :label', [
            'label' => $this->getRecordTitle(),
        ]);
    }

    /**
     * Get the subheading of the page.
     */
    public function getSubheading(): ?string
    {
        return $this->getRecord()->created_at?->diffForHumans();
    }
}
