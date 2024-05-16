<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Xot\Exports;

use Mockery\Mock;
use Modules\Xot\Exports\QueryExport;
use Staudenmeir\LaravelCte\Query\Builder;
use Tests\TestCase;

/**
 * Class QueryExportTest.
 *
 * @covers \Modules\Xot\Exports\QueryExport
 */
final class QueryExportTest extends TestCase
{
    private QueryExport $queryExport;

    private Builder|Mock $query;

    private string $transKey;

    private array $fields;

    protected function setUp(): void
    {
        parent::setUp();

        $this->query = \Mockery::mock(Builder::class);
        $this->transKey = '42';
        $this->fields = [];
        $this->queryExport = new QueryExport($this->query, $this->transKey, $this->fields);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->queryExport, $this->query, $this->transKey, $this->fields);
    }

    public function testHeadings(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testQuery(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testChunkSize(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
