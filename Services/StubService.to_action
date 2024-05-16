<?php

declare(strict_types=1);

namespace Modules\Xot\Services;

use Doctrine\DBAL\Schema\Column;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\Xot\Contracts\ModelContract;
use Modules\Xot\Contracts\ModelProfileContract;
use Modules\Xot\Datas\XotData;

use function Safe\date;
use function Safe\shuffle;

use Symfony\Component\Finder\SplFileInfo;

/**
 * Class StubService.
 */
class StubService
{
    // public ?Model $model;
    public string $model_class;

    public string $name;

    public array $replaces = [];

    private array $custom_replaces = [];

    public bool $debug = false;

    // -- model (object) or class (string)
    // -- stub_name name of stub
    // -- create yes or not
    private static ?self $_instance = null;

    /**
     * getInstance.
     *
     * this method will return instance of the class
     */
    public static function getInstance(): self
    {
        if (! self::$_instance instanceof self) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function make(): self
    {
        return static::getInstance();
    }

    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Summary of setModel.
     */
    public function setModel(Model|ModelContract|ModelProfileContract $model): self
    {
        $this->model_class = $model::class;

        return $this;
    }

    public function setModelClass(string $model_class): self
    {
        $this->model_class = $model_class;

        return $this;
    }

    public function setCustomReplaces(array $custom_replaces): self
    {
        $this->custom_replaces = $custom_replaces;

        return $this;
    }

    /**
     * Summary of setModelAndName.
     */
    public function setModelAndName(Model|ModelContract|ModelProfileContract $model, string $name): self
    {
        $this->setModel($model);
        $this->setName($name);

        return $this;
    }

    public function get(): string
    {
        $classFile = $this->getClassFile();
        $class = $this->getClass();
        if (File::exists($classFile)) {
            // echo '<br/>['.$file.']['.$class.']';
            return $class;
        }

        try {
            $this->generate();
        } catch (\Exception $exception) {
            dddx(
                [
                    'e' => $exception,
                    'class' => $class,
                    'model_class' => $this->model_class,
                    // 'back' => debug_backtrace(),
                ]
            );
        }

        return $class;
    }

    public function getTable(): string
    {
        return $this->custom_replaces['DummyTable'] ?? $this->getModel()->getTable();
    }

    public function getModelClass(): string
    {
        return $this->model_class;
    }

    public function getNamespace(): string
    {
        $ns = $this->getClass();
        $ns = implode('\\', \array_slice(explode('\\', $ns), 0, -1));

        if (Str::startsWith($ns, '\\')) {
            return Str::after($ns, '\\');
        }

        return $ns;
    }

    public function getModelNamespace(): string
    {
        $ns = $this->model_class;
        $ns = implode('\\', \array_slice(explode('\\', $ns), 0, -1));

        if (Str::startsWith($ns, '\\')) {
            return Str::after($ns, '\\');
        }

        return $ns;
    }

    public function getModel(): Model
    {
        return app($this->model_class);
    }

    public function getReplaces(): array
    {
        $xotData = XotData::make();
        $dummy_id = 'id';
        $search = [];
        $fields = [];

        if (class_exists($this->model_class)) {
            $model = $this->getModel();
            $fields = $this->getFields();
            $dummy_id = $model->getRouteKeyName();
        } else {
            $fields = $this->getFieldsFromTable();

            $dummy_id = $this->getPrimaryKeyFromTable();

            // dddx($dummy_id );
        }

        // dddx([var_export($fields, true),$dummy_id]);

        // dddx($dummy_id);

        // $dummy_class = basename($this->getClass());
        $dummy_class = collect(explode('\\', $this->getClass()))->slice(-1)->implode('\\');
        $ns = $this->getNamespace();

        $dummy_timestamps = 'false';
        if (isset($fields['updated_at'])) {
            $dummy_timestamps = 'true';
        }

        // $user_class = get_class(Auth::user());
        $user_class = $xotData->getUserClass();

        $replaces = [
            'DummyNamespace' => $ns,
            'DummyClassLower' => strtolower($dummy_class),
            'DummyClassName' => Str::after($dummy_class, $ns.'\\'),
            'DummyClass' => $dummy_class,
            'DummyModelClass' => basename($this->model_class),
            'DummyFullModel' => $this->getClass(),
            'dummy_id' => $dummy_id,
            'dummy_title' => 'title', // prendo il primo campo stringa
            'dummy_search' => var_export($search, true),
            'dummy_fields' => var_export($fields, true),
            'dummy_factories' => $this->getFactories(),
            'NamespacedDummyUserModel' => $user_class,
            'NamespacedDummyModel' => $this->model_class,
            'dummy_timestamps' => $dummy_timestamps,
        ];

        // dddx($replaces);
        return array_merge($replaces, $this->custom_replaces);
    }

    public function getFactories(): string
    {
        if (! class_exists($this->model_class)) {
            return '';
        }

        return $this->getColumns()
            ->map(
                // function (Column $column) {
                function ($column): array {
                    if (! $column instanceof Column) {
                        throw new \Exception('['.__LINE__.']['.__FILE__.']');
                    }

                    return $this->mapTableProperties($column);
                    // return $this->getPropertiesFromMethods();
                }
            )->collapse()
            ->values()
            ->implode(
                ',
            '
            );
    }

    /**
     * Undocumented function.
     *
     * @return Collection<string>
     */
    public function getFillable(): Collection
    {
        $model = $this->getModel();
        if (! method_exists($model, 'getFillable')) {
            return collect([]);
        }

        $fillables = $model->getFillable();
        if ([] === $fillables) {
            $fillables = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        }

        return collect($fillables)
            ->except(
                [
                    'created_at', 'updated_at', 'updated_by', 'created_by', 'deleted_at', 'deleted_by',
                    'deleted_ip', 'created_ip', 'updated_ip',
                ]
            );
    }

    /**
     * Undocumented function.
     */
    public function getColumns(): Collection
    {
        $model = $this->getModel();
        $connection = $model->getConnection();
        $platform = $connection->getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        /*
        Schema::getTables(), Schema::getColumns(), Schema::getIndexes(), Schema::getForeignKeys(),
        */

        return $this->getFillable()->map(
            static function ($input_name) use ($connection, $model) {
                try {
                    $table_name = $connection->getTablePrefix().$model->getTable();
                    if (! \is_string($input_name)) {
                        throw new \Exception('['.__LINE__.']['.__FILE__.']');
                    }

                    return $connection->getDoctrineColumn($table_name, $input_name);
                } catch (\Exception $exception) {
                    $msg = 'message:['.$exception->getMessage().']
                        file:['.$exception->getFile().']
                        line:['.$exception->getLine().']
                        caller:['.__LINE__.']['.basename(__FILE__).']
                        ';

                    // throw new \Exception($msg);
                    return;
                    /*
                    dddx([
                        'message' => $e->getMessage(),
                        'name' => $this->name,
                        'modelClass' => $this->model_class,
                        'methods' => get_class_methods($e),
                        'e' => $e,
                        'msg' => $msg,
                    ]);
                    */
                    // return null;
                }
            }
        )->filter(
            static fn ($item): bool => $item instanceof Column
        );
    }

    /**
     * sarebbe create ma in maniera fluent.
     */
    public function generate(): self
    {
        $stub_file = __DIR__.'/../Console/stubs/'.$this->name.'.stub';
        $stub = File::get($stub_file);
        $replace = $this->getReplaces();
        /*
        dddx([
            'array_keys($replace),' => array_keys($replace),
            'replace' => $replace,
            'stub' => $stub,
            //'file' => $file,
        ]);
        //*/

        $stub = str_replace(
            array_keys($replace),
            array_values($replace),
            (string) $stub
        );

        $file = $this->getClassFile();
        if ($this->debug) {
            $file .= '.test';
        }

        try {
            File::put($file, $stub);
        } catch (\Exception $exception) {
            $msg = '['.$file.'] '.$exception->getMessage().'
                ['.__LINE__.']
                ['.class_basename(self::class).']
                ';
            throw new \Exception($msg, $exception->getCode(), $exception);
        }

        $msg = ' ['.$file.'] is under creating , refresh page';

        Session::flash($msg);

        return $this;
    }

    public function getClassName(): string
    {
        return class_basename($this->model_class);
    }

    public function getDirModel(): string
    {
        if (class_exists($this->model_class)) {
            $reflectionClass = new \ReflectionClass($this->model_class);
            // dddx($autoloader_reflector);
            $class_file_name = $reflectionClass->getFileName();
            if (false === $class_file_name) {
                throw new \Exception('autoloader_reflector false');
            }

            return \dirname($class_file_name);
        }

        $class = $this->model_class;

        if (Str::startsWith($class, '\\')) {
            $class = Str::after($class, '\\');
        }

        $tmp = collect(explode('\\', (string) $class))->slice(0, -1)->implode('\\');
        // $path = base_path($class);
        $path = base_path($tmp);

        // dddx([$class,$path,$tmp]);
        return FileService::fixPath($path);
    }

    public function getClass(): string
    {
        $dir = collect(explode('\\', $this->model_class))->slice(0, -1)->implode('\\');

        switch ($this->name) {
            case 'factory':
                // Assert::string();
                return Str::replaceFirst('\Models\\', '\Database\Factories\\', $this->model_class).'Factory';
            case 'migration_morph_pivot':
            case 'morph_pivot':
                return '';
            case 'repository':
                return Str::replaceFirst('\Models\\', '\Repositories\\', $this->model_class).'Repository';
            case 'transformer_collection':
                return Str::replaceFirst('\Models\\', '\Transformers\\', $this->model_class).'Collection';
            case 'transformer_resource':
                return Str::replaceFirst('\Models\\', '\Transformers\\', $this->model_class).'Resource';
            case 'policy':
                return $dir.'\\Policies\\'.class_basename($this->model_class).'Policy';
            case 'panel':
                return $dir.'\\Panels\\'.class_basename($this->model_class).'Panel';
            case 'model':
                return $this->model_class;
            default:
                $msg = '['.$this->name.'] Unkwon !['.__LINE__.']['.basename(__FILE__).']';
                // dddx($msg);
                throw new \Exception($msg);
        }
    }

    public function getClassFile(): string
    {
        $className = $this->getClassName();
        $dirModel = $this->getDirModel();
        /*
        dddx([
            'class_name' => $class_name, //Comment
            'dir' => $dir,              //F:\var\www\base_ptvx\laravel\Modules\Blog\Models
        ]);
        */
        switch ($this->name) {
            case 'factory':
                return $dirModel.'/../Database/Factories/'.$className.'Factory.php';

            case 'migration_morph_pivot':
                return $dirModel.'/../Database/Migrations/'.date('Y_m_d_Hi00').'_create_'.Str::snake($className).'_table.php';

            case 'morph_pivot':
                return $dirModel.'/'.$className.'Morph.php';

            case 'repository':
                return $dirModel.'/../Repositories/'.$className.'Repository.php';

            case 'transformer_collection':
                return $dirModel.'/../Transformers/'.$className.'Collection.php';

            case 'transformer_resource':
                return $dirModel.'/../Transformers/'.$className.'Resource.php';
            case 'policy':
                return $dirModel.'/Policies/'.$className.'Policy.php';
            case 'panel':
                return $dirModel.'/Panels/'.$className.'Panel.php';
            case 'model':
                return $dirModel.'/'.$className.'.php';
            default:
                $msg = '['.$this->name.'] Unkwon !['.__LINE__.']['.basename(__FILE__).']';
                throw new \Exception($msg);
        }
    }

    /**
     * @throws FileNotFoundException
     */
    public function getFields(): array
    {
        $model = $this->getModel();
        if (! method_exists($model, 'getFillable')) {
            return [];
        }

        $fillables = $model->getFillable();
        // dddx($fillables);

        if (\count($fillables) <= 1) {
            $fillables = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
            $fillables = collect($fillables)->except(
                [
                    'created_at', 'updated_at', 'updated_by', 'created_by', 'deleted_at', 'deleted_by',
                    'deleted_ip', 'created_ip', 'updated_ip',
                ]
            )->all();
            $reflectionClass = new \ReflectionClass($model);
            $class_filename = $reflectionClass->getFileName();
            if (false === $class_filename) {
                throw new \Exception('autoloader_reflector err');
            }

            $fillables_str = \chr(13).\chr(10).'    protected $fillable=[\''.implode("','", $fillables)."'];".\chr(13).\chr(10);
            $class_content = File::get($class_filename);
            $class_content_before = Str::before($class_content, '{');
            $class_content_after = Str::after($class_content, '{');
            $class_content_new = $class_content_before.'{'.$fillables_str.$class_content_after;
            File::put($class_filename, $class_content_new);
        }

        $fields = [];
        foreach ($fillables as $fillable) {
            $tmp = new \stdClass();
            try {
                $col = $model->getConnection()->getDoctrineColumn($model->getTable(), $fillable); // ->getType();//->getName();
                // dddx(get_class_methods($col->getType()));
                $type = $col->getType();
                /*
                dddx([
                    //$type->getSQLDeclaration(),
                    //$type->getType(),
                    $type->getTypesMap(),
                    $type->getName(),
                    $type->getTypeRegistry(),
                    $type->getBindingType(),
                    //$type->getMappedDatabaseTypes(),
                ]);
                */
                if ($col->getAutoincrement()) {
                    $tmp->type = 'Id';
                } else {
                    $tmp->type = Str::studly($col->getType()->getName());
                    $tmp->type = str_replace('\\', '', (string) $tmp->type);
                }

                $tmp->name = $fillable;
                if ($col->getNotnull() && ! $col->getAutoincrement()) {
                    $tmp->rules = 'required';
                }

                $tmp->comment = $col->getComment();
            } catch (\Exception) {
                // $input_type='Text';
                // $tmp=new \stdClass();
                $tmp->type = 'Text';
                $tmp->name = $fillable;
                $tmp->comment = 'not in Doctrine';
            }

            $fields[] = $tmp;
            // debug_getter_obj(['obj'=>$col]);
            /*
            #_type: IntegerType {#983}
            #_length: null
            #_precision: 10
            #_scale: 0
            #_unsigned: true
            #_fixed: false
            #_notnull: true
            #_default: null
            #_autoincrement: true
            #_platformOptions: []
            #_columnDefinition: null
            #_comment: null
            #_customSchemaOptions: []
            #_name: "id"
            #_namespace: null
            #_quoted: false
             */

            /*
            $tmp=new \stdClass();
            $tmp->type=(string)$input_type;
            $tmp->name=$input_name;
            $fields[]=$tmp;
            */
        }

        return $fields;
    }

    public function getModelPath(): string
    {
        $path = base_path($this->getModelNamespace());

        return FileService::fixPath($path);
    }

    public function getPrimaryKeyFromTable(): string
    {
        $models = File::files($this->getModelPath());
        shuffle($models);
        /*
        $brother_file = collect($models)
        ->filter(function (SplFileInfo $file) {
            return 'php' === $file->getExtension();
        })
        ->first();
        */
        /**
         * @var SplFileInfo
         */
        $brother_file = Arr::first(
            $models,
            static fn (SplFileInfo $file): bool => 'php' === $file->getExtension()
        );
        if (! $brother_file instanceof SplFileInfo) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        $brother_class = $this->getModelNamespace().'\\'.$brother_file->getFilenameWithoutExtension();
        $brother = app($brother_class);
        $indexes = $brother->getConnection()->getDoctrineSchemaManager()->listTableIndexes($this->getTable());

        // dddx($brother->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable()));
        $primaryKey = 'id';

        if (isset($indexes['primary'])) {
            $columns = $indexes['primary']->getColumns();
            if (isset($columns[0])) {
                $primaryKey = $columns[0];
            }
        }

        return $primaryKey;
    }

    public function getFieldsFromTable(): array
    {
        // dddx([$this->getModelClass(), $this->getModelPath()]);

        $models = File::files($this->getModelPath());
        shuffle($models);
        /*
         $models_coll=collect($models);
         $brother_file = $models_coll
             ->filter(function (SplFileInfo $file) {
                 return 'php' === $file->getExtension();
             })
             ->first();
         */
        /**
         * @var SplFileInfo
         */
        $brother_file = Arr::first(
            $models,
            static fn (SplFileInfo $file): bool => 'php' === $file->getExtension()
        );
        // dddx(get_class_methods($brother_file));
        // dddx($brother_file->getFilenameWithoutExtension());
        if (! $brother_file instanceof SplFileInfo) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        $brother_class = $this->getModelNamespace().'\\'.$brother_file->getFilenameWithoutExtension();
        // getRandomBrotherModel
        // dddx($brother_class);
        $brother = app($brother_class);
        $fillables = $brother->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        $except = [
            'created_at', 'created_by', 'created_ip',
            'updated_at', 'updated_by', 'updated_ip',
            'deleted_at', 'deleted_by', 'deleted_ip',
        ];

        /*
                return collect($fillables)
                    ->except($except)
                    ->all();
                */

        return array_diff_key($fillables, array_combine($except, $except));
    }

    /**
     * Maps properties.
     */
    private function mapTableProperties(Column $column): array
    {
        $name = $column->getName();
        /*
        if (! $this->shouldBeIncluded($column)) {
            return $this->mapToFactory($key);
        }
        */
        /*
        if ($column->isForeignKey()) {
            return $this->mapToFactory(
                $key,
                $this->buildRelationFunction($key)
            );
        }
        */

        if ('password' === $name) {
            return $this->mapToFactory($name, "Hash::make('password')");
        }

        /*
        $value = $column->isUnique()
            ? '$this->faker->unique()->'
            : '$this->faker->';
        */
        $value = '$this->faker->';

        return $this->mapToFactory($name, $value.$this->mapToFaker($column));
    }

    /**
     * Undocumented function.
     */
    private function mapToFactory(string $key, ?string $value = null): array
    {
        return [
            $key => null === $value ? $value : sprintf("'%s' => %s", $key, $value),
        ];
    }

    /**
     * Map name to faker method.
     */
    private function mapToFaker(Column $column): string
    {
        return app(TypeGuesser::class)->guess(
            $column->getName(),
            $column->getType(),
            $column->getLength()
        );
    }
}
