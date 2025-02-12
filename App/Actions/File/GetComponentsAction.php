<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\File;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Xot\Datas\ComponentFileData;
use Spatie\LaravelData\DataCollection;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

use function Safe\json_decode;

class GetComponentsAction
{
    use QueueableAction;

    /**
     * Undocumented function.
     *
     * @return DataCollection<ComponentFileData>
     */
    public function execute(string $path, string $namespace, string $prefix, bool $force_recreate = false): DataCollection
    {
        Assert::string($namespace = Str::replace('/', '\\', $namespace), '['.__LINE__.']['.class_basename(static::class).']');
        $components_json = $path.'/_components.json';
        $components_json = app(FixPathAction::class)->execute($components_json);

        $path = app(FixPathAction::class)->execute($path);

        if (! File::exists($path)) {
            if (Str::startsWith($path, base_path('Modules'))) {
                File::makeDirectory($path, 0755, true, true);
            }
        }

        $exists = File::exists($components_json);
        // $force_recreate = true;
        if ($exists && ! $force_recreate) {
            Assert::string($content = File::get($components_json), '['.__LINE__.']['.class_basename(static::class).']');

            // return (array) json_decode((string) $content, null, 512, JSON_THROW_ON_ERROR);
            // return (array) json_decode($content, false, 512, JSON_THROW_ON_ERROR);
            $comps = json_decode($content, false);
            if (! is_array($comps)) {
                $comps = [];
            }
            $res = ComponentFileData::collection($comps);

            return $res;
        }

        $files = File::allFiles($path);

        $comps = [];
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $tmp = (object) [];
            $class_name = $file->getFilenameWithoutExtension();

            $tmp->class_name = $class_name;
            Assert::string($comp_name = Str::replace('\\', ' ', $class_name), '['.__LINE__.']['.class_basename(static::class).']');
            $tmp->comp_name = Str::slug(Str::snake($comp_name));
            $tmp->comp_name = $prefix.$tmp->comp_name;

            $tmp->comp_ns = $namespace.'\\'.$class_name;
            $relative_path = $file->getRelativePath();
            Assert::string($relative_path = Str::replace('/', '\\', $relative_path), '['.__LINE__.']['.class_basename(static::class).']');

            if ($relative_path !== '') {
                $tmp->comp_name = '';
                $piece = collect(explode('\\', $relative_path))
                    ->map(
                        static fn ($item) => Str::slug(Str::snake($item))
                    )
                    ->implode('.');
                $tmp->comp_name .= $piece;
                Assert::string($comp_name = Str::replace('\\', ' ', $class_name), '['.__LINE__.']['.class_basename(static::class).']');

                $tmp->comp_name .= '.'.Str::slug(Str::snake($comp_name));
                $tmp->comp_name = $prefix.$tmp->comp_name;
                $tmp->comp_ns = $namespace.'\\'.$relative_path.'\\'.$class_name;
                $tmp->class_name = $relative_path.'\\'.$tmp->class_name;
            }
            try {
                /** @var class-string $compNs */
                $compNs = $tmp->comp_ns;
                $reflection = new \ReflectionClass($compNs);
                if ($reflection->isAbstract()) {
                    continue;
                }
            } catch (\Exception $e) {
                dddx([
                    'tmp' => $tmp,
                    'path' => $path,
                    'namespace' => $namespace,
                    'prefix' => $prefix,
                    'e' => $e->getMessage(),
                ]);
            }

            $tmp = ComponentFileData::from([
                'name' => $tmp->comp_name,
                'class' => $tmp->class_name,

                // 'path'=>$path.DIRECTORY_SEPARATOR.$relative_path,
                'ns' => $tmp->comp_ns,
            ])->toArray();

            $comps[] = $tmp;
        }

        $content = json_encode($comps, JSON_THROW_ON_ERROR);

        $old_content = '';
        if (File::exists($components_json)) {
            $old_content = File::get($components_json);
        }

        if ($old_content !== $content) {
            File::put($components_json, $content);
        }

        $res = ComponentFileData::collection($comps);

        return $res;
    }
}
