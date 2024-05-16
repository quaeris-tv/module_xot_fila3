<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Xot\Services;

use Modules\Xot\Services\ModuleService;
use Tests\TestCase;

/**
 * Class ModuleServiceTest.
 *
 * @covers \Modules\Xot\Services\ModuleService
 */
final class ModuleServiceTest extends TestCase
{
    private ModuleService $moduleService;

    protected function setUp(): void
    {
        parent::setUp();

        /* @todo Correctly instantiate tested object to use it. */
        $this->moduleService = new ModuleService();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->moduleService);
    }

    public function testGetInstance(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testMake(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testSetName(): void
    {
        $expected = '42';
        $property = (new \ReflectionClass(ModuleService::class))
            ->getProperty('name');
        $this->moduleService->setName($expected);
        self::assertSame($expected, $property->getValue($this->moduleService));
    }

    public function testGetModels(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
