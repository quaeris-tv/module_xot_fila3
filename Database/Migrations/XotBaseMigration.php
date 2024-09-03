<?php

declare(strict_types=1);

namespace Modules\Xot\Database\Migrations;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Xot\Datas\XotData;
use Nwidart\Modules\Facades\Module;

/**
 * Class XotBaseMigration.
 */
abstract class XotBaseMigration extends Migration
{
    protected Model $model;

    protected ?string $model_class = null;

    public function __construct()
    {
        $trace = debug_backtrace();

        if (null == $this->model_class) {
            $this->model_class = $this->getModel();
        }
        $this->model = app($this->model_class);
    }

    public function getModel(): string
    {
        if (null !== $this->model_class) {
            return $this->model_class;
        }

        $name = class_basename($this);

        $name = Str::before(Str::after($name, 'Create'), 'Table');
        $name = Str::singular($name);
        if (Str::contains($name, '.php')) {
            $name = Str::of($name)
                ->between('_create_', '_tables.php')
                ->before('_table.php')
                ->singular()
                ->studly()
                ->toString();
        }

        $reflectionClass = new \ReflectionClass($this);
        $filename = (string) $reflectionClass->getFilename();
        $mod_path = Module::getPath();

        $mod_name = Str::after($filename, $mod_path);
        $mod_name = explode(\DIRECTORY_SEPARATOR, $mod_name)[1];

        $model_ns = '\Modules\\'.$mod_name.'\Models\\'.$name;
        $model_dir = $mod_path.'/'.$mod_name.'/Models/'.$name.'.php';
        Str::replace('/', \DIRECTORY_SEPARATOR, $model_dir);

        return $model_ns;
    }

    public function getTable(): string
    {
        return $this->model->getTable();
    }

    public function getConn(): Builder
    {
        $connectionName = $this->model->getConnectionName();

        return Schema::connection($connectionName);
    }
    /*
    public function getSchemaManager(): AbstractSchemaManager
    {

        //Schema::getTables(), Schema::getColumns(), Schema::getIndexes(), Schema::getForeignKeys()

        return $this->getConn()
            ->getConnection()
            ->getDoctrineSchemaManager();
    }

    /*
     * @throws \Doctrine\DBAL\Exception

    public function getTableDetails(): Table
    {
        return $this->getSchemaManager()
            ->listTableDetails($this->getTable());
    }
    */

    /*
     * @throws \Doctrine\DBAL\Exception
     *
     * @return array<Index>

    public function getTableIndexes(): array
    {
        return $this->getSchemaManager()
            ->listTableIndexes($this->getTable());
    }
    */

    /**
     * ---.
     */
    public function tableExists(string $table = null): bool
    {
        if (null === $table) {
            $table = $this->getTable();
        }

        return $this->getConn()->hasTable($table);
    }

    public function hasColumn(string $col): bool
    {
        return $this->getConn()->hasColumn($this->getTable(), $col);
    }

    /**
     * Get the data type for the given column name.
     */
    public function getColumnType(string $column): string
    {
        return $this->getConn()->getColumnType($this->getTable(), $column);
    }

    /**
     * Undocumented function.
     */
    public function isColumnType(string $column, string $type): bool
    {
        if (! $this->hasColumn($column)) {
            return false;
        }

        return $this->getColumnType($column) === $type;
    }

    /**
     * ---.
     */
    public function query(string $sql): void
    {
        $this->getConn()->getConnection()->statement($sql);
    }

    public function hasIndex(string $column): bool
    {
        return $this->getConn()->hasIndex($this->getTable(), $column);
    }

