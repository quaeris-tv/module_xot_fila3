<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Tree;

use Illuminate\Support\Str;
use Modules\Xot\Contracts\HasRecursiveRelationshipsContract;
use Spatie\QueueableAction\QueueableAction;

class GetTreeOptionsByModelClassAction
{
    use QueueableAction;

    public array $options = [];

    /**
     * Summary of execute.
     *
     * @param  class-string  $class
     */
    public function execute(string $class): array
    {
        $rows = $class::tree()->get()->toTree();

        foreach ($rows as $row) {
            $this->options[$row->id] = $row->name;
            $this->parse($row);
        }

        return $this->options;
    }

    public function parse(HasRecursiveRelationshipsContract $model): void
    {
        foreach ($model->children as $child) {
            $this->options[$child->id] = Str::repeat('---', $child->depth).'   '.$child->name;
        }
    }
}
