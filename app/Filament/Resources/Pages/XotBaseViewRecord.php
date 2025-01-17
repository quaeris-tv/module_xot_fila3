<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;

abstract class XotBaseViewRecord extends ViewRecord
{
    // Aggiungi qui eventuali metodi o proprietà comuni a tutte le pagine di visualizzazione
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema($this->getInfolistSchema());
    }

    public function getInfolistSchema(): array
    {
        return [];
    }
}
