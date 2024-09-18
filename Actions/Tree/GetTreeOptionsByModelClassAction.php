<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Tree;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;
use Modules\Xot\Contracts\HasRecursiveRelationshipsContract;

class GetTreeOptionsByModelClassAction
{
    use QueueableAction;

    public array $options = [];

    /**
     * Summary of execute.
     *
     * @param class-string $class
     */
    public function execute(string $class, Model|callable|null $where): array
    {
        if($where == null) {
            $rows = $class::tree()->get()->toTree();
        } else {
            $rows = $class::treeOf($where)->get()->toTree();
        }

        foreach ($rows as $row) {
            $this->options[$row->getKey()] = $row->getLabel();
            $this->parse($row);
        }

        return $this->options;
    }

    public function parse(HasRecursiveRelationshipsContract $model): void
    {
        foreach ($model->children as $child) {
            $this->options[$child->getKey()] = Str::repeat('---', $child->depth).'   '.$child->getLabel();
        }
    }
}
