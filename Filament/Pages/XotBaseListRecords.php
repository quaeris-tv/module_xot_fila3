<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Illuminate\Support\Str;
use Modules\UI\Enums\TableLayoutEnum;
use Filament\Resources\Pages\ListRecords;
use Modules\Xot\Filament\Traits\TransTrait;
use Modules\Xot\Filament\Traits\HasXotTable;
use Modules\Xot\Filament\Actions\Header\ExportXlsAction;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;

/**
 * Undocumented class.
 *
 * @property ?string $model
 */
abstract class XotBaseListRecords extends ListRecords
{
    use TransTrait;
    
    use HasXotTable;
    
    
    public static function getResource():string {
        $resource=Str::of(static::class)->before('\Pages\\')->toString();
        return $resource;
    }
    
    /*
    public TableLayoutEnum $layoutView = TableLayoutEnum::LIST;
    protected function getTableHeaderActions(): array
    {
        return [
            TableLayoutToggleTableAction::make(),
        ];
    }

    protected function getHeaderActions(): array
    {
        
        $anno=Arr::get($this->tableFilters,'anno.value');
        return [
            Actions\CreateAction::make(),
            app(CopyFromLastYearButton::class)->execute(Assenze::class,'anno',$anno),
        ];
        
        return [
            // app(ExportButton::class)->execute(),
            // app(ImportButton::class)->execute(),
            ExportXlsAction::make(),
        ];
    }
    */
}
