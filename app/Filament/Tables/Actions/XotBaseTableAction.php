<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Tables\Actions;

use Filament\Tables\Actions\Action;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Model;
use Closure;

abstract class XotBaseTableAction extends Action
{
    /**
     * @var ?Closure
     */
    protected $authorizeUsing = null;

    /**
     * @var ?Closure
     */
    protected $beforeActionUsing = null;

    /**
     * @var ?Closure
     */
    protected $afterActionUsing = null;

    /**
     * Set the authorization callback.
     */
    public function authorizeUsing(?Closure $callback): static
    {
        $this->authorizeUsing = $callback;

        return $this;
    }

    /**
     * Set the before action callback.
     */
    public function beforeActionUsing(?Closure $callback): static
    {
        $this->beforeActionUsing = $callback;

        return $this;
    }

    /**
     * Set the after action callback.
     */
    public function afterActionUsing(?Closure $callback): static
    {
        $this->afterActionUsing = $callback;

        return $this;
    }

    /**
     * Configure the action for success.
     */
    public function success(): static
    {
        return $this
            ->color(Color::Success)
            ->icon('heroicon-o-check-circle');
    }

    /**
     * Configure the action for danger.
     */
    public function danger(): static
    {
        return $this
            ->color(Color::Danger)
            ->icon('heroicon-o-exclamation-circle')
            ->requiresConfirmation();
    }

    /**
     * Configure the action for warning.
     */
    public function warning(): static
    {
        return $this
            ->color(Color::Warning)
            ->icon('heroicon-o-exclamation-triangle')
            ->requiresConfirmation();
    }

    /**
     * Configure the action for info.
     */
    public function info(): static
    {
        return $this
            ->color(Color::Info)
            ->icon('heroicon-o-information-circle');
    }

    /**
     * Check if the action is authorized.
     */
    public function isAuthorized(): bool
    {
        if (! $this->authorizeUsing) {
            return true;
        }

        return (bool) call_user_func($this->authorizeUsing, $this->getRecord());
    }

    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function execute()
    {
        if (! $this->isAuthorized()) {
            $this->failure();

            return;
        }

        if ($this->beforeActionUsing) {
            $result = call_user_func($this->beforeActionUsing, $this->getRecord());

            if ($result === false) {
                $this->failure();

                return;
            }
        }

        $result = parent::execute();

        if ($this->afterActionUsing) {
            call_user_func($this->afterActionUsing, $this->getRecord(), $result);
        }

        return $result;
    }

    /**
     * Handle action failure.
     */
    protected function failure(): void
    {
        $this->failure = true;

        $this->failureNotification()?->send();

        if ($this->shouldCloseModalOnFailure()) {
            $this->closeModalOnFailure = true;
        }
    }
}
