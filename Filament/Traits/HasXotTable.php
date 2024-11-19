<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
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
    use TransTrait;

    public TableLayoutEnum $layoutView = TableLayoutEnum::LIST;

    /**
     * @return array<Action|BulkAction|ActionGroup>
     */
    protected function getTableHeaderActions(): array
    {
        $actions = [
            // TableLayoutToggleTableAction::make(),
        ];

        if ($this->shouldShowAssociateAction()) {
            $actions[] = Tables\Actions\AssociateAction::make()
                ->label('')
                ->icon('heroicon-o-paper-clip')
                ->tooltip(__('user::actions.associate_user'));
        }

        if ($this->shouldShowAttachAction()) {
            $actions[] = Tables\Actions\AttachAction::make()
                ->label('')
                ->icon('heroicon-o-link')
                ->tooltip(__('user::actions.attach_user'))
                ->preloadRecordSelect();
        }

        return $actions;
    }

    /**
     * Determine whether to display the AssociateAction.
     */
    protected function shouldShowAssociateAction(): bool
    {
        // Custom logic for showing AssociateAction
        return false; // Change this to your condition
    }

    /**
     * Determine whether to display the AttachAction.
     */
    protected function shouldShowAttachAction(): bool
    {
        // @phpstan-ignore function.alreadyNarrowedType, function.alreadyNarrowedType, function.alreadyNarrowedType, function.alreadyNarrowedType
        return method_exists($this, 'getRelationship'); // Ensure relationship method exists
    }

    /**
     * Determine whether to display the DetachAction.
     */
    protected function shouldShowDetachAction(): bool
    {
        // Show DetachAction only if an associated relationship exists
        // @phpstan-ignore function.alreadyNarrowedType, function.alreadyNarrowedType, function.alreadyNarrowedType, function.alreadyNarrowedType
        return method_exists($this, 'getRelationship') && $this->getRelationship()->exists();
    }

    /**
     * Get global header actions, optimized with tooltips instead of labels.
     *
     * @return array<Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('')
                // ->tooltip(__('user::actions.create_user'))
                ->tooltip(static::trans('actions.create.tooltip'))
                ->icon('heroicon-o-plus'),
        ];
    }

    /**
     * Get table columns for grid layout.
     *
     * @return array<Tables\Columns\Column|Stack>
     */
    public function getGridTableColumns(): array
    {
        return [
            Stack::make($this->getListTableColumns()),
        ];
    }

    /**
     * Get list table columns.
     *
     * @return array<Tables\Columns\Column>
     */
    public function getListTableColumns(): array
    {
        return [];
    }

    public function getTableFiltersFormColumns(): int
    {
        return 1;
    }

    public function getTableRecordTitleAttribute(): string
    {
        return 'name';
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
            ->recordTitleAttribute($this->getTableRecordTitleAttribute())
            ->columns($this->layoutView->getTableColumns())
            ->contentGrid($this->layoutView->getTableContentGrid())
            ->headerActions($this->getTableHeaderActions())
            ->filters($this->getTableFilters())
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns($this->getTableFiltersFormColumns())
            ->persistFiltersInSession()
            ->actions($this->getTableActions())
            ->bulkActions($this->getTableBulkActions())
            ->actionsPosition(ActionsPosition::BeforeColumns)
            ->striped();
        /*
        ->defaultSort(
            column: 'created_at',
            direction: 'Desc',
        )
        */
    }

    /**
     * Define table filters.
     *
     * @return array<Tables\Filters\Filter|\Filament\Tables\Filters\TernaryFilter>
     */
    protected function getTableFilters(): array
    {
        return []; // Implement any specific filters needed
    }

    /**
     * Define row-level actions with translations.
     *
     * @return array<Tables\Actions\Action>
     */
    protected function getTableActions(): array
    {
        $actions = [
            'view' => Tables\Actions\ViewAction::make()
                ->label('')
                ->tooltip(__('user::actions.view'))
            // ->icon('heroicon-o-eye')
                ->color('info'),

            'edit' => Tables\Actions\EditAction::make()
                ->label('')
                ->tooltip(__('user::actions.edit'))
                ->icon('heroicon-o-pencil')
                ->color('warning'),
        ];
        if (! $this->shouldShowDetachAction()) {
            $actions['delete'] = Tables\Actions\DeleteAction::make()
                ->label('')
                ->tooltip(__('user::actions.delete'));
        }

        if ($this->shouldShowDetachAction()) {
            $actions['detach'] = Tables\Actions\DetachAction::make()
                ->label('')
                ->tooltip(__('user::actions.detach'))
                ->icon('heroicon-o-link-slash')
                ->color('danger')
                ->requiresConfirmation();
        }

        return $actions;
    }

    /**
     * Define bulk actions with translations.
     *
     * @return array<Tables\Actions\BulkAction>
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
     * Get the model class from the relationship or throw an exception if not found.
     *
     * @throws \Exception
     */
    public function getModelClass(): string
    {
        // @phpstan-ignore function.alreadyNarrowedType, function.alreadyNarrowedType, function.alreadyNarrowedType, function.alreadyNarrowedType
        if (method_exists($this, 'getRelationship')) {
            return $this->getRelationship()->getModel()::class;
        }
        // @phpstan-ignore function.impossibleType, function.impossibleType
        if (method_exists($this, 'getModel')) {
            return $this->getModel();
        }
        // if (method_exists($this, 'getMountedTableActionRecord')) {
        //    dddx($this->getMountedTableActionRecord());
        // }
        // if (method_exists($this, 'getTable')) {
        //    dddx( $this->getTable()->getModel());
        // }

        // ->model($this->getMountedTableActionRecord() ?? $this->getTable()->getModel())
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
