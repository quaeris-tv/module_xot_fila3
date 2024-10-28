<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Modules\UI\Enums\TableLayoutEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Actions\DeleteBulkAction;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;

/**
 * Summary of HasXotTable
 * @property TableLayoutEnum $layoutView
 */
trait HasXotTable
{
    /**
     * Get header actions.
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('') // Empty label
                ->tooltip(__('Create User')), // Move label to tooltip
            /*
            \Filament\Tables\Actions\AssociateAction::make()
                ->label('') // Empty label
                ->tooltip(__('Associate User')), // Move label to tooltip
            */
        ];
    }

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
            // ->defaultSort($this->getDefaultSort())
            ->striped()
            // ->paginated([10, 25, 50, 100])
            // ->poll('60s')
        ;
    }

    /**
     * Get grid layout columns.
     */
    public function getGridTableColumns(): array
    {
        return [
            Stack::make($this->getListTableColumns()),
        ];
    }

     /*
     * Define table columns in a separate, strongly-typed method.
     *
     * @return array<Column>
     */
    //protected function getListTableColumns(): array
    
        

    protected function getTableFilters(): array
    {
        return [];
    }

    /**
     * Get row-level actions.
     */
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->label('')
                ->tooltip(__('user::actions.view'))
                ->icon('heroicon-o-eye')
                ->color('info'),

            Tables\Actions\EditAction::make()
                ->label('')
                ->tooltip(__('user::actions.edit'))
                ->icon('heroicon-o-pencil')
                ->color('warning'),

            Tables\Actions\DetachAction::make()
                ->label('')
                ->tooltip(__('user::actions.detach'))
                ->icon('heroicon-o-link-slash')
                ->color('danger')
                ->requiresConfirmation(),
        ];
    }

    /**
     * Get bulk actions.
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

    protected function getBulkActions(): array
    {
        return [
            DeleteBulkAction::make()
                ->label('') // Empty label
                ->tooltip(__('Delete Selected')), // Move label to tooltip
        ];
    }

    protected function getDefaultSort(): string
    {
        return 'id';
    }

    // protected function getTableQuery(): Builder
    // {
    //     return static::getModel()::query();
    // }
    public function getModelClass(): string
    {
        if (method_exists($this, 'getRelationship')) {
            $model = $this->getRelationship()->getModel();
            return $model::class;
        }
        throw new \Exception('['.__LINE__.']['.class_basename(__CLASS__).']'.__FUNCTION__.' Error: no model found');

        
    }

    protected function tableExists(): bool
    {
        $model = $this->getModelClass();

        return app($model)->getConnection()->getSchemaBuilder()->hasTable(app($model)->getTable());
    }

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

    protected function configureEmptyTable(Table $table): Table
    {
        $model = $this->getModelClass();

        return $table
            ->modifyQueryUsing(fn (Builder $query) => $model::query()->where('id', null))
            ->columns([
                TextColumn::make('message')
                    ->label(__('user::fields.message.label'))
                    ->default(__('user::fields.message.default'))
                    ->html(),
            ])
            ->headerActions([]) // Nessuna azione header
            ->actions([]); // Nessuna azione footer
    }
}
