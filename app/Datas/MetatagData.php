<?php

declare(strict_types=1);

namespace Modules\Xot\Datas;

use Filament\Support\Colors\Color;
use Illuminate\Support\Arr;
use Livewire\Wireable;
use Modules\Tenant\Services\TenantService;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;
use Modules\Xot\Actions\File\AssetAction;

/**
 * Class MetatagData
 */
class MetatagData extends Data implements Wireable
{
    use WireableData;

    public string $title = '';
    public string $sitename = '';
    public string $subtitle = '';
    public ?string $generator = 'xot';
    public string $charset = 'UTF-8';
    public ?string $author = 'xot';
    public ?string $description = null;
    public ?string $keywords = null;
    public string $nome_regione = '';
    public string $nome_comune = '';
    public string $site_title = '';
    public string $logo = '';
    public string $logo_square = '';
    public string $logo_header = '';
    public string $logo_header_dark = '';
    public string $logo_height = '2em';
    public string $logo_footer = '';
    public string $logo_alt = '';
    public string $hide_megamenu = '';
    public string $hero_type = '';
    public string $facebook_href = '';
    public string $twitter_href = '';
    public string $youtube_href = '';
    public string $fastlink = '';
    public string $color_primary = '';
    public string $color_title = '';
    public string $color_megamenu = '';
    public string $color_hamburger = '';
    public string $color_banner = '';
    public string $favicon = '/favicon.ico';
    public array $colors = [];

    /**
     * Singleton instance.
     */
    private static ?self $instance = null;

    /**
     * Creates or returns the singleton instance.
     */
    public static function make(): self
    {
        if (! self::$instance) {
            /** @var array<string, mixed> $data */
            $data = TenantService::getConfig('metatag');
            self::$instance = self::from($data);
        }

        return self::$instance;
    }

    public function getLogoHeader(): string
    {
        try {
            return asset(app(AssetAction::class)->execute($this->logo_header));
        } catch (\Exception $e) {
            return asset($this->logo_header);
        }
    }

    public function getLogoHeaderDark(): string
    {
        try {
            return asset(app(AssetAction::class)->execute($this->logo_header_dark));
        } catch (\Exception $e) {
            return asset($this->logo_header_dark);
        }
    }

    public function getLogoHeight(): string
    {
        return $this->logo_height;
    }

    public function getFavicon(): string
    {
        try {
            return app(AssetAction::class)->execute($this->favicon);
        } catch (\Exception $e) {
            return asset($this->favicon);
        }
    }

    /**
     * @return array<string, string>
     */
    public function getFilamentColors(): array
    {
        return [
            'danger' => 'danger',
            'gray' => 'gray',
            'info' => 'info',
            'primary' => 'primary',
            'success' => 'success',
            'warning' => 'warning',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getAllColors(): array
    {
        $colors = array_keys(Color::all());
        return array_combine($colors, $colors);
    }

    /**
     * @return array<string, string>
     */
    public function getColors(): array
    {
        if (empty($this->colors)) {
            return $this->getFilamentColors();
        }

        /** @var array<string, string> $mapped */
        $mapped = Arr::mapWithKeys(
            $this->colors,
            function (mixed $item, mixed $key): array {
                if (! is_array($item)) {
                    return [is_string($key) ? $key : (string) $key => ''];
                }

                $keyStr = is_string($item['key'] ?? null) 
                    ? $item['key'] 
                    : (is_string($key) ? $key : (string) $key);
                $colorValue = is_string($item['color'] ?? null) ? $item['color'] : '';

                $value = match (true) {
                    'custom' === $colorValue && is_string($item['hex'] ?? null) => Color::hex($item['hex']),
                    'custom' !== $colorValue => Arr::get(Color::all(), $colorValue, ''),
                    default => '',
                };

                return [$keyStr => $value];
            }
        );

        return $mapped;
    }
}
