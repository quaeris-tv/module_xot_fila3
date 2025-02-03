<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

abstract class XotBaseEditRecord extends EditRecord
{
    /**
     * Get the number of form columns.
     */
    protected function getFormColumns(): int|array
    {
        return 1;
    }
}
