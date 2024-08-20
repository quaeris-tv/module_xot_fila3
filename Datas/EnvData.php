<?php

declare(strict_types=1);

namespace Modules\Xot\Datas;

use Illuminate\Support\Facades\File;
use Livewire\Wireable;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

/**
 * Undocumented class.
 */
class EnvData extends Data implements Wireable
{
    use WireableData;

    private static ?self $instance = null;

    public string $app_url = 'http://localhost';
<<<<<<< HEAD

    public bool $debugbar_enabled = false;

    public string $google_maps_api_key = '';

=======
    public bool $debugbar_enabled = false;
    public string $google_maps_api_key = '';
>>>>>>> 35d9347 (.)
    public string $telegram_bot_token = '';

    public static function make(): self
    {
        if (! self::$instance) {
            $data = [];

            foreach ($_ENV as $k => $v) {
                $k = strtolower($k);
<<<<<<< HEAD
                if ($v == 'false') {
                    $v = false;
                }
                if ($v == 'true') {
=======
                if ('false' == $v) {
                    $v = false;
                }
                if ('true' == $v) {
>>>>>>> 35d9347 (.)
                    $v = true;
                }
                $data[$k] = $v;
            }

            self::$instance = self::from($data);
        } else {
        }

        return self::$instance;
    }

    public function update(array $data): void
    {
        $env_path = base_path('.env');

        $env_content = File::get($env_path);
        foreach ($data as $k => $v) {
<<<<<<< HEAD
            if ($v != $this->$k) {
=======
            if ($this->$k != $v) {
>>>>>>> 35d9347 (.)
                $env_content = $this->updateVar($k, $v, $env_content);
            }
        }

        File::put($env_path, $env_content);
    }

    public function updateVar(string $key, int|bool|string $value, string $env_content): string
    {
        $key = str($key)->upper()->toString();
        $replace = $this->getLine($key, $value);
        $pos_start = strpos($env_content, $key.'=');
<<<<<<< HEAD
        if ($pos_start === false) {
=======
        if (false === $pos_start) {
>>>>>>> 35d9347 (.)
            // throw new \Exception('['.__LINE__.']['.__FILE__.']');
            return $env_content."\n".$replace;
        }
        $pos_end = strpos($env_content, "\n", $pos_start);
<<<<<<< HEAD
        if ($pos_end === false) {
=======
        if (false === $pos_end) {
>>>>>>> 35d9347 (.)
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        $length = $pos_end - $pos_start;
        $find = substr($env_content, $pos_start, $length + 1);

        $env_content = str($env_content)->replace($find, $replace)->toString();

        return $env_content;
    }

    public function getLine(string $key, int|bool|string $value): string
    {
        $replace = $key.'=';
        if (is_bool($value)) {
            $replace .= $value ? 'true' : 'false';
        }
        if (is_string($value)) {
            $replace .= '"'.$value.'"';
        }
<<<<<<< HEAD
        if (is_int($value)) {
=======
        if (is_integer($value)) {
>>>>>>> 35d9347 (.)
            $replace .= $value;
        }
        $replace .= "\n";

        return $replace;
    }
}
