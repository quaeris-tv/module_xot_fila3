<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;
use Modules\Xot\Filament\Actions\Header\ExportXlsAction;

/**
 * Undocumented class.
 *
 * @property ?string $model
 */
abstract class XotBaseListRecords extends ListRecords
{
    public TableLayoutEnum $layoutView = TableLayoutEnum::LIST;

    protected function getTableHeaderActions(): array
    {
        return [
            TableLayoutToggleTableAction::make(),
        ];
    }

    protected function getHeaderActions(): array
    {
        /*
        $anno=Arr::get($this->tableFilters,'anno.value');
        return [
            Actions\CreateAction::make(),
            app(CopyFromLastYearButton::class)->execute(Assenze::class,'anno',$anno),
        ];
        */
        return [
            // app(ExportButton::class)->execute(),
            // app(ImportButton::class)->execute(),
            ExportXlsAction::make(),
        ];
    }
}
