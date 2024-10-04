<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Services\ModuleService;
use Nwidart\Modules\Facades\Module;

use function Safe\define;
use function Safe\glob;
use function Safe\json_decode;
use function Safe\parse_url;
use function Safe\preg_match;
use function Safe\realpath;

use Webmozart\Assert\Assert;

// ------------------------------------------------

/* --- MAH
if (! function_exists('get_current_theme_name')) {
    function current_theme_name(): string {
        $theme_name = config('xra.pub_theme');

        if (inAdmin()) {
            $theme_name = config('xra.adm_theme');
        }

        return '_'.Str::lower($theme_name);
    }
}
*/
if (! function_exists('isRunningTestBench')) {
    function isRunningTestBench(): bool
    {
        /*
        try {
            $testbench = realpath(__DIR__.'/../vendor/orchestra');
        } catch (Exception $e) {
            return false;
        }
        $res = Str::startsWith(base_path(), $testbench);
        if (false == $res) {
            dd([
                'msg' => 'preso',
                'res' => $res,
                'base_path' => base_path(),
                'testbench' => $testbench,
            ]);
        }

        return $res;
        */
        $path = app(Modules\Xot\Actions\File\FixPathAction::class)->execute('\vendor\orchestra\testbench-core\laravel');
        $base = app(Modules\Xot\Actions\File\FixPathAction::class)->execute(base_path());
        $res = Str::endsWith($base, $path);

        return $res;
    }
}

if (! function_exists('snake_case')) {
    function snake_case(string $str): string
    {
        return Str::snake($str);
    }
}

if (! function_exists('str_slug')) {
    function str_slug(string $str): string
    {
        return Str::slug($str);
    }
}

if (! function_exists('str_singular')) {
    function str_singular(string $str): string
    {
        return Str::singular($str);
    }
}

if (! function_exists('starts_with')) {
    function starts_with(string $str, string $str1): bool
    {
        return Str::startsWith($str, $str1);
    }
}

if (! function_exists('ends_with')) {
    function ends_with(string $str, string $str1): bool
    {
        return Str::endsWith($str, $str1);
    }
}

if (! function_exists('str_contains')) {
    function str_contains(string $str, string $str1): bool
    {
        return Str::contains($str, $str1);
    }
}

if (! function_exists('hex2rgba')) {
    /* Convert hexdec color string to rgb(a) string */

    function hex2rgba(string $color, float $opacity = -1.0): string
    {
        $default = 'rgb(0,0,0)';

        // Return default if no color provided
        if (empty($color)) {
            return $default;
        }

        // Sanitize $color if "#" is provided
        if ('#' === $color[0]) {
            $color = mb_substr($color, 1);
        }

        // Check if color has 6 or 3 characters and get values
        if (6 === mb_strlen($color)) {
            $hex = [$color[0].$color[1], $color[2].$color[3], $color[4].$color[5]];
        } elseif (3 === mb_strlen($color)) {
            $hex = [$color[0].$color[0], $color[1].$color[1], $color[2].$color[2]];
        } else {
            return $default;
        }

        // Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        // Check if opacity is set(rgba or rgb)
        if (-1.0 !== $opacity) {
            if ($opacity < 0 || $opacity > 1) {
                $opacity = 1.0;
            }
            $output = 'rgba('.implode(',', $rgb).','.$opacity.')';
        } else {
            $output = 'rgb('.implode(',', $rgb).')';
        }

        // Return rgb(a) color string
        return $output;
    }
}

