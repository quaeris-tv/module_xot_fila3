<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources\Pages;

use Filament\Resources\Pages\Page as FilamentPage;

<<<<<<< HEAD
/**
 * Base Resource Page for Xot Module
 * Extends Filament's Page class to provide common functionality
 */
abstract class XotBaseResourcePage extends FilamentPage
=======
<<<<<<< HEAD
<<<<<<< HEAD
abstract class XotBaseResourcePage extends FilamentResourcePage
>>>>>>> f7282529 (.)
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
<<<<<<< HEAD
=======
=======
abstract class XotBaseResourcePage extends Page {}
>>>>>>> 2f0934a2 (up)
=======
abstract class XotBaseResourcePage extends Page {}
>>>>>>> 94ea520a (up)
>>>>>>> f7282529 (.)
