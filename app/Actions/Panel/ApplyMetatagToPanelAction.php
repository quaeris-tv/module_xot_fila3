<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Panel;

use Filament\Panel;
use Modules\Xot\Datas\MetatagData;
use Spatie\QueueableAction\QueueableAction;

class ApplyMetatagToPanelAction
{
    use QueueableAction;

    /**
     * Apply metatag configuration to a Filament panel.
     */
    public function execute(Panel $panel): void
    {
        $metatag = MetatagData::make();

        $colors = $metatag->getColors();
        $formattedColors = [];

        foreach ($colors as $name => $shades) {
            if (is_array($shades)) {
                $formattedColors[$name] = array_combine(
                    array_keys($shades),
                    array_map(fn (string $shade) => $shade, $shades)
                );
            } else {
                $formattedColors[$name] = $shades;
            }
        }

        $panel->colors($formattedColors);
    }
}
