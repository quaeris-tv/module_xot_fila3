<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Xot\Services;

use Modules\Xot\Services\XLSService;
use Tests\TestCase;
use XLSService as XLSServiceAlias;

/**
 * Class XLSServiceTest.
 *
 * @covers \Modules\Xot\Services\XLSService
 */
final class XLSServiceTest extends TestCase
{
    private XLSService $xLSService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xLSService = new XLSService();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->xLSService);
    }

    public function testGetInstance(): void
    {
        $expected = \Mockery::mock(XLSServiceAlias::class);
        $property = (new \ReflectionClass(XLSService::class))
            ->getProperty('instance');
        $property->setValue(null, $expected);
        self::assertSame($expected, XLSService::getInstance());
    }

    public function testMake(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetNameFromNumber(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testCheckValidUrls(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testFromInputFileName(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testFromRequestFile(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testFromFilePath(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetData(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
