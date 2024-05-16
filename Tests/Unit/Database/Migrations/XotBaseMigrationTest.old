<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit\Database\Migrations;

use Modules\Xot\Database\Migrations\XotBaseMigration;
use Tests\TestCase;

/**
 * Class XotBaseMigrationTest.
 *
 * @covers \Modules\Xot\Database\Migrations\XotBaseMigration
 */
final class XotBaseMigrationTest extends TestCase
{
    private XotBaseMigration $xotBaseMigration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xotBaseMigration = $this->getMockBuilder(XotBaseMigration::class)
            ->setConstructorArgs([])
            ->getMockForAbstractClass();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->xotBaseMigration);
    }

    public function testGetModel(): void
    {
        $expected = '42';
        $property = (new \ReflectionClass(XotBaseMigration::class))
            ->getProperty('model');
        $property->setValue($this->xotBaseMigration, $expected);
        self::assertSame($expected, $this->xotBaseMigration->getModel());
    }

    public function testGetTable(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetConn(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetSchemaManager(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetTableDetails(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetTableIndexes(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testTableExists(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testHasColumn(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetColumnType(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testIsColumnType(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testQuery(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testHasIndex(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testDropIndex(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testHasIndexName(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testHasPrimaryKey(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testDropPrimaryKey(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testDown(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testTableDrop(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testRename(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testRenameTable(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testRenameColumn(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testTableCreate(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testTableUpdate(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testTimestamps(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testUpdateTimestamps(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testUpdateUser(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testUpdateUserKeyString(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testUpdateUserKeyInt(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testUpdateUserKeyInteger(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
