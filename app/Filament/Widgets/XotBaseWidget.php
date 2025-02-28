<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Widgets;

<<<<<<< HEAD
use Filament\Widgets\Widget as FilamentWidget;
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\WidgetConfiguration;
=======
use Illuminate\Support\Facades\Cache;
use Filament\Widgets\WidgetConfiguration;
use Filament\Widgets\Widget as FilamentWidget;
>>>>>>> 8045aaff (up)
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

<<<<<<< HEAD

    public function __construct()
    {
        //parent::__construct();//Cannot call constructor
        $view = app(GetViewByClassAction::class)->execute(static::class);
        static::$view = $view;
=======
    
    public function __construct()
    {
        //parent::__construct();//Cannot call constructor
        $view=app(GetViewByClassAction::class)->execute(static::class);
        static::$view=$view;
>>>>>>> 8045aaff (up)
    }
}
