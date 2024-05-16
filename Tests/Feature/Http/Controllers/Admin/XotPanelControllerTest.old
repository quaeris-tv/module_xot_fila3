<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Feature\Http\Controllers\Admin;

use Modules\Cms\Http\Controllers\Admin\XotPanelController;
use Tests\TestCase;

/**
 * Class XotPanelControllerTest.
 *
 * @covers \Modules\Xot\Http\Controllers\Admin\XotPanelController
 */
final class XotPanelControllerTest extends TestCase
{
    private XotPanelController $xotPanelController;

    protected function setUp(): void
    {
        parent::setUp();

        /* @todo Correctly instantiate tested object to use it. */
        $this->xotPanelController = new XotPanelController();
        $this->app->instance(XotPanelController::class, $this->xotPanelController);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->xotPanelController);
    }

    public function testCall(): void
    {
        /* @todo This test is incomplete. */
        $this->get('/path')
            ->assertStatus(200);
    }
}
