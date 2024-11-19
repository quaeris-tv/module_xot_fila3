<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\File;

use Modules\Xot\Datas\XotData;
use Spatie\QueueableAction\QueueableAction;

class GetViewNameSpacePathAction
{
    use QueueableAction;

    public function execute(string $ns): ?string
    {
        $xot = XotData::make();
        $finder = view()->getFinder();
        $viewHints = [];
        if (method_exists($finder, 'getHints')) {
            $viewHints = $finder->getHints();
        }

        if (isset($viewHints[$ns])) {
            return $viewHints[$ns][0];
        }

        if (\in_array($ns, ['pub_theme'], false)) {
            $theme_name = $xot->{$ns};

            return base_path('Themes/'.$theme_name);
        }

        return null;
    }
}
