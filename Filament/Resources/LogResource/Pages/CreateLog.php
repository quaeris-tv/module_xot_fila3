<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\LogResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Xot\Filament\Resources\LogResource;

class CreateLog extends CreateRecord
{
    protected static string $resource = LogResource::class;
}
