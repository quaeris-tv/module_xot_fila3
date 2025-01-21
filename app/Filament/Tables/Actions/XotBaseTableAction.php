<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Tables\Actions;

use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

/**
 * @property ?Model $record
 */
abstract class XotBaseTableAction extends Action
{
    public function getRecord(): ?Model
    {
        return $this->record;
    }
}
