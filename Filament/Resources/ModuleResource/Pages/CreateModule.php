<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ModuleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Xot\Filament\Resources\ModuleResource;

class CreateModule extends CreateRecord
{
    protected static string $resource = ModuleResource::class;
}
