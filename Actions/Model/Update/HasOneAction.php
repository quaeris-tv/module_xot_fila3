<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;

/**
 * Class HasOneAction.
 *
 * Handles the update operation for HasOne relationships in Eloquent models.
 *
 * @template TModel of Model
 */
class HasOneAction
{
    use QueueableAction;

    /**
     * Execute the update operation for a HasOne relationship.
     *
     * @param Model       $model       The parent model instance
     * @param RelationDTO $relationDTO Data transfer object containing relationship information
     *
     * @throws \InvalidArgumentException When relationship type is invalid
     * @throws \RuntimeException         When relationship data is invalid
     */
    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        $this->validateRelation($relationDTO);
        $this->validateData($relationDTO);

        /** @var HasOne $relation */
        $relation = $relationDTO->rows;

        if ($relation->exists()) {
            $related = $model->{$relationDTO->name};
            if ($related instanceof Model) {
                $related->update($relationDTO->data);

                return;
            }
        }

        // If relation doesn't exist, create it
        $relation->create($relationDTO->data);
    }

    /**
     * Validates the relationship type.
     *
     * @throws \InvalidArgumentException
     */
    private function validateRelation(RelationDTO $relationDTO): void
    {
        if (! $relationDTO->rows instanceof HasOne) {
            throw new \InvalidArgumentException(sprintf('Expected HasOne relationship, got %s', get_debug_type($relationDTO->rows)));
        }
    }

    /**
     * Validates the relationship data.
     *
     * @throws \RuntimeException
     */
    private function validateData(RelationDTO $relationDTO): void
    {
        // if (! is_array($relationDTO->data)) {
        //    throw new \RuntimeException(sprintf('Expected array for relationship data, got %s', get_debug_type($relationDTO->data)));
        // }

        if (empty($relationDTO->data)) {
            throw new \RuntimeException('Relationship data cannot be empty');
        }
    }
}
