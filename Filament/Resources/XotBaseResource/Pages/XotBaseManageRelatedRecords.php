<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\XotBaseResource\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Modules\Xot\Filament\Traits\HasXotTable;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;

abstract class XotBaseManageRelatedRecords extends ManageRelatedRecords implements HasForms
{
    use InteractsWithForms;
    use HasXotTable;
    use NavigationLabelTrait;

    // protected static string $resource;

    public function getFormSchema(): array
    {
        return [];
    }

    public function form(Form $form): Form
    {
        return $form
            // ->model($this->getRecord()) // Assicurati di associare il record
            ->schema($this->getFormSchema());
    }
}
