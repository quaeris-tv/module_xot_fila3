<?php

declare(strict_types=1);

namespace Modules\Xot\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
// https://www.sitepoint.com/guzzle-php-http-client/
// /*
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseContract;
use Psr\Http\Message\UriInterface;
use Request;
use Storage;
use Symfony\Component\DomCrawler\Crawler;
use Webmozart\Assert\Assert;

use function Safe\fclose;
use function Safe\file_get_contents;
use function Safe\fopen;
use function Safe\ini_set;
use function Safe\json_decode;
use function Safe\json_encode;
use function Safe\parse_url;

use Storage;
use Symfony\Component\DomCrawler\Crawler;
use Webmozart\Assert\Assert;

// */

/**
 * Class ImportService.
 */
class ImportService
{
    private static ?self $instance = null;

    private ClientInterface $client;

    private ?CookieJarInterface $cookieJar = null;

    private ResponseContract $response;

    private array $client_options = [];

    public function __construct()
    {
        // ---
    }

    public static function getInstance(): self
    {
        if (! self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function make(): self
    {
        return static::getInstance();
    }

    public function setClientOptions(array $data = []): void
    {
        $this->client_options = array_merge($this->client_options, $data);
        // dddx($this->client_options);
    }

    public function initCookieJar(): CookieJarInterface
    {
        $cookieFile = base_path('../jar.txt');
        $this->cookieJar = new FileCookieJar($cookieFile, true);

        return $this->cookieJar;
    }

    public function importInit(): void
    {
        ini_set('max_execution_time', '3000');

        $route_current = Route::current();
        $params = [];
        if ($route_current instanceof \Illuminate\Routing\Route) {
            $params = $route_current->parameters();
        }

        // $cookieJar = new CookieJar();

        if (! $this->cookieJar instanceof CookieJarInterface) {
            $this->initCookieJar();
        }

        $headers = [];
        $fields = ['User-Agent', 'Accept', 'Accept-Language', 'Accept-Encoding', 'Connection', 'Cookie', 'Upgrade-Insecure-Requests', 'Cache-Control'];
        foreach ($fields as $field) {
            $headers[$field] = \Illuminate\Support\Facades\Request::header($field);
        }

        $this->enableRedirect();
        $this->client_options['headers'] = $headers;
        $this->client_options['headers']['Referer'] = 'http://www.google.com';
        $this->client_options['cookies'] = $this->cookieJar;
        $this->client = new GuzzleClient($this->client_options);
    }

    // end __construct

    // https://hotexamples.com/examples/guzzlehttp.cookie/CookieJar/-/php-cookiejar-class-examples.html
    // google trend example
    // https://hotexamples.com/examples/guzzlehttp.cookie/CookieJar/setCookie/php-cookiejar-setcookie-method-examples.html

    public function enableCharles(): void
    {
        $proxy = [
            // 'http'  => 'tcp://127.0.0.1:8888', // Use this proxy with "http"
            // 'https' => 'tcp://127.0.0.1:8888', // Use this proxy with "https",
            'http' => 'http://127.0.0.1:8888', // Use this proxy with "http"
            'https' => 'https://127.0.0.1:8888', // Use this proxy with "https",

            // 'no' => ['.mit.edu', 'foo.com']    // Don't use a proxy with these
        ];
        $this->setClientOptions(
            [
                'proxy' => $proxy,
                'verify' => false,
            ]
        );
        // senza verify false errore = #message: "cURL error 60: SSL certificate problem: self signed certificate in certificate chain (see http://curl.haxx.se/libcurl/c/libcurl-errors.html)"
    }

    public function enableCookie(array $cookies): void
    {
        // $cookieJar->setCookie(SetCookie::fromString('SID="AuthKey 23ec5d03-86db-4d80-a378-6059139a7ead"; expires=Thu, 24 Nov 2016 13:52:20 GMT; path=/; domain=.sketchup.com'));
        if (! $this->cookieJar instanceof CookieJarInterface) {
            $this->cookieJar = $this->initCookieJar();
        }

        Assert::isArray($url_info = parse_url((string) $this->client_options['base_uri']));

        // $domain = $url_info['host'];
        $domain = collect($url_info)->get('host');
        foreach ($cookies as $name => $value) {
            $cookieData = [
                'Domain' => $domain,
                'Name' => $name,
                'Value' => $value,
                'Discard' => true,
            ];
            $this->cookieJar->setCookie(new SetCookie($cookieData));
        }

        $this->client_options['cookies'] = $this->cookieJar;
    }

    public function enableRedirect(): void
    {
        $onRedirect = static function (RequestInterface $request, ResponseContract $response, UriInterface $uri): void {
            echo '<hr/>Redirecting! '.$request->getUri().' to '.$uri."\n";
        };
        $redirect_params = [
            'max' => 10,        // allow at most 10 redirects.
            'strict' => true,      // use "strict" RFC compliant redirects.
            'referer' => true,      // add a Referer header
            // 'protocols'       => ['https'], // only allow https URLs
            'on_redirect' => $onRedirect,
            'track_redirects' => true,
        ];
        $this->setClientOptions(['allow_redirects' => $redirect_params]);
        // $client->followRedirects(true);
    }

    public function disableRedirect(): void
    {
        $this->setClientOptions(['allow_redirects' => false]);
    }

    /**
     * ---.
     */
    public function getConfig(string $x): mixed
    {
        // $cookieJar = $client->getConfig('cookies');
        // $cookieJar->toArray();
        return $this->client->getConfig($x);
    }

    public function getEffectiveUrl(string $method, string $url, array $attrs = []): string
    {
        $attrs['allow_redirects'] = [
            'max' => 10,        // allow at most 10 redirects.
            'strict' => true,      // use "strict" RFC compliant redirects.
            'referer' => true,      // add a Referer header
            // 'protocols'       => ['https'], // only allow https URLs
            // 'on_redirect'     => $onRedirect,
            'track_redirects' => true,
        ];
        $res = $this->client->request($method, $url, array_merge($this->client_options, $attrs));

        return $res->getHeaderLine('X-Guzzle-Redirect-History');
    }

    public function jqueryRequest(string $method, string $url, array $attrs = []): Application|Factory|View
    {
        return view()->make('ui::jquery_request');
    }

    // ret \Exception|GuzzleException|string|Crawler

    public function gRequest(string $method, string $url, array $attrs = [], string $out = 'res'): ?string
    {
        // if (null === $this->client) {
        //    $this->importInit();
        // }

        if (! isset($this->client_options['base_uri'])) {
            Assert::isArray($url_info = parse_url($url));
            $this->client_options['base_uri'] = collect($url_info)->get('scheme').'://'.collect($url_info)->get('host');

            // $url = isset($url_info['path']) ? $url_info['path'] : '';
            $url = collect($url_info)->get('path');
            // if (isset($url_info['query'])) {
            //    $url .= '?'.$url_info['query'];
            // }
            $query = collect($url_info)->get('query');
            if ($query !== '') {
                $url .= '?'.$query;
            }
        }

        $url = (string) $url;

        $base_uri = $this->client_options['base_uri'];
        if (Str::startsWith($url, $base_uri)) {
            $url = substr($url, \strlen((string) $base_uri));
        }

        try {
            $res = $this->client->request($method, $url, array_merge($this->client_options, $attrs));
            $this->response = $res;

            $this->client_options['headers']['Referer'] = $this->client_options['base_uri'].$url;
            $html = (string) $res->getBody();
        } catch (GuzzleException) {
            $html = null;
        }

        return $html;
        // echo $res->getStatusCode(); // 200
        // echo $res->getHeaderLine('X-Guzzle-Redirect-History');// http://first-redirect, http://second-redirect, etc...
        // echo $res->getHeaderLine('X-Guzzle-Redirect-Status-History');// 301, 302, etc...
        /*
        $this->res = $res;

        $this->client_options['headers']['Referer'] = $this->client_options['base_uri'].$url;
        switch ($out) {
            case 'res': return $res;
            case 'html':
                $html = (string) $res->getBody();

                return $html;
            case 'crawler':
                $html = (string) $res->getBody();
                $crawler = new Crawler((string) $html, $this->client_options['base_uri']);

                return $crawler;
        }

        return $res;
        */
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getRedirectHistory(): string
    {
        return $this->response->getHeaderLine('X-Guzzle-Redirect-History'); // http://first-redirect, http://second-redirect, etc...
        // echo $res->getHeaderLine('X-Guzzle-Redirect-Status-History');// 301, 302, etc...
    }

    // ret \Exception|GuzzleException|string|Crawler

    /*
    public function submit($form, array $vars, $out): ?string {
        $vars = \array_merge($form->getValues(), $vars);

        return $this->gRequest($form->getMethod(), $form->getUri(), ['form_params' => $vars], $out);
    }
    */

    public function getCacheKey(string $method, string $url, array $attrs = []): string
    {
        $key = json_encode(['method' => $method, 'url' => $url, 'attrs' => $attrs], JSON_THROW_ON_ERROR);

        return $key.'_1';
    }

    public function cacheRequest(string $method, string $url, array $attrs = []): string
    {
        $key = $this->getCacheKey($method, $url, $attrs = []);
        $value = Cache::store('file')->rememberForever(
            $key,
            function () use ($method, $url, $attrs): string {
                $body = $this->gRequest($method, $url, $attrs);

                return (string) $body;
            }
        );
        $this->client_options['headers']['referer'] = $url;
        if (! \is_string($value)) {
            throw new \Exception('['.__LINE__.']['.class_basename(self::class).']');
        }

        return $value;
    }

    /**
     * @throws FileNotFoundException
     */
    public function cacheRequestFile(string $method, string $url, array $attrs = []): string
    {
        // --- uguale ma al posto di usare il sistema cache usa i file
        if (! isset($this->client_options['base_uri'])) {
            Assert::isArray($parse_url = parse_url($url));
            $url_info = collect($parse_url);
            if ($url_info->get('scheme') !== null && $url_info->get('host') !== null) {
                $this->client_options['base_uri'] = $url_info->get('scheme').'://'.$url_info->get('host');
            } else {
                $this->client_options['base_uri'] = '';
            }

            $url = $url_info->get('path');
            if ($url_info->get('query') !== null) {
                $url .= '?'.$url_info->get('query');
            }
        }

        $file_path = Str::slug((string) $this->client_options['base_uri'], '_').'/'.Str::slug((string) $url, '_').'.json';
        // $params=['method'=>$method,'url'=>$url,'attrs'=>$attrs];
        // $key=json_encode(array_values($params));
        // $key=str_slug
        if (\Illuminate\Support\Facades\Storage::disk('cache')->exists($file_path)) {
            $content = \Illuminate\Support\Facades\Storage::disk('cache')->get($file_path);
            $this->client_options['headers']['referer'] = $url;

            return (string) $content;
        }

        $body = $this->gRequest($method, (string) $url, $attrs);
        \Illuminate\Support\Facades\Storage::disk('cache')->put($file_path, (string) $body);
        $this->client_options['headers']['referer'] = $url;

        // echo '<br/>da sito ['.$url.']';
        return (string) $body;
    }

    /**
     * @throws \Exception
     */
    public function getAddressFields(array $params): array
    {
        extract($params);
        if (! isset($address)) {
            dddx(['err' => 'address is missing']);

            return [];
        }

        $linked = new \stdClass;
        $location_url = config('services.google.url_location_api').'?address='.urlencode((string) $address).'&key='.config('services.google.maps_key');
        $loc_json = $this->cacheRequest('GET', $location_url);

        // $loc_obj = (object) json_decode($loc_json, null, 512, JSON_THROW_ON_ERROR);
        $loc_obj = (object) json_decode($loc_json, false, 512, JSON_THROW_ON_ERROR);

        if (isset($loc_obj->results[0])) {
            $loc_obj = $loc_obj->results[0];
            // dddx($loc_obj->address_components);
            foreach ($loc_obj->address_components as $addr) {
                // echo '<br/>'.$addr->post_types[0].'  '.$addr->long_name.'  '.$addr->short_name;
                $sk = $addr->types[0];
                $linked->$sk = $addr->long_name;
                $sk .= '_short';
                $linked->$sk = $addr->short_name;
            }

            $linked->latitude = $loc_obj->geometry->location->lat;
            $linked->longitude = $loc_obj->geometry->location->lng;
        } else {
            $msg = [
                'id' => $id ?? '',
                'address' => $address,
                'obj' => $loc_obj,
            ];
            throw new \Exception('address not valide');
            // dddx($msg);
        }

        return get_object_vars($linked);
    }

    /*
    public function download($url, $name, $extensions){
       $path = __DIR__.'/download/' . $name . $extensions;
       $file_path = fopen($path,'w');
       $client = new \GuzzleHttp\Client();
       $response = $client->get($url, ['save_to' => $file_path]);
       return ['response_code'=>$response->getStatusCode(), 'name' => $name];
    }
    */
    /*
    $resource = fopen('/path/to/file', 'w');
    $stream = GuzzleHttp\Psr7\stream_for($resource);
    $client->request('GET', '/stream/20', ['save_to' => $stream]);
    */
    /*
    $resource = fopen('/path/to/file', 'w');
    $client->request('GET', '/stream/20', ['sink' => $resource]);
    */
    // new guzzle client setup
    /*
    $client = new GuzzleHttp\Client([ 'base_uri' => 'http://whatever' ]);
    //create a php temp file (returns a resource)
    $putStream = tmpfile();
    //guzzle get() and sink into resource
    $client->get('/stream/20', ['sink' => $putStream]);
    rewind($putStream);
    //store
    Storage::disk('local')->put('somewhere/here.txt', $putStream);
    //release tempfile
    fclose($putStream);
    */

    // https://phpnews.io/feeditem/chunked-transfer-encoding-in-php-with-guzzle

    public function download(array $params): void
    {
        // $url
        // $filename
        extract($params);
        if (! isset($filename)) {
            dddx(['err' => 'filename is missing']);

            return;
        }

        if (! isset($url)) {
            dddx(['err' => 'url is missing']);

            return;
        }

        $resource = fopen($filename, 'w');
        if ($resource === false) {
            throw new \Exception('can open '.$filename);
        }

        $stream = Utils::streamFor($resource);
        $this->gRequest(
            'get',
            $url,
            [
                'sink' => $stream,
                'progress' => static function (string $download_size, string $downloaded, string $upload_size, string $uploaded): void {
                    // $this->downloadProgress($download_size, $downloaded, $upload_size, $uploaded);
                    echo '<br>['.$download_size.']['.$downloaded.']['.$upload_size.']['.$uploaded.']';
                },
            ]
        );
        fclose($resource);
    }

    /*
    public function upload_to_test($params){
        // Open a stream so that we stream the image download
        $stream = fopen($url, 'r');

        // Create a curl handle to upload to the file server
        $ch = curl_init($fileServer);
        // Send a PUT request
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        // Let curl know that we are sending an entity body
        curl_setopt($ch, CURLOPT_UPLOAD, true);
        // Let curl know that we are using a chunked transfer encoding
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Transfer-Encoding: chunked'));
        // Use a callback to provide curl with data to transmit from the stream
        curl_setopt($ch, CURLOPT_READFUNCTION, function($ch, $fd, $length) use ($stream) {
            return fread($stream, $length) ? '';
        });

        curl_exec($ch);
    }//end upload
    */

    // end function

    public function pixabay(array $params): mixed
    {
        $lang = app()->getLocale();
        $image_type = 'photo';
        $q = 'necessary';
        extract($params);
        $pixabay_url = 'https://pixabay.com/api/?key=7945761-cdc8fef41b0600630fdabe778';
        $pixabay_url .= '&lang='.$lang;
        $pixabay_url .= '&image_type='.$image_type;
        $pixabay_url .= '&q='.$q;
        $pixabay_url = str_replace(' ', '%20', $pixabay_url);
        $json = $this->cacheRequest('GET', $pixabay_url);
        /**
         * @var object
         */
        // $json = json_decode($json, null, 512, JSON_THROW_ON_ERROR);
        $json = json_decode($json, false, 512, JSON_THROW_ON_ERROR);
        if (! isset($json->hits)) {
            return null;
        }

        /**
         * @var array
         */
        $hits = $json->hits;

        return collect($hits)
            ->shuffle()
            ->first();
    }

    // -------------------------------------------------------------------------

    public function trans(array $params): mixed
    {
        $i = random_int(0, 20);
        if ($i > 0 && $i < 10) {
            return $this->googleTrans($params);
        }

        return $this->mymemoryTrans($params);
    }

    // public function apertiumTrans(array $params): void
    // {
    // https://github.com/24aitor/Laralang/blob/master/src/Builder/ApertiumTrans.php
    // $host = 'api.apertium.org';
    // $urldata = file_get_contents("http://$host/json/translate?q=$urlString&langpair=$this->from|$this->to");
    // $data = json_decode($urldata, true);
    // }

    public function googleTrans(array $params): string
    {
        $q = 'necessary';
        $from = 'en';
        $to = 'it';
        extract($params);
        $q = urlencode($q);
        $urldata = file_get_contents(sprintf('https://translate.googleapis.com/translate_a/single?client=gtx&sl=%s&tl=%s&dt=t&q=%s', $from, $to, $q));
        $tr = $urldata;
        $tr = mb_substr($tr, 3, -6);
        $tr .= '["';
        $tr = explode('],[', $tr);
        $trans = [];
        foreach ($tr as $tran) {
            $transl = explode('","', $tran);
            $trans[] = str_replace('\"', '"', ucfirst(mb_substr($transl[0], 1)));
        }

        return trim(implode(' ', $trans));
    }

    public function mymemoryTrans(array $params): mixed
    {
        $host = 'api.mymemory.translated.net';
        $q = 'necessary';
        $from = 'en';
        $to = 'it';
        extract($params);
        $q = urlencode($q);
        $url = 'http://'.$host.'/get?q='.$q.'&langpair='.$from.'|'.$to.'';
        $urldata = file_get_contents($url);
        // if (false === $urldata) {
        //    throw new \Exception('can not get '.$urldata);
        // }
        $data = (array) json_decode($urldata, true, 512, JSON_THROW_ON_ERROR);
        // $data = Json::decode($urldata, Json::FORCE_ARRAY);
        // $data = (array) Json::decode($urldata, Json::FORCE_ARRAY);

        if ($data['responseStatus'] !== 200) {
            /* if (true == $this->debug) {
                 if (403 == $data['responseStatus']) {
                     $details = ($data['responseDetails']);
                 } else {
                     $details = $data['responseDetails'];
                 }
                 $translation = "<font style='color:red;'>Error ".$data->responseStatus.': '.$details.'</font>';
             }*/

            return;
        }

        $responseData = (array) $data['responseData'];

        return $responseData['translatedText'];
    }

    // end mymemoryTrans;

    public function getForms(array $params): array
    {
        $html = '';
        $node_tag = '';
        extract($params);
        $crawler = new Crawler($html);
        $forms = $crawler->filter($node_tag)->each(
            static fn (Crawler $node): array => [
                'action' => $node->attr('action'),
                'method' => $node->attr('method'),
                'fields' => $node->filter('input')->each(
                    static fn (Crawler $crawler): array => [$crawler->attr('name') => $crawler->attr('value')]
                ),
            ]
        );
        foreach ($forms as $k => $v) {
            Assert::isArray($v_fields = $v['fields']);
            $forms[$k]['fields'] = collect($v_fields)->collapse()->all();
        }

        return $forms;
    }

    // ret \Exception|GuzzleException|string|Crawler

    public function formRequest(array $params): ?string
    {
        $form = ['method' => '?', 'action' => '?', 'fields' => '?'];
        extract($params);

        return $this->gRequest($form['method'], $form['action'], ['form_params' => $form['fields']]);
    }
}// end class
