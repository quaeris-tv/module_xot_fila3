<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Feature\Console\Commands;

use Modules\Xot\Console\Commands\GenerateFormCommand;
use Tests\TestCase;

/**
 * Class GenerateFormCommandTest.
 *
 * @covers \Modules\Xot\Console\Commands\GenerateFormCommand
 */
final class GenerateFormCommandTest extends TestCase
{
    private GenerateFormCommand $generateFormCommand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generateFormCommand = new GenerateFormCommand();
        $this->app->instance(GenerateFormCommand::class, $this->generateFormCommand);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->generateFormCommand);
    }

    public function testHandle(): void
    {
        /* @todo This test is incomplete. */
        $this->artisan('xot:generate-form {module}')
            ->expectsOutput('Some expected output')
            ->assertExitCode(0);
    }
}