if (! function_exists('dddx')) {
    function dddx(mixed $params): string
    {
        $tmp = debug_backtrace();
        $file = $tmp[0]['file'] ?? 'file-unknown';
        $file = str_replace('/', DIRECTORY_SEPARATOR, $file);

        Assert::string($doc_root = $_SERVER['DOCUMENT_ROOT']);
        $doc_root = str_replace('/', DIRECTORY_SEPARATOR, (string) $doc_root);

        $dir_piece = explode(DIRECTORY_SEPARATOR, __DIR__);
        $dir_piece = array_slice($dir_piece, 0, -6);

        $dir_copy = implode(DIRECTORY_SEPARATOR, $dir_piece);
        $file = str_replace($dir_copy, $doc_root, $file);

        if (! defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }

        $start = LARAVEL_START;
        $data = [
            '_' => $params,
            'line' => $tmp[0]['line'] ?? 'line-unknows',
            'file' => app(Modules\Xot\Actions\File\FixPathAction::class)->execute($tmp[0]['file'] ?? 'file-unknown'),
            'time' => microtime(true) - $start,
            'memory_taken' => round(memory_get_peak_usage() / (1024 * 1024), 2).' MB',

            // 'file_1' => $file, //da sistemare
        ];
        if (File::exists($data['file']) && Str::startsWith($data['file'], app(Modules\Xot\Actions\File\FixPathAction::class)->execute(storage_path('framework/views')))) {
            // $data['extra'] = 'preso';
            $content = File::get($data['file']);
            $data['view_file'] = app(Modules\Xot\Actions\File\FixPathAction::class)->execute(Str::between($content, '/**PATH ', ' ENDPATH**/'));
        }

        dd(
            $data,
        );
    }
}
/*
if (! function_exists('debug_methods')) {
    function debug_methods(object $rows): string
    {
        $methods = get_class_methods($rows);
        // *
        $methods_get = collect($methods)->filter(
            static fn ($item) => Str::startsWith($item, 'get')
        )->map(
            static function ($item) use ($rows) {
                $value = 'Undefined';
                try {
                    $value = $rows->{$item}();
                } catch (Exception|ArgumentCountError $e) {
                    $value = $e->getMessage();
                }

                return [
                    'name' => $item,
                    'value' => $value,
                ];
            }
        )->all();

        return ArrayService::make()
            ->setArray($methods_get)
            ->toHtml()
            ->render();
    }
}
*/

if (! function_exists('getFilename')) {
    function getFilename(array $params): string
    {
        $tmp = debug_backtrace();
        $class = class_basename($tmp[1]['class'] ?? 'class-unknown');

        $func = $tmp[1]['function'];
        $params_list = collect($params)->except(['_token', '_method'])->implode('_');

        return Str::slug(
            str_replace('Controller', '', $class).
                '_'.str_replace('do_', '', $func).
                '_'.$params_list
        );
    }
}

if (! function_exists('req_uri')) {
    function req_uri(): mixed
    {
        return $_SERVER['REQUEST_URI'] ?? '';
    }
}

if (! function_exists('in_admin')) {
    /**
     * ---.
     */
    function in_admin(array $params = []): bool
    {
        return inAdmin($params);
    }
}

if (! function_exists('inAdmin')) {
    /**
     * ---.
     */
    function inAdmin(array $params = []): bool
    {
        if (isset($params['in_admin'])) {
            return (bool) $params['in_admin'];
        }

        // dddx(ThemeService::__getStatic('in_admin'));
        /* Cannot call method get() on mixed
        if (null !== config()->get('in_admin')) {
            return config()->get('in_admin');
        }
        */
        if ('admin' === Request::segment(2)) {
            return true;
        }

        $segments = Request::segments();

        return (is_countable($segments) ? count($segments) : 0) > 0 && 'livewire' === $segments[0] && true === session('in_admin');
    }
}

/*
     * Return true if current page is home.
     *
     * @return bool
     */
if (! function_exists('isHome')) {
    function isHome(): bool
    {
        if (URL::current() === url('')) {
            return true;
        }

        return Route::is('home');
    }
}

/*
     * Return true if current page is an admin home page.
     *
     * @return bool
     */
if (! function_exists('isAdminHome')) {
    function isAdminHome(): bool
    {
        return URL::current() === route('admin.index');
    }
}

/*
     * https://gist.github.com/atorscho/5bcf63d077c11ed0e8ce
     * Return true if current page is an admin page.
     *
     * @return bool
     */
if (! function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        return Route::is('*admin*');
    }
}

/*
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
if (! function_exists('fullTextWildcards')) {
    /* protected */
    function fullTextWildcards(string $term): string
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (mb_strlen($word) >= 3) {
                $words[$key] = '+'.$word.'*';
            }
        }

        return implode(' ', $words);
    }
}

if (! function_exists('isContainer')) {
    function isContainer(): bool
    {
        [$containers, $items] = params2ContainerItem();

        return count($containers) > count($items);
    }
}

