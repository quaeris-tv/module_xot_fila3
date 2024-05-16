<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Xot\Exports;

use Illuminate\Support\Collection;
use Mockery\Mock;
use Modules\Xot\Exports\CollectionExport;
use Tests\TestCase;

/**
 * Class CollectionExportTest.
 *
 * @covers \Modules\Xot\Exports\CollectionExport
 */
final class CollectionExportTest extends TestCase
{
    private CollectionExport $collectionExport;

    private Collection|Mock $collection;

    private string $transKey;

    private array $fields;

    protected function setUp(): void
    {
        parent::setUp();

        $this->collection = \Mockery::mock(Collection::class);
        $this->transKey = '42';
        $this->fields = [];
        $this->collectionExport = new CollectionExport($this->collection, $this->transKey, $this->fields);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->collectionExport, $this->collection, $this->transKey, $this->fields);
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
}
