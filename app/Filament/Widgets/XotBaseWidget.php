<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Widgets;

use Filament\Widgets\Widget as FilamentWidget;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\WidgetConfiguration;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Modules\Xot\Actions\View\GetViewByClassAction;

/**
 * @property bool $shouldRender
 */
abstract class XotBaseWidget extends FilamentWidget
{
    use InteractsWithPageFilters;
    public string $title = '';
    public string $icon = '';
    protected static string $view = 'ui::empty';


    public function __construct()
    {
<<<<<<< HEAD
        $view = app(GetViewByClassAction::class)->execute(static::class);
        static::$view = $view;
=======
        //parent::__construct();//Cannot call constructor
        $view=app(GetViewByClassAction::class)->execute(static::class);
        static::$view=$view;

>>>>>>> 0943a3cb (.)
    }
}
