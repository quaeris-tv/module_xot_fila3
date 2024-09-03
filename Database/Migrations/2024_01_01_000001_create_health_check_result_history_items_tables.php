<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Models\HealthCheckResultHistoryItem;

<<<<<<< HEAD
return new class() extends XotBaseMigration {
=======
<<<<<<< HEAD
<<<<<<< HEAD
return new class extends XotBaseMigration {
=======
<<<<<<< HEAD
return new class () extends XotBaseMigration {
=======
return new class extends XotBaseMigration
{
>>>>>>> 9f602e2 (up)
>>>>>>> ea98aa92 (🔧 (gitignore): remove duplicate entries and resolve conflict markers in .gitignore file)
=======
return new class () extends XotBaseMigration {
>>>>>>> 6bebb798 (up)
>>>>>>> aebd4f2f (🔧 (ExportXlsStreamByLazyCollection.php): resolve conflict markers and remove duplicate entries in the file)
    protected ?string $model_class = HealthCheckResultHistoryItem::class;

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function up()
    {
        // -- CREATE --
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

                // $table->timestamps();
            }
        );

        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                $this->updateTimestamps(table: $table, hasSoftDeletes: false);
            }
        );
    }
};
