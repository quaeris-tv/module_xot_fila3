<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Xot\Filament\Actions\Header\ExportXlsAction;

/**
 * Undocumented class.
 *
 * @property ?string $model
 */
abstract class XotBaseListRecords extends ListRecords
{
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