    /*
     * ---.

    public function hasPrimaryKey(): bool
    {
        $table_details = $this->getTableDetails();

        return $table_details->hasPrimaryKey();
    }

    public function dropPrimaryKey(): void
    {
        $table_details = $this->getTableDetails();
        $table_details->dropPrimaryKey();

        $sql = 'ALTER TABLE '.$this->getTable().' DROP PRIMARY KEY;';
        $this->query($sql);
    }
    */

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->getConn()->dropIfExists($this->getTable());
    }

    public function tableDrop(string $table): void
    {
        $this->getConn()->dropIfExists($table);
    }

    public function rename(string $from, string $to): void
    {
        $this->getConn()->rename($from, $to);
    }

    public function renameTable(string $from, string $to): void
    {
        if ($this->tableExists($from)) {
            $this->getConn()->rename($from, $to);
        }
    }

    // da rivedere
    public function renameColumn(string $from, string $to): void
    {
        // Call to an undefined method Illuminate\Database\Schema\Builder::renameColumn().
        /**
         * @var Blueprint
         */
        $query = $this->getConn();
        $query->renameColumn($from, $to);
    }

    /**
     * Undocumented function.
     */
    public function tableCreate(\Closure $next): void
    {
        if (! $this->tableExists()) {
            $this->getConn()->create(
                $this->getTable(),
                $next
            );
        }
    }

    /**
     * Undocumented function.
     */
    public function tableUpdate(\Closure $next): void
    {
        $this->getConn()->table(
            $this->getTable(),
            $next
        );
    }

    public function timestamps(Blueprint $table, bool $hasSoftDeletes = false): void
    {
        $xot = XotData::make();
        $userClass = $xot->getUserClass();
        $table->timestamps();
        $table->foreignIdFor(
            model: $userClass,
            column: 'user_id',
        )
            ->nullable();
        // ->nullOnDelete()
        // ->cascadeOnUpdate()

        $table->foreignIdFor(
            model: $userClass,
            column: 'updated_by',
        )
            ->nullable();
        // ->nullOnDelete()
        // ->cascadeOnUpdate()
        $table->foreignIdFor(
            model: $userClass,
            column: 'created_by',
        )
            ->nullable();
        // ->nullOnDelete()
        // ->cascadeOnUpdate()

        if ($hasSoftDeletes) {
            $table->softDeletes();
        }
    }

    public function updateTimestamps(Blueprint $table, bool $hasSoftDeletes = false): void
    {
        $xot = XotData::make();
        $userClass = $xot->getUserClass();
        if (! $this->hasColumn('updated_at') && ! $this->hasColumn('created_at')) {
            $table->timestamps();
        }

        /*
        if (! $this->hasColumn('user_id')) {
            $table->foreignIdFor(
                model: \Modules\Xot\Datas\XotData::make()->getUserClass(),
                column: 'user_id',
            )
            ->nullable()
            ->nullOnDelete()
            ->cascadeOnUpdate();
        }
        */
        if (! $this->hasColumn('updated_by')) {
            $table->foreignIdFor(
                model: $userClass,
                column: 'updated_by',
            )
                ->nullable();
            // ->nullOnDelete()
            // ->cascadeOnUpdate()
        }
        if (! $this->hasColumn('created_by')) {
            $table->foreignIdFor(
                model: $userClass,
                column: 'created_by',
            )
                ->nullable();
            // ->nullOnDelete()
            // ->cascadeOnUpdate()
        }

        if ($hasSoftDeletes && ! $this->hasColumn('deleted_at')) {
            $table->softDeletes();
            if (! $this->hasColumn('deleted_by')) {
                $table->foreignIdFor(
                    model: $userClass,
                    column: 'deleted_by',
                )
                    ->nullable();
                // ->nullOnDelete()
                // ->cascadeOnUpdate()
            }
        }

        if ($this->hasColumn('deleted_at') && ! $this->hasColumn('deleted_by')) {
            $table->foreignIdFor(
                model: $userClass,
                column: 'deleted_by',
            )
                ->nullable();
            // ->nullOnDelete()
            // ->cascadeOnUpdate()
        }
    }

    public function updateUser(Blueprint $table): void
    {
        $func = 'updateUserKey'.Str::studly($this->model->getKeyType());
        $this->{$func}($table);

        if ($this->hasColumn('model_id') && 'bigint' === $this->getColumnType('model_id')) {
            $table->string('model_id', 36)->index()->change();
        }

        if ($this->hasColumn('team_id') && 'bigint' === $this->getColumnType('team_id')) {
            $table->uuid('team_id')->nullable()->change(); //  ->index()
        }
    }

    public function updateUserKeyString(Blueprint $table): void
    {
        if (! $this->hasColumn('id')) {
            $table->uuid('id')->primary()->first(); // ->default(DB::raw('(UUID())'));
        }

        if ($this->hasColumn('id') && 'bigint' === $this->getColumnType('id')) {
            // $table->uuid('id')->default(DB::raw('(UUID())'))->change();
            $table->uuid('id')->change();
        }

        if ($this->hasColumn('user_id') && 'bigint' === $this->getColumnType('user_id')) {
            $table->uuid('user_id')->change(); //  ->index()
        }
    }

    public function updateUserKeyInt(Blueprint $table): void
    {
        $this->updateUserKeyInteger($table);
    }

    public function updateUserKeyInteger(Blueprint $table): void
    {
        if (! $this->hasColumn('id')) {
            $table->id('id')->first();
        }

        if ($this->hasColumn('id') && \in_array($this->getColumnType('id'), ['string', 'guid'], true)) {
            // if ($this->hasIndexName('PRIMARY')) {
            //    $table->dropPrimary();
            // }

            $table->renameColumn('id', 'uuid');
            // $table->id('id')->first();
        }
    }
}// end XotBaseMigration
