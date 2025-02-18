<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Panel;

use Filament\Panel;
use Modules\Xot\Datas\MetatagData;
use Spatie\QueueableAction\QueueableAction;

class ApplyMetatagToPanelAction
{
    use QueueableAction;

    public function execute(Panel &$panel): Panel
    {
        $metatag = MetatagData::make();

        return $panel
            // @phpstan-ignore argument.type
            ->colors($metatag->getColors())
            ->brandLogo($metatag->getLogoHeader())
            ->brandName($metatag->title)
            ->darkModeBrandLogo($metatag->getLogoHeaderDark())
            ->brandLogoHeight($metatag->getLogoHeight())
            ->favicon($metatag->getFavicon());
    }
}
