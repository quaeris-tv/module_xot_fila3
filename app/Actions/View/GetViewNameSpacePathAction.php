<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\View;

use Illuminate\Support\Arr;
use Illuminate\View\FileViewFinder;
use Modules\Xot\Datas\XotData;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GetViewNameSpacePathAction
{
    use QueueableAction;

    /**
     * @throws \Exception
     */
    public function execute(string $ns): string
    {
        $xot = XotData::make();
        /** @var FileViewFinder $finder */
        $finder = view()->getFinder();
        $viewHints = [];
        if (method_exists($finder, 'getHints')) {
            /** @var array<string, array<string>> $viewHints */
            $viewHints = $finder->getHints();
        }

        $path = Arr::get($viewHints, "$ns.0");
        if (! empty($path) && is_string($path)) {
            return $path;
        }

        if (\in_array($ns, ['pub_theme', 'adm_theme'], false)) {
            Assert::string($theme_name = ($xot->{$ns} ?? ''));

            return base_path('Themes/'.$theme_name);
        }

        throw new \Exception('View namespace not found['.$ns.'].');
    }
}
