<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Module;

class GetModulePathByGeneratorAction
{
    public function execute(string $moduleName, string $generatorPath): string
    {
        $relativePath = config('modules.paths.generator.'.$generatorPath.'.path');

        return module_path($moduleName, $relativePath);
    }
}
