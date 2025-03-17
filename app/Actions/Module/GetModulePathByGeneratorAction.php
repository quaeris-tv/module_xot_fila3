<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Module;

use Webmozart\Assert\Assert;

class GetModulePathByGeneratorAction
{
    public function execute(string $moduleName, string $generatorPath): string
    {
        $relativePath = config('modules.paths.generator.'.$generatorPath.'.path');

        $res = module_path($moduleName, $relativePath);
        Assert::string($res);

        return $res;
    }
}
