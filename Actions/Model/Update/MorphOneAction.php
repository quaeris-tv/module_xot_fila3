<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\App;
use Modules\Xot\Datas\RelationData as RelationDTO;
use Spatie\QueueableAction\QueueableAction;

class MorphOneAction
{
    use QueueableAction;

    /**
     * Undocumented function.
     */
    public function execute(Model $model, RelationDTO $relationDTO): void
    {
        /* con update or create crea sempre uno nuovo, con update e basta se non esiste non va a crearlo */
        // $rows = $model->$name();
        if (! $relationDTO->rows instanceof MorphOne) {
            throw new \Exception('['.__LINE__.']['.class_basename($this).']');
        }

        $rows = $relationDTO->rows;
        if ($rows->exists()) {
            $rows->update($relationDTO->data);
        } else {
            if (! isset($relationDTO->data['lang'])) {
                $relationDTO->data['lang'] = App::getLocale();
            }

            $rows->create($relationDTO->data);
        }
    }
}
