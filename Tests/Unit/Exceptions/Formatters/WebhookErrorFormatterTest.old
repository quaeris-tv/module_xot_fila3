<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit\Exceptions\Formatters;

use Mockery\Mock;
use Modules\Xot\Exceptions\Formatters\WebhookErrorFormatter;
use Tests\TestCase;

/**
 * Class WebhookErrorFormatterTest.
 *
 * @covers \Modules\Xot\Exceptions\Formatters\WebhookErrorFormatter
 */
final class WebhookErrorFormatterTest extends TestCase
{
    private WebhookErrorFormatter $webhookErrorFormatter;

    private \Throwable|Mock $exception;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exception = \Mockery::mock(\Throwable::class);
        $this->webhookErrorFormatter = new WebhookErrorFormatter($this->exception);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->webhookErrorFormatter, $this->exception);
    }

    public function testFormat(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
