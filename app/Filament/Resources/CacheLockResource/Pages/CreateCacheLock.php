<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\CacheLockResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Xot\Filament\Resources\CacheLockResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreateCacheLock extends XotBaseCreateRecord
{
    protected static string $resource = CacheLockResource::class;
}
