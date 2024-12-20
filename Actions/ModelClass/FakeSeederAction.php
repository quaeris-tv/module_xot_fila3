<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\QueueableAction\QueueableAction;

/**
 * Class FakeSeederAction.
 *
 * Handles the creation of fake model data for seeding purposes
 * with chunked processing and queue support for large datasets
 */
class FakeSeederAction
{
    use QueueableAction;

    private const MAX_RECORDS = 200;
    private const CHUNK_SIZE = 50;

    /**
     * Execute the fake data seeding process.
     *
     * @param class-string<Model> $modelClass The fully qualified model class name
     * @param int<1, max>         $qty        Number of records to generate
     *
     * @throws \InvalidArgumentException When model class is invalid or qty is less than 1
     */
    public function execute(string $modelClass, int $qty): void
    {
        if ($qty < 1) {
            throw new \InvalidArgumentException('Quantity must be greater than 0');
        }

        if (! class_exists($modelClass) || ! is_subclass_of($modelClass, Model::class) || ! in_array(HasFactory::class, class_uses_recursive($modelClass))) {
            throw new \InvalidArgumentException("Invalid model class or missing HasFactory trait: {$modelClass}");
        }

        $qtyToDo = min($qty, self::MAX_RECORDS);

        /** @var \Illuminate\Database\Eloquent\Factories\Factory<Model> $factory */
        $factory = $modelClass::factory();
        /** @var Collection<int, Model> $rows */
        $rows = $factory->count($qtyToDo)->make();

        /** @var Collection<int, Collection> $chunks */
        $chunks = $rows->chunk(self::CHUNK_SIZE);

        $chunks->each(function (Collection $chunk) use ($modelClass): void {
            /** @var array<int, array<string, mixed>> $data */
            $data = $chunk->map(fn (Model $item): array => $item->getAttributes())->all();
            $modelClass::insert($data);
        });

        $this->sendNotification($modelClass, $qtyToDo);

        if ($qty > self::MAX_RECORDS) {
            $this->queueRemainingRecords($modelClass, $qty);
        }
    }

    /**
     * Send a notification about the seeding completion.
     *
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $count
     */
    private function sendNotification(string $modelClass, int $count): void
    {
        $title = sprintf('Created %d %s !', $count, $modelClass);
        Notification::make()->title($title)->success()->send();
    }

    /**
     * Queue remaining records for processing.
     *
     * @param class-string<Model> $modelClass
     * @param int<1, max>         $qty
     */
    private function queueRemainingRecords(string $modelClass, int $qty): void
    {
        app(self::class)
            ->onQueue()
            ->execute($modelClass, $qty - self::MAX_RECORDS);
    }
}
