<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Spatie\Health\Models\HealthCheckResultHistoryItem;
use Spatie\Health\ResultStores\EloquentHealthResultStore;

return new class() extends XotBaseMigration {
    /**
     * Undocumented function.
     *
     * @return void
     */
    public function up()
    {
        // $connection = (new HealthCheckResultHistoryItem())->getConnectionName();
        // $tableName = EloquentHealthResultStore::getHistoryItemInstance()->getTable();

        // Schema::connection($connection)->create($tableName, function (Blueprint $table) {
        $this->tableCreate(
            function (Blueprint $table): void {
                $table->id();

                $table->string('check_name');
                $table->string('check_label');
                $table->string('status');
                $table->text('notification_message')->nullable();
                $table->string('short_summary')->nullable();
                $table->json('meta');
                $table->timestamp('ended_at');
                $table->uuid('batch')->index();

                $table->timestamps();
            }
        );

        // Schema::connection($connection)->table($tableName, function (Blueprint $table) {
        //    $table->index('created_at');
        //    $table->index('batch');
        // });
    }
};
