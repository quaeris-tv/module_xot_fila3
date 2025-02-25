<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\XotBaseResource\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Modules\Xot\Filament\Traits\HasXotTable;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;

abstract class XotBaseManageRelatedRecords extends ManageRelatedRecords implements HasForms
{
    use HasXotTable;
    use InteractsWithForms;
    use NavigationLabelTrait;

    // protected static string $resource;

    public static function getNavigationGroup(): string
    {
        return '';
    }

    /**
     * @return array<\Filament\Forms\Components\Component>
     */
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

    protected function getHeaderActions(): array
    {
        return [
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            'create' => Tables\Actions\CreateAction::make('create')
                // ->icon('heroicon-o-plus')
                ->color('primary')
            // ->outlined(false)
            // ->iconButton()
            // ->badgeColor('success')
            // ->tooltip('Nuova Fase')
            // ->extraAttributes([
            //    'class' => 'mx-auto my-8 bg-primary',
            // ])
            // ->modalHeading('Crea Nuova Fase')
            // ->form($this->getFormSchema())
            ,
        ];
    }
}
