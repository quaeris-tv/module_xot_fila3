<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Widgets;

<<<<<<< HEAD
use Filament\Widgets\Widget as FilamentWidget;
=======
>>>>>>> d5e9f6d7 (.)
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\WidgetConfiguration;
use Filament\Widgets\Widget as FilamentWidget;
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
        //parent::__construct();//Cannot call constructor
<<<<<<< HEAD
        $view = app(GetViewByClassAction::class)->execute(static::class);
        static::$view = $view;
=======
        $view=app(GetViewByClassAction::class)->execute(static::class);
        static::$view=$view;
>>>>>>> d5e9f6d7 (.)
    }
}
