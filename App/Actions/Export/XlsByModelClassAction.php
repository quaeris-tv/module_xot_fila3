<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Export;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Xot\Actions\Model\GetTransKeyByModelClassAction;
// use Modules\Xot\Services\ArrayService;
use Modules\Xot\Exports\CollectionExport;
use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class XlsByModelClassAction
{
    use QueueableAction;

    public function execute(
        string $modelClass,
        array $where = [],
        array $includes = [],
        array $excludes = [],
        ?callable $callback = null,
    ): BinaryFileResponse {
        $with = $this->getWithByIncludes($includes);

        $rows = app($modelClass)
            ->with($with)
            ->where($where);

        $rows = $rows->get();
        if ($includes !== []) {
            $rows = $rows->map(
                static function ($item) use ($includes) {
                    $data = [];
                    foreach ($includes as $include) {
                        $data[$include] = data_get($item, $include);
                    }

                    return $data;
                }
            );
        }

        if ($excludes !== []) {
            $rows = $rows->makeHidden($excludes);
        }

        if ($callback !== null) {
            $rows = $rows->map($callback);
        }

        $transKey = app(GetTransKeyByModelClassAction::class)->execute($modelClass);
        $collectionExport = new CollectionExport($rows, $transKey);
        $filename = $this->getExportName($modelClass);

        return Excel::download($collectionExport, $filename);
    }

    private function getWithByIncludes(array $includes): array
    {
        $with = [];
        foreach ($includes as $include) {
            $tmp = explode('.', (string) $include);
            if (! isset($tmp[0])) {
                continue;
            }
            if (! Str::contains($include, '.')) {
                continue;
            }
            $with[] = $tmp[0];
        }

        return $with;
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
