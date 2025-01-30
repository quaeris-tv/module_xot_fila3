<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Forms;
use Filament\Resources\Pages\EditRecord;

abstract class XotBaseEditRecord extends EditRecord
{
    /**
     * Get the form schema with common fields.
     */
    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema($this->getFormSchema())
            // ->columns($this->getFormColumns())
            // ->statePath('data')
        ;
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
     *
     * @return array<Forms\Components\Component>
     */
    protected function getFormSchema(): array
    {
        return [];
    }
}
