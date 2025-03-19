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
use Modules\Xot\Actions\Export\ExportXlsByCollection;
use Modules\Xot\Actions\GetTransKeyAction;
use Webmozart\Assert\Assert;

class ExportXlsAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->translateLabel()

            
            ->tooltip(__('xot::actions.export_xls'))

            // ->icon('heroicon-o-cloud-arrow-down')
            // ->icon('fas-file-excel')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(static function (ListRecords $livewire) {
                $filename = class_basename($livewire).'-'.collect($livewire->tableFilters)->flatten()->implode('-').'.xlsx';
                $transKey = app(GetTransKeyAction::class)->execute($livewire::class);
                $transKey .= '.fields';
                $query = $livewire->getFilteredTableQuery();
                // ->getQuery(); // Staudenmeir\LaravelCte\Query\Builder
                $rows = $query->get();
                $resource = $livewire->getResource();
                $fields = [];
                if (method_exists($resource, 'getXlsFields')) {
                    $fields = $resource::getXlsFields($livewire->tableFilters);
                    // Convertiamo tutti i valori a stringhe
                    if (is_array($fields)) {
                        $fields = array_map(function ($field): string {
                            if (is_scalar($field)) {
                                return (string) $field;
                            }
                            if (is_object($field) && method_exists($field, '__toString')) {
                                return (string) $field;
                            }
                            return '';
                        }, $fields);
                    } else {
                        $fields = [];
                    }
                    Assert::isArray($fields);
                }

                return app(ExportXlsByCollection::class)->execute(
                    $rows, 
                    $filename, 
                    $transKey, 
                    array_values($fields)
                );
            });
    }

    public static function getDefaultName(): ?string
    {
        return 'export_xls';
    }
}
