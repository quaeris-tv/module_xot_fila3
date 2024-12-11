<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\XotBaseResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Traits\HasXotTable;
use Filament\Resources\Pages\ManageRelatedRecords;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;

abstract class XotBaseManageRelatedRecords extends ManageRelatedRecords
{
    use HasXotTable;
    use NavigationLabelTrait;

    // protected static string $resource;

}
