<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

/**
 * Class Modules\Xot\Filament\Pages\MainDashboard.
 */
class MainDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'xot::filament.pages.dashboard';

    public function mount(): void
    {
        Assert::notNull($user = auth()->user(), '['.__LINE__.']['.class_basename($this).']');
        $modules = $user->roles->filter(
            static function ($item) {
                return Str::endsWith($item->name, '::admin');
            }
        );

        if (1 === $modules->count()) {
            Assert::notNull($modules->first(), '['.__LINE__.']['.class_basename($this).']');
            $panel_name = $modules->first()->name;
            $module_name = Str::before($panel_name, '::admin');
            $url = '/'.$module_name.'/admin';
            redirect($url);
        }

        if (0 === $modules->count()) {
            $url = '/'.app()->getLocale();
            redirect($url);
        }
    }
}
