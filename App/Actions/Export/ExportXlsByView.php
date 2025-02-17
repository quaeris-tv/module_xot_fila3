<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Export;

// use Modules\Xot\Services\ArrayService;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Xot\Exports\ViewExport;
use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportXlsByView
{
    use QueueableAction;

    public function execute(
        View $view,
        string $filename = 'test.xlsx',
        ?string $transKey = null,
        ?array $fields = null,
    ): BinaryFileResponse {
        $export = new ViewExport($view, $transKey, $fields);

        return Excel::download($export, $filename);
    }
}
