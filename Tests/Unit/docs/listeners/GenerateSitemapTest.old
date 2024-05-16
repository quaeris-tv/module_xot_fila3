<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit\docs\listeners;

use App\Listeners\GenerateSitemap;
use Tests\TestCase;
use TightenCo\Jigsaw\Jigsaw;

/**
 * Class GenerateSitemapTest.
 *
 * @covers \App\Listeners\GenerateSitemap
 */
class GenerateSitemapTest extends TestCase
{
    private GenerateSitemap $generateSitemap;

    protected function setUp(): void
    {
        parent::setUp();

        /* @todo Correctly instantiate tested object to use it. */
        $this->generateSitemap = new GenerateSitemap();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->generateSitemap);
    }

    public function testHandle(): void
    {
        $jigsaw = \Mockery::mock(Jigsaw::class);

        /* @todo This test is incomplete. */
        $this->generateSitemap->handle($jigsaw);
    }

    public function testIsExcluded(): void
    {
        /* @todo This test is incomplete. */
        self::markTestIncomplete();
    }
}
