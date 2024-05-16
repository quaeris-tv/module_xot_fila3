<?php

declare(strict_types=1);

namespace Modules\Xot\Traits;

use Illuminate\Support\Str;
use Modules\Cms\Contracts\PanelContract;

/**
 * Trait CrudContainerItemJobTrait.
 */
trait CrudContainerItemJobTrait
{
    public function __call(string $name, array $arg): PanelContract
    {
        $func = '\Modules\Xot\Jobs\Crud\\'.Str::studly($name).'Job';

        return $func::dispatchNow($arg[1], $arg[2]);
    }
}
