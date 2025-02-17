<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Export;

use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Webmozart\Assert\Assert;

use function Safe\fclose;
use function Safe\fopen;
use function Safe\fputcsv;

class ExportXlsStreamByLazyCollection
{
    use QueueableAction;

    public function execute(
        LazyCollection $data,
        string $filename = 'test.csv',
        ?string $transKey = null,
        ?array $fields = null,
    ): StreamedResponse {
        $headers = [
            'Content-Disposition' => 'attachment; filename='.$filename,
        ];
        $head = $this->headings($data, $transKey);

        return response()->stream(
            static function () use ($data, $head): void {
                $file = fopen('php://output', 'w+');
                fputcsv($file, $head);

                foreach ($data as $key => $value) {
                    // if(!method_exists($value,'toArray')){
                    //    throw new \Exception('WIP['.__LINE__.']['.class_basename($this).']');
                    // }
                    /** @phpstan-ignore method.nonObject */
                    $data = $value->toArray();

                    fputcsv($file, $data);
                }
                $blanks = ["\t", "\t", "\t", "\t"];
                fputcsv($file, $blanks);
                $blanks = ["\t", "\t", "\t", "\t"];
                fputcsv($file, $blanks);
                $blanks = ["\t", "\t", "\t", "\t"];
                fputcsv($file, $blanks);

                fclose($file);
            },
            200,
            $headers
        );
    }

    public function headings(LazyCollection $data, ?string $transKey = null): array
    {
        /**
         * @var array
         */
        $head = $data->first();
        $headings = collect($head)->keys();
        if ($transKey !== null) {
            $headings = $headings->map(
                static function (string $item) use ($transKey) {
                    $key = $transKey.'.fields.'.$item;
                    $trans = trans($key);
                    if ($trans !== $key) {
                        return $trans;
                    }

                    Assert::string($item1 = Str::replace('.', '_', $item), '['.__LINE__.']['.__CLASS__.']');
                    $key = $transKey.'.fields.'.$item1;
                    $trans = trans($key);
                    if ($trans !== $key) {
                        return $trans;
                    }

                    return $item;
                }
            );
        }

        return $headings->toArray();
    }
}
