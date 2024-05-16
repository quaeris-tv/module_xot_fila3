<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Xot\Services;

use Modules\Xot\Services\UrlService;
use Tests\TestCase;
use UrlService as UrlServiceAlias;

/**
 * Class UrlServiceTest.
 *
 * @covers \Modules\Xot\Services\UrlService
 */
final class UrlServiceTest extends TestCase
{
    private UrlService $urlService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->urlService = new UrlService();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->urlService);
    }

    public function testGetInstance(): void
    {
        $expected = \Mockery::mock(UrlServiceAlias::class);
        $property = (new \ReflectionClass(UrlService::class))
            ->getProperty('instance');
        $property->setValue(null, $expected);
        self::assertSame($expected, UrlService::getInstance());
    }

    public function testMake(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testCheckValidUrl(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
