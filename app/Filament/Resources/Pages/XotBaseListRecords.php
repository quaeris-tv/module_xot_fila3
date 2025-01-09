<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Resources\Pages\ListRecords as FilamentListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\Xot\Filament\Actions\Header\ExportXlsAction;
use Modules\Xot\Filament\Traits\HasXotTable;
use Webmozart\Assert\Assert;

/**
 * Base class for list records pages.
 * @property ?string $model
 * @property ?string $resource
 * @property ?string $slug
 * @property TableLayoutEnum $layoutView 
 */
abstract class XotBaseListRecords extends FilamentListRecords
{
    use HasXotTable;

    /**
     * Get the table instance.
     */
    public function table(Table $table): Table
    {
        $defaultSort = $this->getDefaultSort();
        $column = key($defaultSort);
        $direction = current($defaultSort);

        return $table
            ->columns($this->getListTableColumns())
            ->defaultSort($column, $direction);
    }

    /**
     * Get the table columns.
     *
     * @return array<string, Tables\Columns\Column>
     */
    public function getListTableColumns(): array{
        return [];
    }

    /**
     * Get the default sort column and direction.
     *
     * @return array<string, string>
     */
    protected function getDefaultSort(): array
    {
        //return ['created_at' => 'desc'];
        return ['id' => 'desc'];
    }

    /**
     * Get the header actions.
     *
     * @return array<\Filament\Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
            ExportXlsAction::make(),
        ];
    }

    /**
     * Get the resource class name.
     *
     * @return class-string
     */
    public static function getResource(): string
    {
        $resource = Str::of(static::class)->before('\Pages\\')->toString();
        Assert::classExists($resource);

        return $resource;
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->fastPaginate(
            ('all' === $this->getTableRecordsPerPage())
                ? $query->count()
                : $this->getTableRecordsPerPage()
        );
    }
}
