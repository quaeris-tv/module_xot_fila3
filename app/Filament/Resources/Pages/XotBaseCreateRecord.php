<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Resources\Pages\CreateRecord;

abstract class XotBaseCreateRecord extends CreateRecord
{
    /**
     * Get the number of form columns.
     */
    protected function getFormColumns(): int|array
    {
        return 1;
    }
}
