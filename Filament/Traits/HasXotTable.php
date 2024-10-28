<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Filament\Tables\Table;
use Webmozart\Assert\Assert;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Enums\ActionsPosition;

trait HasXotTable
{
    public function getGridTableColumns(): array
    {
        return [
            Stack::make($this->getListTableColumns()),
        ];
    }

    /**
    * Set up the table schema and functionality for tenants.
    *
    * @param Table $table The table instance for configuration.
    *
    * @return Table Configured table instance.
    */
    public function table(Table $table): Table
    {
        return $table
            /*
            ->striped()
            ->paginated([10, 25, 50, 100])
            ->poll('60s');
            */
            ->columns($this->layoutView->getTableColumns())
            ->contentGrid($this->layoutView->getTableContentGrid())
            ->headerActions($this->getTableHeaderActions())
            ->filters($this->getTableFilters())
            ->filtersLayout(FiltersLayout::AboveContent)
            ->persistFiltersInSession()
            ->actions($this->getTableActions())
            ->bulkActions($this->getTableBulkActions())
            ->actionsPosition(ActionsPosition::BeforeColumns)
            //->defaultSort(column: 'created_at', direction: 'DESC')
        ;


    }
}
