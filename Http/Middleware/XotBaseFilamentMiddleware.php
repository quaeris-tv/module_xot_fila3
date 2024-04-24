<?php

declare(strict_types=1);

namespace Modules\Xot\Http\Middleware;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Str;
use Nwidart\Modules\Laravel\Module;
use Webmozart\Assert\Assert;

abstract class XotBaseFilamentMiddleware extends Middleware
{
    public static string $module = 'Xot';

    public static string $context = 'filament';

    protected function authenticate($request, array $guards): void
    {
        $contextName = $this->getContextName();
        Assert::string($guardName = config(sprintf('%s.auth.guard', $contextName)), 'fix config ['.$contextName.'.auth.guard]');
        $guard = $this->auth->guard($guardName);

        if (! $guard->check()) {
            $this->unauthenticated($request, $guards);

            return;
        }

        $this->auth->shouldUse($guardName);

        $user = $guard->user();

        if ($user instanceof FilamentUser && method_exists($user, 'canAccessFilament')) {
            abort_if(! $user->canAccessPanel(), 403);

            return;
        }

        abort_if('local' !== config('app.env'), 403);
    }

    protected function redirectTo($request): string
    {
        $contextName = $this->getContextName();

        return route(sprintf('%s.auth.login', $contextName));
    }

    private function getModule(): Module|\Nwidart\Modules\Module
    {
        return app('modules')->findOrFail(static::$module);
    }

    /**
     * @throws \Exception
     */
    private function getContextName(): string
    {
        $this->getModule();
        if ('' === static::$context || '0' === static::$context) {
            throw new \Exception('Context has to be defined in your class');
        }

        return Str::slug(static::$context);
        // dddx(Str::of($module->getLowerName())->append('-')->append(Str::slug(static::$context))->kebab()->toString());
        // return Str::of($module->getLowerName())->append('-')->append(Str::slug(static::$context))->kebab()->toString();
    }
}
