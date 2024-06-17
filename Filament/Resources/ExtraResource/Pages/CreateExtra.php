<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ExtraResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Xot\Filament\Resources\ExtraResource;

class CreateExtra extends CreateRecord
{
    protected static string $resource = ExtraResource::class;
}
