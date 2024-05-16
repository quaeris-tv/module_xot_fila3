<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Export;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
// use Modules\Xot\Services\ArrayService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Xot\Exports\CollectionExport;
use Spatie\QueueableAction\QueueableAction;

class XlsByModelClassAction
{
    use QueueableAction;

    public function execute(string $modelClass, array $with = [], array $where = [], array $includes = [],
        array $excludes = [], array $headings = [], callable $callback = null)
    {
        $rows = app($modelClass)
            ->with($with)
            ->where($where);

        $rows = count($includes) > 0 ? $rows->get($includes) : $rows->get();

        if (count($excludes) > 0) {
            $rows = $rows->makeHidden($excludes);
        }

        if (null != $callback) {
            $rows = $rows->map($callback);
        }

        $collectionExport = new CollectionExport($rows, $headings);
        $filename = $this->getExportName($modelClass);

        return Excel::download($collectionExport, $filename);
    }

    private function getExportName(string $modelClass): string
    {
        return sprintf(
            '%s %s.xlsx',
            Str::slug(class_basename($modelClass)),
            Carbon::now()->format('d-m-Y His'),
        );
    }
}
