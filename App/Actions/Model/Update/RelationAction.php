<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Actions\Model\FilterRelationsAction;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;
use RuntimeException;

class RelationAction
{
    use QueueableAction;

    /**
     * Undocumented function.
     */
    public function execute(Model $model, array $data): void
    {
        $relations = app(FilterRelationsAction::class)->execute($model, $data);
        
        foreach ($relations as $relation) {
            $relationType = class_basename(get_class($relation));
            
            $actionClass = match ($relationType) {
                'BelongsTo' => BelongsToAction::class,
                'BelongsToMany' => BelongsToManyAction::class,
                'HasMany' => HasManyAction::class,
                'HasOne' => HasOneAction::class,
                default => throw new RuntimeException("Unsupported relation type: $relationType"),
            };

            /** @var object $action */
            $action = app($actionClass);
            Assert::object($action);

            if (method_exists($action, 'execute')) {
                $action->execute($model, $relation);
            }
        }
    }
}
