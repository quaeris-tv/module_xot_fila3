<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\LogResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Xot\Filament\Resources\LogResource;

class ViewLog extends ViewRecord
{
    protected static string $resource = LogResource::class;
}
