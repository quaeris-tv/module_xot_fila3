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
 *
 * @property ?string $model
 * @property ?string $resource
 * @property ?string $slug
 * @property TableLayoutEnum $layoutView
 */
abstract class XotBaseListRecords extends FilamentListRecords
{
    use HasXotTable;


    /**
     * Get the table columns.
     *
     * @return array<string, Tables\Columns\Column>
     */
    abstract public function getListTableColumns(): array;

    /**
     * Get the default sort column and direction.
     *
     * @return array{id: 'desc'|'asc'}
     */
    protected function getDefaultSort(): array
    {
        return ['id' => 'desc'];
    }

    /**
     * Get the header actions.
     *
     * @return array<int, \Filament\Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            // \Filament\Actions\CreateAction::make(),
            ExportXlsAction::make('export_xls'),
        ];
    }

    /**
     * Get the resource class name.
     *
     * @return class-string
     */
    public static function getResource(): string
    {
        $resource = Str::of(static::class)->before('\\Pages\\')->toString();
        Assert::classExists($resource);

        return $resource;
    }

    /**
     * Paginate the table query.
     */
    protected function paginateTableQuery(Builder $query): Paginator
    {
        $perPage = $this->getTableRecordsPerPage();

        if ('all' === $perPage) {
            $count = $query->count();

            /* @var \Illuminate\Contracts\Pagination\Paginator */
            Assert::isInstanceOf($res = $query->fastPaginate($count), Paginator::class);
            return $res;
        }

        if (is_numeric($perPage)) {
            $perPageInt = (int) $perPage;
            Assert::greaterThan($perPageInt, 0);

            /* @var \Illuminate\Contracts\Pagination\Paginator */
            Assert::isInstanceOf($res = $query->fastPaginate($perPageInt), Paginator::class);
            return $res;
        }

        /* @var \Illuminate\Contracts\Pagination\Paginator */
        Assert::isInstanceOf($res = $query->fastPaginate(10), Paginator::class);
        return $res;
    }
}
