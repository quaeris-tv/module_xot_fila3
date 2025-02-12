<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Infolists\Components\Component;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord as FilamentViewRecord;

abstract class XotBaseViewRecord extends FilamentViewRecord
{
    // Aggiungi qui eventuali metodi o proprietà comuni a tutte le pagine di visualizzazione
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema($this->getInfolistSchema());
    }

    /**
     * @return array<Component>
     */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
