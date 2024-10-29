<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;

/**
 * Trait HasXotTable.
 *
 * Provides enhanced table functionality with translations and optimized structure.
 *
 * @property TableLayoutEnum $layoutView
 */
trait HasXotTable
{
    /**
     * Get header actions for the table, including custom action for table layout toggle.
     */
    protected function getTableHeaderActions(): array
    {
        return [
            TableLayoutToggleTableAction::make(),
            Tables\Actions\AssociateAction::make()
                ->label('')
                ->icon('heroicon-o-paper-clip')
                ->tooltip(__('user::actions.associate_user')),
            Tables\Actions\AttachAction::make()
                ->label('')
                ->icon('heroicon-o-link')
                ->tooltip(__('user::actions.attach_user')),
        ];
    }

    /**
     * Get global header actions, optimized with tooltips instead of labels.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('')
                ->tooltip(__('user::actions.create_user'))
                ->icon('heroicon-o-plus'),
        ];
    }

    /**
     * Define the main table structure.
     */
    public function table(Table $table): Table
    {
        if (! $this->tableExists()) {
            $this->notifyTableMissing();

            return $this->configureEmptyTable($table);
        }

        return $table
            ->columns($this->layoutView->getTableColumns())
            ->contentGrid($this->layoutView->getTableContentGrid())
            ->headerActions($this->getTableHeaderActions())
            ->filters($this->getTableFilters())
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->persistFiltersInSession()
            ->actions($this->getTableActions())
            ->bulkActions($this->getTableBulkActions())
            ->actionsPosition(ActionsPosition::BeforeColumns)
            ->striped();
    }

    /**
     * Define grid layout columns.
     */
    public function getGridTableColumns(): array
    {
        return [
            Stack::make($this->getListTableColumns()),
        ];
    }

    /**
     * Define table filters.
     */
    protected function getTableFilters(): array
    {
        return [
            // ...
        ];
    }

    /**
     * Define row-level actions with translations.
     */
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->label('')
                ->tooltip(__('user::actions.view'))
            // ->icon('heroicon-o-eye')
            // ->color('info')
            ,

            Tables\Actions\EditAction::make()
                ->label('')
                ->tooltip(__('user::actions.edit'))
            // ->icon('heroicon-o-pencil')
            // ->color('warning')
            ,

            Tables\Actions\DetachAction::make()
                ->label('')
                ->tooltip(__('user::actions.detach'))
                ->icon('heroicon-o-link-slash')
                // ->color('danger')
                ->requiresConfirmation(),
        ];
    }

    /**
     * Define bulk actions with translations.
     */
    protected function getTableBulkActions(): array
    {
        return [
            DeleteBulkAction::make()
                ->label('')
                ->tooltip(__('user::actions.delete_selected'))
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation(),
        ];
    }

    /**
     * Retrieve the default sorting column.
     */
    protected function getDefaultSort(): string
    {
        return 'id';
    }

    /**
     * Get the model class from the relationship or throw an exception if not found.
     *
     * @throws \Exception
     */
    public function getModelClass(): string
    {
        if (method_exists($this, 'getRelationship')) {
            return $this->getRelationship()->getModel()::class;
        }
        throw new \Exception('No model found in '.class_basename(__CLASS__).'::'.__FUNCTION__);
    }

    /**
     * Check if the model's table exists in the database.
     */
    protected function tableExists(): bool
    {
        $model = $this->getModelClass();

        return app($model)->getConnection()->getSchemaBuilder()->hasTable(app($model)->getTable());
    }

    /**
     * Notify the user if the table is missing.
     */
    protected function notifyTableMissing(): void
    {
        $model = $this->getModelClass();
        $tableName = app($model)->getTable();
        Notification::make()
            ->title(__('user::notifications.table_missing.title'))
            ->body(__('user::notifications.table_missing.body', ['table' => $tableName]))
            ->persistent()
            ->warning()
            ->send();
    }

    /**
     * Configure an empty table in case the actual table is missing.
     */
    protected function configureEmptyTable(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('id'))
            ->columns([
                TextColumn::make('message')
                    ->label(__('user::fields.message.label'))
                    ->default(__('user::fields.message.default'))
                    ->html(),
            ])
            ->headerActions([])
            ->actions([]);
    }
}
