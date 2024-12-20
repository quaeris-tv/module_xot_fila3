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
 *
 * @method void execute(Model $model, RelationDTO $relationDTO)
 */
class MorphManyAction
{
    use QueueableAction;

    /**
     * Updates morphMany relationships for the given model.
     *
     * @throws \InvalidArgumentException When relation data is invalid
     * @throws \RuntimeException         When relation operation fails
     */
    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        Assert::isInstanceOf($model->{$relationDTO->name}(), MorphMany::class);
        Assert::isArray($relationDTO->data, 'Relation data must be an array');

        if ([] === $relationDTO->data) {
            $model->{$relationDTO->name}()->saveMany([]);

            return;
        }

        $related = $relationDTO->related;
        Assert::isInstanceOf($related, Model::class);

        $keyName = $related->getKeyName();
        $models = [];
        $ids = [];

        foreach ($relationDTO->data as $data) {
            Assert::isArray($data, 'Each relation item must be an array');

            if (! array_key_exists($keyName, $data)) {
                throw new \InvalidArgumentException(sprintf('Primary key "%s" missing in relation data', $keyName));
            }

            /** @var array<string, mixed> $typedData */
            $typedData = $data;

            $updatedModel = app(UpdateAction::class)->execute($related, $typedData, []);
            $ids[] = $updatedModel->getKey();
            $models[] = $updatedModel;
        }

        $model->{$relationDTO->name}()->saveMany($models);
    }
}
