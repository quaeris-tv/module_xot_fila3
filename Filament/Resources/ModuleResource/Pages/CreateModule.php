<?php

<<<<<<< HEAD
declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\ModuleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Xot\Filament\Resources\ModuleResource;
=======
namespace Modules\Xot\Filament\Resources\ModuleResource\Pages;

use Modules\Xot\Filament\Resources\ModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
>>>>>>> d0eb3a4 (📝 (EnvData.php): Update method signatures in EnvData class to specify return types for better type safety and clarity)

class CreateModule extends CreateRecord
{
    protected static string $resource = ModuleResource::class;
}
