<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Feature\Http\Controllers;

use Modules\Xot\Http\Controllers\XotBaseController;
use Tests\TestCase;

/**
 * Class XotBaseControllerTest.
 *
 * @covers \Modules\Xot\Http\Controllers\XotBaseController
 */
final class XotBaseControllerTest extends TestCase
{
    private XotBaseController $xotBaseController;

    protected function setUp(): void
    {
        parent::setUp();

        /* @todo Correctly instantiate tested object to use it. */
        $this->xotBaseController = new XotBaseController();
        $this->app->instance(XotBaseController::class, $this->xotBaseController);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->xotBaseController);
    }

    public function testSendResponse(): void
    {
        /* @todo This test is incomplete. */
        $this->get('/path')
            ->assertStatus(200);
    }

    public function testSendError(): void
    {
        /* @todo This test is incomplete. */
        $this->get('/path')
            ->assertStatus(200);
    }
}
