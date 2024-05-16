<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Feature\Console\Commands;

use Modules\Xot\Console\Commands\DatabaseBackUpCommand;
use Tests\TestCase;

/**
 * Class DatabaseBackUpCommandTest.
 *
 * @covers \Modules\Xot\Console\Commands\DatabaseBackUpCommand
 */
final class DatabaseBackUpCommandTest extends TestCase
{
    private DatabaseBackUpCommand $databaseBackUpCommand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->databaseBackUpCommand = new DatabaseBackUpCommand();
        $this->app->instance(DatabaseBackUpCommand::class, $this->databaseBackUpCommand);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->databaseBackUpCommand);
    }

    public function testHandle(): void
    {
        /* @todo This test is incomplete. */
        $this->artisan('database:backup')
            ->expectsOutput('Some expected output')
            ->assertExitCode(0);
    }
}
