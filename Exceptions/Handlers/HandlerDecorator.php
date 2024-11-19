<?php

declare(strict_types=1);

/**
 * --.
 */

namespace Modules\Xot\Exceptions\Handlers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Response;

/**
 * The exception handler decorator.
 */
class HandlerDecorator implements ExceptionHandler
{
    /**
     * The custom handlers repository.
     */
    protected HandlersRepository $repository;

    /**
     * Set the dependencies.
     * The default Laravel exception handler.
     */
    public function __construct(
        protected ExceptionHandler $defaultHandler,
        HandlersRepository $repository,
    ) {
        $this->repository = $repository;
    }

    /**
     * Proxy other calls to default Laravel exception handler.
     */
    public function __call(string $name, array $parameters): mixed
    {
        /**
         * @var callable
         */
        $callable = [$this->defaultHandler, $name];

        return \call_user_func_array($callable, $parameters);
    }

    /**
     * Report or log an exception.
     *
     * @throws \Throwable
     *
     * @return void|mixed
     */
    public function report(\Throwable $e)
    {
        foreach ($this->repository->getReportersByException($e) as $reporter) {
            if ($report = $reporter($e)) {
                return $report;
            }
        }

        $this->defaultHandler->report($e);
    }

    /**
     * Register a custom handler to report exceptions.
     */
    public function reporter(callable $reporter): int
    {
        return $this->repository->addReporter($reporter);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @see laravel\vendor\laravel\framework\src\Illuminate\Contracts\Debug\ExceptionHandler.php
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Throwable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, \Throwable $e)
    {
        foreach ($this->repository->getRenderersByException($e) as $renderer) {
            if ($render = $renderer($e, $request)) {
                return $render;
            }
        }

        return $this->defaultHandler->render($request, $e);
    }

    /**
     * Register a custom handler to render exceptions.
     */
    public function renderer(callable $renderer): int
    {
        return $this->repository->addRenderer($renderer);
    }

    /**
     * Render an exception to the console.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void|mixed
     *
     * @internal this method is not meant to be used or overwritten outside the framework
     */
    public function renderForConsole($output, \Throwable $e)
    {
        foreach ($this->repository->getConsoleRenderersByException($e) as $renderer) {
            if ($render = $renderer($e, $output)) {
                return $render;
            }
        }

        $this->defaultHandler->renderForConsole($output, $e);
    }

    /**
     * Register a custom handler to render exceptions in console.
     */
    public function consoleRenderer(callable $renderer): int
    {
        return $this->repository->addConsoleRenderer($renderer);
    }

    /**
     * Determine if the exception should be reported.
     *
     * @return bool
     */
    public function shouldReport(\Throwable $e)
    {
        return $this->defaultHandler->shouldReport($e);
    }
}
