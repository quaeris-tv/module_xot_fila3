<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Xot\Exports;

use Illuminate\Support\LazyCollection;
use Mockery\Mock;
use Modules\Xot\Exports\LazyCollectionExport;
use Tests\TestCase;

/**
 * Class LazyCollectionExportTest.
 *
 * @covers \Modules\Xot\Exports\LazyCollectionExport
 */
final class LazyCollectionExportTest extends TestCase
{
    private LazyCollectionExport $lazyCollectionExport;

    private LazyCollection|Mock $collection;

    private string $transKey;

    private array $fields;

    protected function setUp(): void
    {
        parent::setUp();

        $this->collection = \Mockery::mock(LazyCollection::class);
        $this->transKey = '42';
        $this->fields = [];
        $this->lazyCollectionExport = new LazyCollectionExport($this->collection, $this->transKey, $this->fields);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->lazyCollectionExport, $this->collection, $this->transKey, $this->fields);
    }

    public function testMap(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testHeadings(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testCollection(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testIterator(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