if (! function_exists('isItem')) {
    function isItem(): bool
    {
        [$containers, $items] = params2ContainerItem();

        return count($containers) === count($items);
    }
}

if (! function_exists('params2ContainerItem')) {
    /**
     * @return array<array>
     */
    function params2ContainerItem(?array $params = null): array
    {
        if (null === $params) {
            // Call to static method current() on an unknown class Route.
            // $params = optional(\Route::current())->parameters();
            // Cannot call method parameters() on mixed.
            // $params = optional(Route::current())->parameters();
            $params = [];
            $route_current = Route::current();
            if ($route_current instanceof Illuminate\Routing\Route) {
                $params = $route_current->parameters();
            }
        }

        $container = [];
        $item = [];
        foreach ($params as $k => $v) {
            $pattern = '/(container|item)(\d+)/';
            preg_match($pattern, $k, $matches);

            if (is_array($matches) && isset($matches[1]) && isset($matches[2])) {
                $sk = $matches[1];
                $sv = $matches[2];
                ${$sk}[$sv] = $v;
            }
        }

        return [$container, $item];
    }
}

if (! function_exists('getModelFields')) {
    function getModelFields(Model $model): array
    {
        return $model->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($model->getTable());
    }
}

if (! function_exists('getModelByName')) {
    function getModelByName(string $name): Model
    {
        $registered = config('morph_map.'.$name);
        if (is_string($registered) && class_exists($registered)) {
            return app($registered);
        }

        // getFirst..
        $files_path = base_path('Modules').'/*/Models/*.php';
        Assert::isArray($files = glob($files_path));
        // if (false === $files) {
        //    throw new Exception('['.__LINE__.']['.__FILE__.']');
        // }

        $path = collect($files)->first(
            static function ($file) use ($name): bool {
                $info = pathinfo((string) $file);
                // Offset 'filename' on array{dirname?: string, basename: string, extension?: string, filename: string} on left side of ?? always exists and is not nullable.
                $filename = $info['filename'];

                // ?? '';
                return Str::snake($filename) === $name;
            }
        );

        // dddx($registered);

        if (null === $path) {
            throw new Exception('['.$name.'] not in morph_map ['.__LINE__.']['.__FILE__.']');
        }

        $path = app(Modules\Xot\Actions\File\FixPathAction::class)->execute($path);
        $info = pathinfo($path);
        $module_name = Str::between($path, 'Modules'.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR.'Models');

        $class = 'Modules\\'.$module_name.'\Models\\'.$info['filename'];

        return app($class);
    }
}

/* moved into xotData
if (! function_exists('getUserClass')) {
    function getUserClass(): string
    {
        $class = config('auth.providers.users.model');
        if (! is_string($class)) {
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }

        return $class;
    }
}
*/

if (! function_exists('getModuleFromModel')) {
    function getModuleFromModel(object $model): Nwidart\Modules\Module
    {
        $class = $model::class;
        $module_name = Str::before(Str::after($class, 'Modules\\'), '\\Models\\');

        // call to an undefined static method  Nwidart\Modules\Facades\Module::find().
        // $mod = Module::find($module_name);
        // Call to an undefined static method Nwidart\Modules\Facades\Module::get().
        // $mod = Module::get($module_name);
        // Static call to instance method Nwidart\Modules\Module::get()
        // $mod = \Nwidart\Modules\Module::get($module_name);
        // 480    Call to an undefined method Nwidart\Modules\Facades\Module::get()
        // $mod = app('module')->get($module_name);
        return app('module')->find($module_name);
    }
}

if (! function_exists('getModuleNameFromModel')) {
    function getModuleNameFromModel(object $model): string
    {
        $class = $model::class;

        return Str::before(Str::after($class, 'Modules\\'), '\\Models\\');
    }
}

if (! function_exists('getModuleNameFromModelName')) {
    function getModuleNameFromModelName(string $model_name): string
    {
        $model_class = config('morph_map.'.$model_name);
        if (! is_string($model_class)) {
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }

        $model = app($model_class);

        return getModuleNameFromModel($model);
    }
}

