<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Xot\Services;

use ImportService as ImportServiceAlias;
use Modules\Xot\Services\ImportService;
use Tests\TestCase;

/**
 * Class ImportServiceTest.
 *
 * @covers \Modules\Xot\Services\ImportService
 */
final class ImportServiceTest extends TestCase
{
    private ImportService $importService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->importService = new ImportService();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->importService);
    }

    public function testGetInstance(): void
    {
        $expected = \Mockery::mock(ImportServiceAlias::class);
        $property = (new \ReflectionClass(ImportService::class))
            ->getProperty('instance');
        $property->setValue(null, $expected);
        self::assertSame($expected, ImportService::getInstance());
    }

    public function testMake(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testSetClientOptions(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testInitCookieJar(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testImportInit(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testEnableCharles(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testEnableCookie(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testEnableRedirect(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testDisableRedirect(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetConfig(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetEffectiveUrl(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testJqueryRequest(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGRequest(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetStatusCode(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetRedirectHistory(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetCacheKey(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testCacheRequest(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testCacheRequestFile(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetAddressFields(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testDownload(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testPixabay(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testTrans(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGoogleTrans(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testMymemoryTrans(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetForms(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testFormRequest(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
