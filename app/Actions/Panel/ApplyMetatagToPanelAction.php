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
        try {
            $metatag = MetatagData::make();

            return $panel
                ->colors($metatag->getColors())
                ->brandLogo($metatag->getLogoHeader())
                ->brandName($metatag->title)
                ->darkModeBrandLogo($metatag->getLogoHeaderDark())
                ->brandLogoHeight($metatag->getLogoHeight())
                ->favicon($metatag->getFavicon());
        } catch (\Exception $e) {
            // Log l'errore ma non bloccare l'applicazione
            \Illuminate\Support\Facades\Log::error('Error applying metatag to panel: ' . $e->getMessage());
            return $panel;
        }
    }
}
