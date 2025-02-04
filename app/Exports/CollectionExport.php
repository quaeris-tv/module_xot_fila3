<?php

declare(strict_types=1);

namespace Modules\Xot\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Xot\Actions\Collection\TransCollectionAction;

class CollectionExport implements FromCollection, ShouldQueue, WithHeadings, WithMapping
{
    use Exportable;

    public array $headings;

    public ?string $transKey;

    /** @var array<int, string> */
    public ?array $fields = null;

    /**
     * @param  array<int, string>  $fields
     */
    public function __construct(
        public Collection $collection,
        ?string $transKey = null,
        array $fields = [],
    ) {
        $this->transKey = $transKey;
        $this->fields = $fields;
    }

    public function getHead(): Collection
    {
        if (\is_array($this->fields)) {
            return collect($this->fields);
        }

        /**
         * @var array
         */
        $head = $this->collection->first();

        return collect($head)->keys();
    }

    public function headings(): array
    {
        $headings = $this->getHead();
        $transKey = $this->transKey;
        $headings = app(TransCollectionAction::class)->execute($headings, $transKey);

        return $headings->toArray();
    }

    public function collection(): Collection
    {
        return $this->collection;
    }

    /**
     * @param  \Illuminate\Contracts\Support\Arrayable<(int|string), mixed>|iterable<(int|string), mixed>|null  $item
     */
    public function map($item): array
    {
        if ($this->fields === null) {
            return collect($item)->toArray();
        }

        // return collect($item)->only($this->fields)->toArray();
        $data = [];
        foreach ($this->fields as $field) {
            $value = data_get($item, $field);
            if (\is_object($value)) {
                if (enum_exists($value::class) && method_exists($value, 'getLabel')) {
                    $value = $value->getLabel();
                }
            }
            $data[$field] = $value;
        }

        return $data;
    }
}
