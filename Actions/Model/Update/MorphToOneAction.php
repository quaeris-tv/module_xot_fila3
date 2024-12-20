<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Fidum\EloquentMorphToOne\MorphToOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Class MorphToOneAction.
 *
 * Handles the creation of MorphToOne relationship records
 *
 * @template TModel of Model
 */
class MorphToOneAction
{
    use QueueableAction;

    /**
     * Execute the action to create a MorphToOne relationship.
     *
     * @param Model       $model       The parent model
     * @param RelationDTO $relationDTO Data transfer object containing relationship information
     *
     * @throws \InvalidArgumentException When relation type is invalid
     */
    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        Assert::isInstanceOf($relation = $relationDTO->rows, MorphToOne::class);

        $data = $this->prepareData($relationDTO->data);

        $relation->create($data);
    }

    /**
     * Prepare the data array for creation.
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function prepareData(array $data): array
    {
        if (! isset($data['lang'])) {
            $data['lang'] = App::getLocale();
        }

        return $data;
    }
}
