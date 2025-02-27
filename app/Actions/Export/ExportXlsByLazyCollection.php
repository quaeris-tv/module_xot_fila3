<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Export;

use Illuminate\Http\Response;
use Illuminate\Support\LazyCollection;
use Modules\Xot\Exports\LazyCollectionExport;
use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportXlsByLazyCollection
{
    use QueueableAction;

    public function execute(
        LazyCollection $collection,
        string $filename = 'test.xlsx',
        ?string $transKey = null,
        array $fields = [],
    ): Response|BinaryFileResponse {
        $export = new LazyCollectionExport($collection, $transKey, $fields);

        return $export->download($filename);
    }
}
