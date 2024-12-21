<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Xot\Actions\Model\UpdateAction;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

/**
 * Handles morphMany relationship updates for models.
 */
class MorphManyAction
{
    use QueueableAction;

    /**
     * Handle updating or creating related MorphMany models.
     *
     * @param Model       $model       The parent model
     * @param RelationDTO $relationDTO Data object containing relation details
     *
     * @throws \InvalidArgumentException If the relation is not a valid MorphMany instance
     * @throws \RuntimeException         If the relation data is invalid or the operation fails
     */
    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        // Validate that relationDTO->data is an array
        Assert::isArray($relationDTO->data, 'RelationDTO->data must be an array.');

        // If data is empty, clear the relation
        if (empty($relationDTO->data)) {
            $relation = $model->{$relationDTO->name}();
            Assert::isInstanceOf($relation, MorphMany::class, 'Relation must be an instance of MorphMany.');
            $relation->saveMany([]);

            return;
        }

        // Validate that the relation is a MorphMany instance
        Assert::isInstanceOf($relationDTO->rows, MorphMany::class, 'RelationDTO->rows must be an instance of MorphMany.');

        // Validate the related model
        $relatedModel = $relationDTO->related;
        Assert::isInstanceOf($relatedModel, Model::class, 'RelationDTO->related must be an instance of Model.');

        $keyName = $relatedModel->getKeyName();
        Assert::stringNotEmpty($keyName, 'The related model key name must be a non-empty string.');

        $models = [];
        $ids = [];

        foreach ($relationDTO->data as $data) {
            // Validate each data entry
            Assert::isArray($data, 'Each entry in RelationDTO->data must be an array.');
            Assert::allString(array_keys($data), 'Keys in $data must all be strings.');

            if (array_key_exists($keyName, $data)) {
                // Update existing model
                $updatedModel = app(UpdateAction::class)->execute($relatedModel, $data, []);
                Assert::isInstanceOf($updatedModel, Model::class, 'UpdateAction must return an instance of Model.');
                $ids[] = $updatedModel->getKey();
                $models[] = $updatedModel;
            } else {
                throw new \RuntimeException(sprintf('Key "%s" not found in relation data.', $keyName));
            }
        }

        // Save updated or created models
        $relation = $model->{$relationDTO->name}();
        Assert::isInstanceOf($relation, MorphMany::class, 'Relation must be an instance of MorphMany.');
        $relation->saveMany($models);
    }
}
