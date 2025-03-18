<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;
use Modules\Xot\Actions\Model\TableExistsByModelClassActions;
use Webmozart\Assert\Assert;

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

    protected static bool $canReplicate = false;

    protected static bool $canView = true;

    protected static bool $canEdit = true;

    /**
     * Get table header actions.
     *
     * @return array<string, Action|ActionGroup>
     */
    public function getTableHeaderActions(): array
    {
        $actions = [];

        $actions['create'] = Tables\Actions\CreateAction::make();

        if ($this->shouldShowAssociateAction()) {
            $actions['associate'] = Tables\Actions\AssociateAction::make()
                ->label('')
                ->icon('heroicon-o-paper-clip')
                ->tooltip(__('user::actions.associate_user'));
        }

        if ($this->shouldShowAttachAction()) {
            $actions['attach'] = Tables\Actions\AttachAction::make()
                ->label('')
                ->icon('heroicon-o-link')
                ->tooltip(__('user::actions.attach_user'))
                ->preloadRecordSelect();
        }

        $actions['layout'] = TableLayoutToggleTableAction::make('layout');

        return $actions;
    }

    protected function shouldShowAssociateAction(): bool
    {
        return false;
    }

    protected function shouldShowAttachAction(): bool
    {
        return method_exists($this, 'getRelationship');
    }

    protected function shouldShowDetachAction(): bool
    {
        return method_exists($this, 'getRelationship');
    }

    protected function shouldShowReplicateAction(): bool
    {
        return static::$canReplicate;
    }

    protected function shouldShowViewAction(): bool
    {
        return static::$canView;
    }

    protected function shouldShowEditAction(): bool
    {
        return static::$canEdit;
    }

    /**
     * Get header actions.
     *
     * @return array<string, Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
        ];
    }

    /**
     * Get grid table columns.
     *
     * @return array<int, Tables\Columns\Column|Stack>
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
     * @return array<string, Tables\Columns\Column>
     */
    public function getListTableColumns(): array
    {
        return [];
    }

    /**
     * Get table filters form columns.
     */
    public function getTableFiltersFormColumns(): int
    {
        $count = count($this->getTableFilters()) + 1;

        return min($count, 6);
    }

    /**
     * Get table record title attribute.
     */
    public function getTableRecordTitleAttribute(): string
    {
        return 'name';
    }

    /**
     * Get table heading.
     */
    public function getTableHeading(): ?string
    {
        $key = static::getKeyTrans('table.heading');
        /** @var string|array<int|string,mixed>|null $trans */
        $trans = trans($key);

        return (is_string($trans) && $trans !== $key) ? $trans : null;
    }

    /**
     * Get table empty state actions.
     *
     * @return array<string, Action>
     */
    public function getTableEmptyStateActions(): array
    {
        return [];
    }

    /**
     * Configura una tabella Filament.
     *
     * Nota: Questo metodo è stato modificato per risolvere l'errore
     * "Method Filament\Actions\Action::table does not exist" in Filament 3.
     * La soluzione verifica l'esistenza dei metodi getTableHeaderActions(),
     * getTableActions() e getTableBulkActions() prima di chiamarli,
     * garantendo la compatibilità con diverse versioni di Filament.
     *
     * Problema: Il trait chiamava direttamente metodi che potrebbero non esistere
     * nelle classi che lo utilizzano, causando errori in Filament 3.
     *
     * Soluzione: Verifica condizionale dell'esistenza dei metodi prima di chiamarli,
     * mantenendo la retrocompatibilità e prevenendo errori.
     *
     * Ultimo aggiornamento: 10/2023
     */
    public function table(Table $table): Table
    {
        $modelClass = $this->getModelClass();
        if (! app(TableExistsByModelClassActions::class)->execute($modelClass)) {
            $this->notifyTableMissing();
            return $this->configureEmptyTable($table);
        }

        /** @var Model $model */
        $model = app($modelClass);
        Assert::isInstanceOf($model, Model::class);

        // Configurazione base della tabella
        $table = $table
            ->recordTitleAttribute($this->getTableRecordTitleAttribute())
            ->heading($this->getTableHeading())
            ->columns($this->layoutView->getTableColumns())
            ->contentGrid($this->layoutView->getTableContentGrid())
            ->filters($this->getTableFilters())
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns($this->getTableFiltersFormColumns())
            ->persistFiltersInSession();

        // Verifica i metodi disponibili prima di chiamarli
        if (method_exists($this, 'getTableHeaderActions')) {
            $table = $table->headerActions($this->getTableHeaderActions());
        }

        if (method_exists($this, 'getTableActions')) {
            $table = $table->actions($this->getTableActions());
        }

        if (method_exists($this, 'getTableBulkActions')) {
            $table = $table->bulkActions($this->getTableBulkActions());
        }

        $table = $table
            ->actionsPosition(ActionsPosition::BeforeColumns)
            ->emptyStateActions($this->getTableEmptyStateActions())
            ->striped();

        /*
            ->defaultSort(
                column: $this->getDefaultTableSortColumn(),
                direction: $this->getDefaultTableSortDirection(),
            );
        */
        return $table;
    }

    /**
     * Get default table sort column.
     */
    protected function getDefaultTableSortColumn(): ?string
    {
        try {
            $modelClass = $this->getModelClass();
            /** @var Model $model */
            $model = app($modelClass);
            Assert::isInstanceOf($model, Model::class);

            return $model->getTable().'.id';
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get default table sort direction.
     */
    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    /**
     * Get table filters.
     *
     * @return array<string, Tables\Filters\Filter|TernaryFilter|BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [];
    }

    /**
     * Get table actions.
     *
     * @return array<string, Action|ActionGroup>
     */
    public function getTableActions(): array
    {
        $actions = [];

        if ($this->shouldShowViewAction()) {
            $actions['view'] = Tables\Actions\ViewAction::make()
                ->iconButton()
                ->tooltip(__('user::actions.view'));
        }

        if ($this->shouldShowEditAction()) {
            $actions['edit'] = Tables\Actions\EditAction::make()
                ->iconButton()
                ->tooltip(__('user::actions.edit'));
        }

        $actions['delete'] = Tables\Actions\DeleteAction::make()
            ->iconButton()
            ->tooltip(__('user::actions.delete'));

        if ($this->shouldShowReplicateAction()) {
            $actions['replicate'] = Tables\Actions\ReplicateAction::make()
                ->iconButton()
                ->tooltip(__('user::actions.replicate'));
        }

        // Check if class has the getRelationship method
        if ($this->shouldShowDetachAction()) {
            if (method_exists($this, 'getRelationship')) {
                if (method_exists($this->getRelationship(), 'getTable')) {
                    $pivotClass = $this->getRelationship()->getPivotClass();
                    if (method_exists($pivotClass, 'getKeyName')) {
                        $actions['detach'] = Tables\Actions\DetachAction::make()
                            ->iconButton()
                            ->tooltip(__('user::actions.detach'));
                    }
                }
            }
        }

        return $actions;
    }

    /**
     * Get table bulk actions.
     *
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make()
                ->label('')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation(),
        ];
    }

    /**
     * Get model class.
     *
     * @throws \Exception Se non viene trovata una classe modello valida
     *
     * @return class-string<Model>
     */
    public function getModelClass(): string
    {
        if (method_exists($this, 'getRelationship')) {
            $relationship = $this->getRelationship();
            if ($relationship instanceof Relation) {
                /* @var class-string<Model> */
                return get_class($relationship->getModel());
            }
        }

        if (method_exists($this, 'getModel')) {
            $model = $this->getModel();
            if (is_string($model)) {
                Assert::classExists($model);

                /* @var class-string<Model> */
                return $model;
            }
            if ($model instanceof Model) {
                /* @var class-string<Model> */
                return get_class($model);
            }
        }

        throw new \Exception('No model found in '.class_basename(__CLASS__).'::'.__FUNCTION__);
    }

    /**
     * Notify that table is missing.
     */
    protected function notifyTableMissing(): void
    {
        $modelClass = $this->getModelClass();
        /** @var Model $model */
        $model = app($modelClass);
        Assert::isInstanceOf($model, Model::class);

        Notification::make()
            ->title(__('user::notifications.table_missing.title'))
            ->body(__('user::notifications.table_missing.body', [
                'table' => $model->getTable(),
            ]))
            ->persistent()
            ->warning()
            ->send();
    }

    /**
     * Configure empty table.
     */
    protected function configureEmptyTable(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(static fn (Builder $query) => $query->whereNull('id'))
            ->columns([
                TextColumn::make('message')
                    ->default(__('user::fields.message.default'))
                    ->html(),
            ])
            ->headerActions([])
            ->actions([]);
    }

    /**
     * Get searchable columns.
     *
     * @return array<string>
     */
    protected function getSearchableColumns(): array
    {
        return ['id', 'name'];
    }

    /**
     * Check if search is enabled.
     */
    protected function hasSearch(): bool
    {
        return true;
    }

    /**
     * Get table search query.
     */
    public function getTableSearch(): string
    {
        /* @var string */
        return $this->tableSearch ?? '';
    }
}