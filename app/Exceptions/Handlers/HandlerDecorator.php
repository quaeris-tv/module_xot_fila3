<?php

declare(strict_types=1);

namespace Modules\Xot\Exceptions\Handlers;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class HandlerDecorator implements ExceptionHandler
{
    public function __construct(
        private readonly HandlersRepository $handlers
    ) {
    }

    public function __call(string $name, array $parameters): mixed
    {
        if (method_exists($this->handlers, $name)) {
            return $this->handlers->$name(...$parameters);
        }

        throw new \BadMethodCallException(sprintf(
            'Il metodo %s non esiste nell\'handler delle eccezioni',
            $name
        ));
    }

    public function report(Throwable $e): void
    {
        $this->handlers->getReporters($e)->each(fn ($reporter) => $reporter->report($e));
    }

    public function render($request, Throwable $e): SymfonyResponse
    {
        return $this->handlers->getRenderers($e)->first()?->render($request, $e);
    }

    public function renderForConsole($output, Throwable $e): void
    {
        $this->handlers->getConsoleRenderers($e)->first()?->render($output, $e);
    }

    public function reporter(): HandlersRepository
    {
        return $this->handlers;
    }

    public function renderer(): HandlersRepository
    {
        return $this->handlers;
    }

    public function consoleRenderer(): HandlersRepository
    {
        return $this->handlers;
    }

    public function shouldReport(\Throwable $e): bool
    {
        return $this->handlers->shouldReport($e);
    }
}
