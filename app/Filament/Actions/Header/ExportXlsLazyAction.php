<?php

/**
 * @see https://coderflex.com/blog/create-advanced-filters-with-filament
 */

declare(strict_types=1);

namespace Modules\Xot\Filament\Actions\Header;

// Header actions must be an instance of Filament\Actions\Action, or Filament\Actions\ActionGroup.
// use Filament\Tables\Actions\Action;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Actions\Export\ExportXlsByLazyCollection;
use Modules\Xot\Actions\Export\ExportXlsByQuery;
use Modules\Xot\Actions\Export\ExportXlsStreamByLazyCollection;
use Modules\Xot\Actions\GetTransKeyAction;
use Webmozart\Assert\Assert;

class ExportXlsLazyAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->translateLabel()
            
            ->tooltip(__('xot::actions.export_xls'))
            ->icon('heroicon-o-arrow-down-tray')
            ->action(static function (ListRecords $livewire) {
                $filename = class_basename($livewire).'-'.collect($livewire->tableFilters)->flatten()->implode('-').'.xlsx';
                $transKey = app(GetTransKeyAction::class)->execute($livewire::class);
                $transKey .= '.fields';

                $resource = $livewire->getResource();
                $fields = [];
                if (method_exists($resource, 'getXlsFields')) {
                    $fields = $resource::getXlsFields($livewire->tableFilters);
                    // Convertiamo tutti i valori a stringhe
                    if (is_array($fields)) {
                        $fields = array_map(fn ($field): string => (string) $field, $fields);
                    } else {
                        $fields = [];
                    }
                    Assert::isArray($fields);
                }

                $lazy = $livewire->getFilteredTableQuery();
                
                if ($lazy->count() < 7) {
                    Assert::isInstanceOf($lazy, Builder::class);
                    // Ottieni il criterio per la query
                    $query = $lazy;
                    
                    // Convertiamo l'array di campi in array<int|string, string>
                    $stringFields = array_values($fields);
                    
                    return app(ExportXlsByQuery::class)->execute(
                        $query, 
                        $filename, 
                        $stringFields, 
                        null
                    );
                }

                $lazyCursor = $lazy->cursor();

                if ($lazyCursor->count() > 3000) {
                    return app(ExportXlsStreamByLazyCollection::class)->execute(
                        $lazyCursor, 
                        $filename, 
                        $transKey, 
                        array_values($fields)
                    );
                }

                return app(ExportXlsByLazyCollection::class)->execute(
                    $lazyCursor, 
                    $filename, 
                    array_values($fields)
                );
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'export_xls';
    }
}
