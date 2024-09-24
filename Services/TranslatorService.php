<?php

declare(strict_types=1);

namespace Modules\Xot\Services;

use Illuminate\Support\Arr;
// ---- services ---
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator as BaseTranslator;
use Modules\Xot\Actions\Array\SaveArrayAction;

// dddx('leggo');

/**
 * Class TranslatorService.
 */
class TranslatorService extends BaseTranslator
{
    public static function parse(array $params): array
    {
        $lang = app()->getLocale();
        extract($params);
        if (! isset($key)) {
            dddx(['err' => 'key is missing']);

            return [];
        }

        $translator = app('translator');
        $tmp = $translator->parseKey($key);
        $namespace = $tmp[0];
        $group = $tmp[1];
        $item = $tmp[2];
        $trans = trans();
        $path = collect($trans->getLoader()->namespaces())->flip()->search($namespace);
        $filename = $path.'/'.$lang.'/'.$group.'.php';
        $filename = str_replace(['/', '\\'], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR], $filename);

        $lang_dir = \dirname($filename, 2);

        return [
            'key' => str_replace(['[', ']'], ['.', ''], (string) $key),
            'namespace' => $namespace,
            'group' => $group,
            'ns_group' => $namespace.'::'.$group,
            'item' => $item,
            'filename' => $filename,
            'file_exists' => File::exists($filename),
            'lang_dir' => $lang_dir,
            'dir_exists' => File::exists($lang_dir), // dir without lang
        ];
    }

    public static function store(array $data): void
    {
        $data = collect($data)->map(
            static function ($v, $k) {
                $item = self::parse(['key' => $k]);
                $item['value'] = $v;

                return $item;
            }
        )
        // ->dd()
            ->filter(
                static fn (array $v, $k): bool => $v['dir_exists'] && \strlen((string) $v['lang_dir']) > 3
            )
            ->groupBy(['ns_group'])  // risparmio salvataggi
            ->all();
        // dddx($data);
        foreach ($data as $ns_group => $data0) {
            $rows = trans($ns_group);

            if (! \is_array($rows)) {
                // dddx($rows);  //---- dovrei leggere il file o controllarlo intanto lo blokko non voglio sovrascrivere
                $rows = [];
            }

            foreach ($data0 as $v) {
                $key = Str::after($v['key'], $ns_group.'.');
                Arr::set($rows, $key, $v['value']);
            }

            $data = $rows;
            if (! isset($v)) {
                dddx(['err' => 'v is missing']);

                return;
            }

            $filename = $v['filename'];
            // echo '<h3>['.$filename.']</h3>';
            app(SaveArrayAction::class)->execute(data: $data, filename: $filename);
        }
    }

    public static function set(string $key, string $value): void
    {
        $lang = app()->getLocale();
        if (trans($key) === $value) {
            return;
        } // non serve salvare

        $translator = app('translator');
        $tmp = $translator->parseKey($key);
        $namespace = $tmp[0];
        $group = $tmp[1];
        $item = $tmp[2];
        $trans = trans();
        $path = collect($trans->getLoader()->namespaces())->flip()->search($namespace);
        $filename = $path.\DIRECTORY_SEPARATOR.$lang.\DIRECTORY_SEPARATOR.$group.'.php';

        $trad = $namespace.'::'.$group;
        $rows = trans($trad);
        $item_keys = explode('.', (string) $item);
        $item_keys = implode('"]["', $item_keys);
        $item_keys = '["'.$item_keys.'"]';

        $str = '$rows'.$item_keys.'="'.$value.'";';
        try {
            eval($str); // fa schifo ma funziona
        } catch (\Exception) {
        }

        app(SaveArrayAction::class)->execute(data: $rows, filename: $filename);

        Session::flash('status', 'Modifica Eseguita! ['.$filename.']');

        /*

        dddx($rows)



        dddx($item_keys);

        dddx($filename);
        */
    }

    public static function getFilePath(string $key): string
    {
        $lang = app()->getLocale();
        $translator = app('translator');
        [$namespace,$group,$item] = $translator->parseKey($key);
        $trans = trans();
        $path = collect($trans->getLoader()->namespaces())->flip()->search($namespace);
        $file_path = $path.\DIRECTORY_SEPARATOR.$lang.\DIRECTORY_SEPARATOR.$group.'.php';

        return app(\Modules\Xot\Actions\File\FixPathAction::class)->execute($file_path);
    }

    /**
     * Undocumented function.
     */
    public static function add(string $key, array $data): void
    {
        $file_path = self::getFilePath($key);
        $original = [];
        if (File::exists($file_path)) {
            $original = File::getRequire($file_path);
            // $original = Lang::get($key, []);
        }

        if (! \is_array($original)) {
            dddx(
                [
                    'message' => 'original is not an array',
                    'file_path' => $file_path,
                    'original' => $original,
                    // 'ori1' => File::getRequire($file_path),
                    'key' => $key,
                    'data' => $data,
                ]
            );
            throw new \Exception('['.__LINE__.']['.class_basename(static::class).']');
        }

        $merged = collect($original)
            ->merge($data)
            ->all();

        if ($original !== $merged) {
            app(SaveArrayAction::class)->execute(data: $merged, filename: $file_path);
            Session::flash('status', 'Modifica Eseguita! ['.$file_path.']');
        }
    }

    /**
     * Undocumented function.
     */
    public static function addMissing(string $key, array $data): void
    {
        $missing = collect($data)
            ->filter(
                static function (string $item) use ($key): bool {
                    $k = $key.'.'.$item;
                    $v = trans($k);

                    return $k === $v;
                }
            )->all();
        $missing = array_combine($missing, $missing);
        self::add($key, $missing);
    }

    public static function getArrayTranslated(string $key, array $data): array
    {
        self::addMissing($key, $data);

        return collect($data)->map(
            static function (string $item) use ($key) {
                $k = $key.'.'.$item;

                return trans($k);
            }
        )->all();
    }

    /**
     * Get the translation for the given key.
     *
     * @param string      $key
     * @param string|null $locale
     * @param bool        $fallback
     *
     * @return string|array
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        // backtrace(true);
        // trans parte da xotbasepanel riga 1109 (per ora)
        // superdump([$key, $replace , $locale , $fallback ]);

        // *
        if (null === $locale) {
            $locale = app()->getLocale();
        }

        // */
        return parent::get($key, $replace, $locale, $fallback);
    }

    /**
     * getFromJson.
     */
    public function getFromJson(string $key, array $replace = [], ?string $locale = null): array|string
    {
        return $this->get($key, $replace, $locale);
    }
}
