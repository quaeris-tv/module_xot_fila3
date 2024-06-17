<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\CacheResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Xot\Filament\Resources\CacheResource;

class CreateCache extends CreateRecord
{
    protected static string $resource = CacheResource::class;
}
