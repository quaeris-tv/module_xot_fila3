<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Export;

// use Modules\Xot\Services\ArrayService;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Xot\Exports\CollectionExport;
use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportXlsByCollection
{
    use QueueableAction;

    public function execute(
        Collection $collection
    ): BinaryFileResponse {
        $collectionExport = new CollectionExport($collection);
        $filename = 'test.xls';

        return Excel::download($collectionExport, $filename);
    }
}