<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Xot\Rules;

use Modules\Xot\Rules\DateTimeRule;
use Tests\TestCase;

/**
 * Class DateTimeRuleTest.
 *
 * @covers \Modules\Xot\Rules\DateTimeRule
 */
final class DateTimeRuleTest extends TestCase
{
    private DateTimeRule $dateTimeRule;

    protected function setUp(): void
    {
        parent::setUp();

        /* @todo Correctly instantiate tested object to use it. */
        $this->dateTimeRule = new DateTimeRule();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->dateTimeRule);
    }

    public function testPassesWhenOk(): void
    {
        /* @todo This test is incomplete. */
        self::assertTrue($this->dateTimeRule->passes('attribute', 'valid value'));
    }

    public function testPassesWhenFailed(): void
    {
        /* @todo This test is incomplete. */
        self::assertFalse($this->dateTimeRule->passes('attribute', 'invalid value'));
    }

    public function testMessage(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
