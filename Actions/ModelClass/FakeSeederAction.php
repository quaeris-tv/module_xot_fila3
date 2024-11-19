<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\ModelClass;

use Filament\Notifications\Notification;
use Spatie\QueueableAction\QueueableAction;

class FakeSeederAction
{
    use QueueableAction;

    /**
     * execute a select query.
     */
    public function execute(string $modelClass, int $qty): void
    {
        $max = 200;
        $qty_to_do = $qty;
        if ($qty_to_do > $max) {
            $qty_to_do = $max;
        }

        $rows = $modelClass::factory()->count($qty_to_do)->make();
        $fillable = app($modelClass)->getFillable();
        $chunks = $rows->chunk(50);

        $chunks->each(function ($chunk) use ($modelClass) {
            $callback = function ($item) {
                /*
                dddx([
                    'item'=>$item,
                    'a'=>$item->getAttributes(),
                ]);
                return $item->only($fillable);
                */
                return $item->getAttributes();
            };
            $data = $chunk
                ->map($callback)
                ->toArray();

            $modelClass::insert($data);
        });

        $title = 'Created '.$qty_to_do.' '.$modelClass.' !';
        Notification::make()
            ->title($title)
            ->success()
            ->send();

        if ($qty > $max) {
            app(self::class)
                ->onQueue()
                ->execute($modelClass, $qty - $max);
        }
    }
}
