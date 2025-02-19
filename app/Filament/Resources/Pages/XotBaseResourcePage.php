<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Resources\Pages\Page as FilamentPage;

/**
 * Base Resource Page for Xot Module
 * Extends Filament's Page class to provide common functionality
 */
abstract class XotBaseResourcePage extends FilamentPage
{
    /**
     * Get view data for the page
     *
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [];
    }

    /**
     * Get the breadcrumbs for the page
     *
     * @return array<array<string, string>>
     */
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
