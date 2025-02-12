<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\File;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use Spatie\QueueableAction\QueueableAction;

use function Safe\scandir;

class GetModulePathAction
{
    use QueueableAction;

    public function execute(string $moduleName): string
    {
        try {
            $module_path = Module::getModulePath($moduleName);
        } catch (\Exception) {
            $modulesPath = base_path('Modules');
            if (! File::exists($modulesPath)) {
                return __DIR__.'/../';
            }

            $files = scandir($modulesPath);
            $module_path = collect($files)
                ->filter(
                    static fn ($item): bool => Str::lower($item) === Str::lower($moduleName)
                )->first();
            $module_path = base_path('Modules/'.$module_path);
        }

        return $module_path;
    }
}
