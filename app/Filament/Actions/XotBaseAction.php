<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Icon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

/**
 * Base class for Filament actions.
 *
 * @property ?Model $record The associated record for this action
 * @method static static make(?string $name = null) Create a new instance of the action
 */
abstract class XotBaseAction extends Action
{

}
