<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Traits;

use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Webmozart\Assert\Assert;
use Filament\Tables\Actions\Action;
use Modules\UI\Enums\TableLayoutEnum;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Relations\Relation;
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
    protected static bool $canReplicate = false;
    protected static bool $canView = true;
    protected static bool $canEdit = true;

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
     * Get global header actions, optimized with tooltips instead of labels.
     *
     * @return array<Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('')
                ->tooltip(static::trans('actions.create.tooltip'))
                ->icon('heroicon-o-plus')
                // ->iconButton()
                ->button(),
        ];
    }

    /**
     * Get table columns for grid layout.
     *
     * @return array<Tables\Columns\Column|Stack|Tables\Columns\Layout\Split>
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
        $c = count($this->getTableFilters()) + 1;
        if ($c > 6) {
            return 6;
        }

        return $c;
    }

    public function getTableRecordTitleAttribute(): string
    {
        return 'name';
    }

    public function getTableEmptyStateActions(): array
    {
        return [];
    }

    /**
     * Define the main table structure
     */
    public function table(Table $table): Table
    {
        // if (! $this->tableExists()) {
        //     $this->notifyTableMissing();

        //     return $this->configureEmptyTable($table);
        // }

        return $table
            ->recordTitleAttribute($this->getTableRecordTitleAttribute())
            ->columns($this->layoutView->getTableColumns())
            ->contentGrid($this->layoutView->getTableContentGrid())
            ->headerActions($this->getTableHeaderActions())
            ->filters($this->getTableFilters())
            ->filtersLayout(FiltersLayout::AboveContent)
            ->filtersFormColumns($this->getTableFiltersFormColumns());
    }

    /**
     * Get table filters.
     *
     * @return array<BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            TernaryFilter::make('is_active')
                ->label(__('user::fields.is_active.label')),
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