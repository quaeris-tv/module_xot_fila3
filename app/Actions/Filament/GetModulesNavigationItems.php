<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Filament;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Tenant\Services\TenantService;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class GetModulesNavigationItems
{
    use QueueableAction;

    /**
     * Undocumented function.
     *
     * @return array<NavigationItem>
     */
    public function execute(): array
    {
        $navs = [];

        $modules = TenantService::allModules();

        foreach ($modules as $module) {
            // if (! Filament::auth()->check()) {
            //    continue;
            // }
            $module_low = Str::lower($module);
            // if (! auth()->user()->can('module_'.$module_low)) {
            //    continue;
            // }

            $relativeConfigPath = config('modules.paths.generator.config.path');
            try {
                $configPath = module_path($module, $relativeConfigPath);
            } catch (\Error $e) {
                $configPath = base_path('Modules/'.$module.'/'.$relativeConfigPath);
            }
            /**
             * @var array
             */
            $config = File::getRequire($configPath.'/config.php');
            Assert::string($icon = $config['icon'] ?? 'heroicon-o-question-mark-circle');
            $role = $module_low.'::admin';
            Assert::integer($navigation_sort = $config['navigation_sort'] ?? 1);
            $nav = NavigationItem::make($module)
                ->url('/'.$module_low.'/admin')
                ->icon($icon)
                ->group('Modules')
                ->sort($navigation_sort)
                ->visible(
                    static function () use ($role) {
                        $user = Filament::auth()->user();
                        if ($user === null) {
                            return false;
                        }

                        // Call to an undefined method Illuminate\Foundation\Auth\User::hasRole()
                        return $user->hasRole($role);
                    }
                );

            $navs[] = $nav;
        }

        return $navs;
    }
}
