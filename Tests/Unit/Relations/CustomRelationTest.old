<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Xot\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mockery\Mock;
use Modules\Xot\Relations\CustomRelation;
use Tests\TestCase;

/**
 * Class CustomRelationTest.
 *
 * @covers \Modules\Xot\Relations\CustomRelation
 */
final class CustomRelationTest extends TestCase
{
    private CustomRelation $customRelation;

    private Builder|Mock $query;

    private Model|Mock $model;

    private \Closure|Mock $baseConstraints;

    private \Closure|Mock $eagerConstraints;

    private \Closure|Mock $eagerMatcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->query = \Mockery::mock(Builder::class);
        $this->model = \Mockery::mock(Model::class);
        $this->baseConstraints = \Mockery::mock(\Closure::class);
        $this->eagerConstraints = \Mockery::mock(\Closure::class);
        $this->eagerMatcher = \Mockery::mock(\Closure::class);
        $this->customRelation = new CustomRelation($this->query, $this->model, $this->baseConstraints, $this->eagerConstraints, $this->eagerMatcher);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->customRelation, $this->query, $this->model, $this->baseConstraints, $this->eagerConstraints, $this->eagerMatcher);
    }

    public function testAddConstraints(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testAddEagerConstraints(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testInitRelation(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testMatch(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGetResults(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }

    public function testGet(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