if (! function_exists('getTransformerFromModel')) {
    function getTransformerFromModel(object $model, string $type = 'Resource'): mixed
    {
        $class = $model::class;
        $module_name = getModuleNameFromModel($model);
        $transformer = '\\Modules\\'.$module_name.'\Transformers\\'.class_basename($model).''.$type;
        // dddx($transformer.' '.class_exists($transformer));
        if (! class_exists($transformer)) {
            dddx('preso');
        }

        return $transformer;
    }
}

if (! function_exists('getAllModules')) {
    function getAllModules(): array
    {
        return Module::all();
    }
}

if (! function_exists('getAllModulesModels')) {
    /**
     * @throws ReflectionException
     */
    function getAllModulesModels(): array
    {
        $res = [];
        $modules = Module::all();
        foreach ($modules as $module) {
            $tmp = getModuleModels($module->getName());
            $res = array_merge($res, $tmp);
        }

        return $res;
    }
}

if (! function_exists('getModuleModels')) {
    /**
     * @throws ReflectionException
     */
    function getModuleModels(string $module): array
    {
        return ModuleService::make()->setName($module)->getModels();
    }
}

/* --- XOT NON DEVE DIPENDERE DA NESSUNO PANEL E' in CMS questa sara' un helper di cms
if (! function_exists('getModuleModelsMenu')) {
    function getModuleModelsMenu(string $module): Collection {
        $models = getModuleModels($module);
        $menu = collect($models)->map(
            function ($item, $key) {
                // $obj = new $item();
                $obj = app($item);
                $panel = PanelService::make()->get($obj);
                if ('media' === $key) {// media e' singolare ma anche plurale di medium
                    $panel->setName('medias');
                }
                $url = $panel->url('index');
                return (object) [
                    'title' => $key,
                    'url' => $url,
                    'active' => false,
                ];
            }
        );
        return $menu;
    }
}
*/

if (! function_exists('xotModel')) {
    function xotModel(string $name): Model
    {
        $model_class = config('morph_map.'.$name);
        if (! is_string($model_class)) {
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }

        return app($model_class);
    }
}

if (! function_exists('deltaTime')) {
    function deltaTime(): void
    {
        echo '<h3>Time : '.(microtime(true) - LARAVEL_START).'</h3>';
    }
}

if (! function_exists('bracketsToDotted')) {
    // privacies[111][pivot][title] => privacies.111.pivot.title

    function bracketsToDotted(string $str, string $quotation_marks = ''): string
    {
        return str_replace(['[', ']'], ['.', ''], $str);
    }
}

if (! function_exists('dottedToBrackets')) {
    // privacies.111.pivot.title => privacies[111][pivot][title]
    function dottedToBrackets(string $str, string $quotation_marks = ''): string
    {
        return collect(explode('.', $str))->map(
            static fn (string $v, $k): string => 0 === $k ? $v : '['.$v.']'
        )->implode('');
    }
}

