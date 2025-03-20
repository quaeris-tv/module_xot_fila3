<?php

declare(strict_types=1);

namespace Modules\Xot\Exceptions\Handlers;

use Throwable;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container;

/**
 * The handlers repository.
 */
class HandlersRepository
{
    /**
     * @var array<string, array<string>>
     */
    private array $reporters = [];

    /**
     * @var array<string, array<string>>
     */
    private array $renderers = [];

    /**
     * @var array<string, array<string>>
     */
    private array $consoleRenderers = [];

    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get reporters for an exception.
     *
     * @param Throwable $e
     * @return array<object>
     */
    public function getReportersByException(Throwable $e): array
    {
        return $this->getHandlersByException($e, $this->reporters);
    }

    /**
     * Get renderers for an exception.
     *
     * @param Throwable $e
     * @return array<object>
     */
    public function getRenderersByException(Throwable $e): array
    {
        return $this->getHandlersByException($e, $this->renderers);
    }

    /**
     * Get console renderers for an exception.
     *
     * @param Throwable $e
     * @return array<object>
     */
    public function getConsoleRenderersByException(Throwable $e): array
    {
        return $this->getHandlersByException($e, $this->consoleRenderers);
    }

    /**
     * Add a reporter.
     *
     * @param string $reporter
     * @param string $exceptionType
     */
    public function addReporter(string $reporter, string $exceptionType): void
    {
        $this->reporters[$exceptionType][] = $reporter;
    }

    /**
     * Add a renderer.
     *
     * @param string $renderer
     * @param string $exceptionType
     */
    public function addRenderer(string $renderer, string $exceptionType): void
    {
        $this->renderers[$exceptionType][] = $renderer;
    }

    /**
     * Add a console renderer.
     *
     * @param string $renderer
     * @param string $exceptionType
     */
    public function addConsoleRenderer(string $renderer, string $exceptionType): void
    {
        $this->consoleRenderers[$exceptionType][] = $renderer;
    }

    /**
     * Get handlers for an exception.
     *
     * @param Throwable $e
     * @param array<string, array<string>> $handlers
     * @return array<object>
     */
    private function getHandlersByException(Throwable $e, array $handlers): array
    {
        return Collection::make($handlers)
            ->filter(fn (array $_, string $type) => is_a($e, $type))
            ->flatten()
            ->map(fn (string $handler) => $this->container->make($handler))
            ->all();
    }

    public function getReporters(Throwable $e): Collection
    {
        return collect($this->reporters)
            ->filter(fn ($handler) => $this->handlesException($handler, $e))
            ->map(fn ($handler) => $this->resolveHandler($handler));
    }

    public function getRenderers(Throwable $e): Collection
    {
        return collect($this->renderers)
            ->filter(fn ($handler) => $this->handlesException($handler, $e))
            ->map(fn ($handler) => $this->resolveHandler($handler));
    }

    public function getConsoleRenderers(Throwable $e): Collection
    {
        return collect($this->consoleRenderers)
            ->filter(fn ($handler) => $this->handlesException($handler, $e))
            ->map(fn ($handler) => $this->resolveHandler($handler));
    }

    public function addReporter(string|callable $handler): void
    {
        $this->reporters[] = $handler;
    }

    public function addRenderer(string|callable $handler): void
    {
        $this->renderers[] = $handler;
    }

    public function addConsoleRenderer(string|callable $handler): void
    {
        $this->consoleRenderers[] = $handler;
    }

    public function shouldReport(Throwable $e): bool
    {
        return true;
    }

    private function handlesException(string|callable $handler, Throwable $e): bool
    {
        if (is_string($handler)) {
            return is_a($e, $handler);
        }

        return true;
    }

    private function resolveHandler(string|callable $handler): object|callable
    {
        if (is_string($handler)) {
            return $this->container->make($handler);
        }

        return $handler;
    }
}
