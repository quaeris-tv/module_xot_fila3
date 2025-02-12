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
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
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
    protected function getTableHeaderActions(): array
    {
        $actions = [];

        $actions['create'] = Tables\Actions\CreateAction::make()
            ->button()
            //->color('primary')
            //->size('md')
            //->icon('heroicon-o-plus')
            //->iconPosition('before')
            ->label('')
            ->extraAttributes([
                'class' => 'font-semibold shadow-sm hover:shadow-md transition-all duration-200',
            ]);

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
     * @return array<string, Tables\Columns\Column|Stack>
     */
    public function getGridTableColumns(): array
    {
        return [
            'stack01'=>Stack::make($this->getListTableColumns()),
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
     * Configure the table.
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

        $table = $table
            ->recordTitleAttribute($this->getTableRecordTitleAttribute())
            ->heading($this->getTableHeading())
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
    protected function getTableFilters(): array
    {
        return [];
    }

    /**
     * Get table actions.
     *
     * @return array<string, Action|ActionGroup>
     */
    protected function getTableActions(): array
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

        if ($this->shouldShowReplicateAction()) {
            $actions['replicate'] = Tables\Actions\ReplicateAction::make()
                ->label('')
                ->tooltip(__('user::actions.replicate'))
                ->iconButton();
        }

        if (! $this->shouldShowDetachAction()) {
            $actions['delete'] = Tables\Actions\DeleteAction::make()
                ->tooltip(__('user::actions.delete'))
                ->iconButton();
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
     * Get table bulk actions.
     *
     * @return array<string, BulkAction>
     */
    protected function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make()
                ->label('')
                ->tooltip(__('user::actions.delete_selected'))
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation(),
        ];
    }

    /**
     * Get model class.
     *
     * @return class-string<Model>
     *
     * @throws \Exception Se non viene trovata una classe modello valida
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