if (! function_exists('array_merge_recursive_distinct')) {
    function array_merge_recursive_distinct(array &$array1, array &$array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}

/*
|--------------------------------------------------------------------------
| Laravel 5 - URL Query String Helper
|--------------------------------------------------------------------------
|
| A helper function to take a URL string then quickly and easily add query
| string parameters to it, or change existing ones.
|
| url_queries(['order' => 'desc', 'page' => 2],
|             'https://app.dev/users/?sort=username&order=asc');
| // https://app.dev/users/?sort=username&order=desc&page=2
|
https://gist.github.com/ImLiam/49c420ddb2db881afd59d77635d039f8
*/
if (! function_exists('url_queries')) {
    /**
     * Modifies the query strings in a given (or the current) URL.
     *
     * @param array       $queries Indexed array of query parameters
     * @param string|null $url     URL to use parse. If none is supplied, the current URL of the page load will be used
     *
     * @return string The updated query string
     */
    function url_queries(array $queries, ?string $url = null): string
    {
        // If a URL isn't supplied, use the current one
        if (! $url) {
            $url = Request::fullUrl();
        }

        // Split the URL down into an array with all the parts separated out
        $url_parsed = parse_url($url);

        if (false === $url_parsed) {
            throw new Exception('error parsing url ['.$url.']');
        }

        // Turn the query string into an array
        $url_params = [];
        // Cannot access offset 'query' on array(?'scheme' => string, ?'host' => string, ?'port' => int, ?'user' => string, ?'pass' => string, ?'path' => string, ?'query' => string, ?'fragment' => string)|false.
        if (isset($url_parsed['query'])) {
            // if (in_array('query', array_keys($url_parsed))) {
            parse_str((string) $url_parsed['query'], $url_params);
        }

        // Merge the existing URL's query parameters with our new ones
        $url_params = array_merge($url_params, $queries);

        // Build a new query string from our updated array
        $string_query = http_build_query($url_params);
        // Add the new query string back into our URL
        Assert::isArray($url_parsed, 'wip');
        $url_parsed['query'] = $string_query;

        // Build the array back into a complete URL string
        return build_url($url_parsed);
    }
}

if (! function_exists('build_url')) {
    /**
     * Rebuilds the URL parameters into a string from the native parse_url() function.
     *
     * @param array $parts The parts of a URL
     *
     * @return string The constructed URL
     */
    function build_url(array $parts): string
    {
        return (isset($parts['scheme']) ? sprintf('%s:', $parts['scheme']) : '').
            (isset($parts['user']) || isset($parts['host']) ? '//' : '').
            ($parts['user'] ?? '').
            (isset($parts['pass']) ? sprintf(':%s', $parts['pass']) : '').
            (isset($parts['user']) ? '@' : '').
            ($parts['host'] ?? '').
            (isset($parts['port']) ? sprintf(':%s', $parts['port']) : '').
            ($parts['path'] ?? '').
            (isset($parts['query']) ? sprintf('?%s', $parts['query']) : '').
            (isset($parts['fragment']) ? sprintf('#%s', $parts['fragment']) : '');
    }
}

if (! function_exists('getRelationships')) {
    /**
     * @throws ReflectionException
     */
    function getRelationships(Model $model): array
    {
        $methods = get_class_methods($model);
        $data = [];
        // if (! is_array($methods)) {
        //     return $data;
        // }

        foreach ($methods as $method) {
            $reflection = new ReflectionMethod($model, $method);
            $args = $reflection->getParameters();
            if ([] !== $args) {
                continue;
            }

            if ($reflection->class !== $model::class) {
                continue;
            }

            try {
                $return = $reflection->invoke($model);
                $check = $return instanceof Relation;
                if ($check) {
                    $related_model = (new ReflectionClass($return->getRelated()))->getName();
                    $msg = [
                        'name' => $reflection->name,
                        'type' => class_basename($return),
                        // 'check'=>$check,
                        // $msg['type']=(new \ReflectionClass($return))->getShortName();
                        'model' => $related_model,
                    ];
                    $data[] = $msg;
                }
            } catch (ErrorException) {
            }
        }

        return $data;
    }
}

/*
    public function getRelationshipsV2($model){
        $relationships = [];
        foreach((new \ReflectionClass($model))->getMethods(\ReflectionMethod::IS_PUBLIC) as $method){
            if ($method->class != get_class($model) ||
                !empty($method->getParameters()) ||
                $method->getName() == __FUNCTION__) {
                continue;
            }
            try {
                $return = $method->invoke($model);
                if ($return instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    $relationships[$method->getName()] = [
                        'name' => $method->getName(),
                        'type' => (new \ReflectionClass($return))->getShortName(),
                        'model' => (new \ReflectionClass($return->getRelated()))->getName()
                    ];
                }
            } catch(ErrorException $e) {}
        }
        return $relationships;
    }
    */

/*
 * https://chasingcode.dev/blog/laravel-global-url-helpers/
 * URL before:
 * https://example.com/orders/123?order=ABC009&status=shipped
 *
 * 1. removeQueryParams(['status'])
 * 2. removeQueryParams(['status', 'order'])
 *
 * URL after:
 * 1. https://example.com/orders/123?order=ABC009
 * 2. https://example.com/orders/123
 */

if (! function_exists('removeQueryParams')) {
    function removeQueryParams(array $params = []): string
    {
        $url = url()->current(); // get the base URL - everything to the left of the "?"
        $query = request()->query(); // get the query parameters (what follows the "?")

        foreach ($params as $param) {
            unset($query[$param]); // loop through the array of parameters we wish to remove and unset the parameter from the query array
        }

        // 924    Parameter #1 $querydata of function http_build_query expects array|object, array|string given.
        return $query ? $url.'?'.http_build_query($query) : $url; // rebuild the URL with the remaining parameters, don't append the "?" if there aren't any query parameters left
    }
}

/*
 * https://chasingcode.dev/blog/laravel-global-url-helpers/
 * URL before:
 * https://example.com/orders/123?order=ABC009
 *
 * 1. addQueryParams(['status' => 'shipped'])
 * 2. addQueryParams(['status' => 'shipped', 'coupon' => 'CCC2019'])
 *
 * URL after:
 * 1. https://example.com/orders/123?order=ABC009&status=shipped
 * 2. https://example.com/orders/123?order=ABC009&status=shipped&coupon=CCC2019
 */
if (! function_exists('addQueryParams')) {
    function addQueryParams(array $params = []): string
    {
        $query = array_merge(
            (array) request()->query(),
            $params
        ); // merge the existing query parameters with the ones we want to add

        return url()->current().'?'.http_build_query($query); // rebuild the URL with the new parameters array
    }
}

if (! function_exists('isJson')) {
    /*
    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    */
    /*
    function isJson($string) {
    return ((is_string($string) &&
            (is_object(json_decode($string)) ||
            is_array(json_decode($string))))) ? true : false;
    }
    */
    function isJson(string $string): bool
    {
        // return is_string($string) && is_array(json_decode($string, true, 512, JSON_THROW_ON_ERROR));
        return is_array(json_decode($string, true, 512, JSON_THROW_ON_ERROR));
    }
}

/*
if (! function_exists('getExcerpt')) {
    function getExcerpt(string $str, int $length = 225): string
    {
        $cleaned = strip_tags(
            (string) preg_replace(['/<pre>[\w\W]*?<\/pre>/', '/<h\d>[\w\W]*?<\/h\d>/'], '', $str),
            '<code>'
        );
        $truncated = substr($cleaned, 0, $length);

        if (substr_count($truncated, '<code>') > substr_count($truncated, '</code>')) {
            $truncated .= '</code>';
        }

        return strlen($cleaned) > $length
            ? preg_replace('/\s+?(\S+)?$/', '', $truncated).'...'
            : $cleaned;
    }
}
*/
if (! function_exists('getRouteParameters')) {
    function getRouteParameters(): array
    {
        $route = request()->route();
        if (! $route instanceof Illuminate\Routing\Route) {
            return [];
        }

        return $route->parameters();
    }
}

if (! function_exists('getRouteName')) {
    function getRouteName(): ?string
    {
        // getRouteName();
        /**
         * @var Illuminate\Routing\Route|null
         */
        $route = request()->route();
        if (! $route instanceof Illuminate\Routing\Route) {
            return null;
        }

        return $route->getName();
    }
}

/*
if (! function_exists('getRouteAction')) {
    function getRouteAction(): string {
    }
}
*/

if (! function_exists('getModTradFilepath')) {
    function getModTradFilepath(string $file_path): string
    {
        Assert::string($file_path = Str::replace('\\', '/', $file_path), '['.__LINE__.']['.__FILE__.']');

        $stringable = Str::of($file_path)->after('/Modules/')->before('/')->lower();
        $info = pathinfo($file_path);
        $group = Str::snake($info['filename']);

        return $stringable.'::'.$group;
    }
}

/*
    function is_iterable($var)
{
    return $var !== null
        && (is_array($var)
            || $var instanceof Traversable
            || $var instanceof Iterator
            || $var instanceof IteratorAggregate
            );
}
*/

if (! function_exists('is_active')) {
    /**
     * Determines if the given routes are active.
     */
    function is_active(array $routes): bool
    {
        return (bool) call_user_func_array([app('router'), 'is'], $routes);
    }
}

if (! function_exists('md_to_html')) {
    /**
     * Convert Markdown to HTML.
     */
    function md_to_html(?string $markdown): ?string
    {
        return $markdown;
        // return app(App\Markdown\Converter::class)->toHtml($markdown);
    }
}

if (! function_exists('replace_links')) {
    /**
     * Convert Standalone Urls to HTML.
     */
    function replace_links(string $markdown): string
    {
        /*
        return (new LinkFinder([
            'attrs' => ['target' => '_blank', 'rel' => 'nofollow'],
        ]))->processHtml($markdown);
        */
        return $markdown;
    }
}

if (! function_exists('debugStack')) {
    /**
     * Undocumented function.
     */
    function debugStack(): void
    {
        if (! extension_loaded('xdebug')) {
            throw new RuntimeException('XDebug must be installed to use this function');
        }

        xdebug_set_filter(
            XDEBUG_FILTER_TRACING,
            XDEBUG_PATH_EXCLUDE,
            // [LARAVEL_DIR.'/vendor/']
            [__DIR__.'/../../vendor/']
        );

        xdebug_print_function_stack();
    }
}

if (! function_exists('secondsToHms')) {
    function secondsToHms(float $seconds, int $decimal = 0): string
    {
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
        $str = '';
        if ($hours > 0) {
            $str .= ($hours < 9 ? '0'.$hours : $hours).':';
        }

        return $str.(($minutes < 9 ? '0'.$minutes : $minutes).':'.round($seconds, $decimal));
    }
}

if (! function_exists('rowsToSql')) {
    /**
     * Undocumented function.
     */
    function rowsToSql(HasOne|Builder|Illuminate\Database\Eloquent\Builder $rows): string
    {
        // $sql = str_replace('?', $rows->getBindings(), $rows->toSql());
        /**
         * @var array<int|string, string>
         */
        $bindings = $rows->getBindings();
        $sql = $rows->toSql();

        return Str::replaceArray('?', $bindings, $sql);
    }
}

if (! function_exists('getServerName')) {
    function getServerName(): string
    {
        $default = env('APP_URL');
        if (! is_string($default)) {
            $default = 'localhost';
        }

        $default = Str::after($default, '//');

        $server_name = $default;
        if (isset($_SERVER['SERVER_NAME']) && '127.0.0.1' !== $_SERVER['SERVER_NAME']) {
            $server_name = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
        }
        if (! is_string($server_name)) {
            $server_name = $default;
        }
        $server_name = Str::of($server_name)->replace('www.', '')->toString();

        return $server_name;
    }
}

/*
if (! function_exists('getLang')) {
    function getLang(): string {
        return '/en/'; //per xstream
    }
}
*/

if (! function_exists('str_limit')) {
    function str_limit(string $str, int $limit): string
    {
        return Str::limit($str, $limit); // per xstream
    }
}

if (! function_exists('inArrayBetween')) {
    function inArrayBetween(int $curr, array $data, ?string $field_start = 'start', ?string $field_end = 'end'): bool
    {
        foreach ($data as $v) {
            if ($curr < $v[$field_start]) {
                continue;
            }

            if ($curr > $v[$field_end]) {
                continue;
            }

            return true;
        }

        return false;
    }
}

if (! function_exists('inArrayBetweenKey')) {
    function inArrayBetweenKey(int $curr, array $data, ?string $field_start = 'start', ?string $field_end = 'end'): int|bool
    {
        foreach ($data as $k => $v) {
            if ($curr < $v[$field_start]) {
                continue;
            }

            if ($curr > $v[$field_end]) {
                continue;
            }

            return $k;
        }

        return false;
    }
}

if (! function_exists('is_base64')) {
    function is_base64(string $s): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
    }
}

if (! function_exists('recursive_unset')) {
    function recursive_unset(array &$array, string $unwanted_key): void
    {
        unset($array[$unwanted_key]);
        foreach ($array as &$value) {
            if (is_array($value)) {
                recursive_unset($value, $unwanted_key);
            }
        }
    }
}

if (! function_exists('profile')) {
    function profile(): Model|ProfileContract
    {
        $xotData = XotData::make();

        return $xotData->getProfileModel();
    }
}

if (! function_exists('cssInLine')) {
    function cssInLine(string $file): string
    {
        return File::get(app(Modules\Xot\Actions\File\AssetPathAction::class)->execute($file));
    }
}

if (! function_exists('authId')) {
    function authId(): ?string
    {
        try {
            $id = Filament::auth()->id() ?? auth()->id();
        } catch (Exception $e) {
            return null;
        } catch (Error $e) {
            return null;
        }
        if (null === $id) {
            return null;
        }

        return (string) $id;
    }
}
