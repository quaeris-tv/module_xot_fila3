<?php

declare(strict_types=1);

namespace Modules\Xot\Datas;

use Livewire\Wireable;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;
use Webmozart\Assert\Assert;
use Filament\Support\Colors\Color;
use Modules\Xot\Actions\File\AssetAction;
use Modules\Tenant\Services\TenantService;
use Spatie\LaravelData\Concerns\WireableData;

/**
 * Class MetatagData
 * 
 * @property string $title
 * @property string $sitename
 * @property string $subtitle
 * @property string|null $generator
 * @property string $charset
 * @property string|null $author
 * @property string|null $description
 * @property string|null $keywords
 * @property string $nome_regione
 * @property string $nome_comune
 * @property string $site_title
 * @property string $logo
 * @property string $logo_square
 * @property string $logo_header
 * @property string $logo_header_dark
 * @property string $logo_height
 * @property string $logo_footer
 * @property string $logo_alt
 * @property string $hide_megamenu
 * @property string $hero_type
 * @property string $facebook_href
 * @property string $twitter_href
 * @property string $youtube_href
 * @property string $fastlink
 * @property string $color_primary
 * @property string $color_title
 * @property string $color_megamenu
 * @property string $color_hamburger
 * @property string $color_banner
 * @property string $favicon
 * @property array<string, array{key?: string, color: string, hex?: string}> $colors
 */
class MetatagData extends Data implements Wireable
{
    use WireableData;

    /** @var string */
    public string $title = '';

    /** @var string */
    public string $sitename = '';

    /** @var string */
    public string $subtitle = '';

    /** @var string|null */
    public ?string $generator = 'xot';

    /** @var string */
    public string $charset = 'UTF-8';

    /** @var string|null */
    public ?string $author = 'xot';

    /** @var string|null */
    public ?string $description = null;

    /** @var string|null */
    public ?string $keywords = null;

    /** @var string */
    public string $nome_regione = '';

    /** @var string */
    public string $nome_comune = '';

    /** @var string */
    public string $site_title = '';

    /** @var string */
    public string $logo = '';

    /** @var string */
    public string $logo_square = '';

    /** @var string */
    public string $logo_header = '';

    /** @var string */
    public string $logo_header_dark = '';

    /** @var string */
    public string $logo_height = '2em';

    /** @var string */
    public string $logo_footer = '';

    /** @var string */
    public string $logo_alt = '';

    /** @var string */
    public string $hide_megamenu = '';

    /** @var string */
    public string $hero_type = '';

    /** @var string */
    public string $facebook_href = '';

    /** @var string */
    public string $twitter_href = '';

    /** @var string */
    public string $youtube_href = '';

    /** @var string */
    public string $fastlink = '';

    /** @var string */
    public string $color_primary = '';

    /** @var string */
    public string $color_title = '';

    /** @var string */
    public string $color_megamenu = '';

    /** @var string */
    public string $color_hamburger = '';

    /** @var string */
    public string $color_banner = '';

    /** @var string */
    public string $favicon = '/favicon.ico';
    
    /**
     * @var array<string, array{key?: string, color: string, hex?: string}>
     */
    public array $colors = [];

    /**
     * Singleton instance.
     */
    private static ?self $instance = null;

    /**
     * Creates or returns the singleton instance.
     *
     * @return self
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

    /**
     * Get the header logo URL.
     *
     * @return string
     */
    public function getLogoHeader(): string
    {
        try {
            /** @var string $path */
            $path = app(AssetAction::class)->execute($this->logo_header);
            return asset($path);
        } catch (\Throwable $e) {
            return asset($this->logo_header);
        }
    }

    /**
     * Get the dark header logo URL.
     *
     * @return string
     */
    public function getLogoHeaderDark(): string
    {
        try {
            /** @var string $path */
            $path = app(AssetAction::class)->execute($this->logo_header_dark);
            return asset($path);
        } catch (\Throwable $e) {
            return asset($this->logo_header_dark);
        }
    }

    /**
     * Get the logo height.
     *
     * @return string
     */
    public function getLogoHeight(): string
    {
        return $this->logo_height;
    }

    /**
     * Get the favicon URL.
     *
     * @return string
     */
    public function getFavicon(): string
    {
        try {
            /** @var string $path */
            $path = app(AssetAction::class)->execute($this->favicon);
            return $path;
        } catch (\Throwable $e) {
            return asset($this->favicon);
        }
    }

    /**
     * Get the default Filament colors configuration.
     *
     * @return array<string, array<int, string>>
     */
    public function getFilamentColors(): array
    {
        return [
            'danger' => Color::Red,
            'gray' => Color::Zinc,
            'info' => Color::Blue,
            'primary' => Color::Amber,
            'success' => Color::Green,
            'warning' => Color::Amber,
        ];
    }

    /**
     * Get the colors array with proper type handling.
     *
     * @return array<string, array<int, string>>
     */
    public function getColors(): array
    {
        if (empty($this->colors)) {
            return $this->getFilamentColors();    
        }

        /** @var array<string, array<int, string>> $mapped */
        $mapped = Arr::mapWithKeys(
            $this->colors,
            function (array $item, string|int $key): array {
                $keyStr = isset($item['key']) 
                    ? $item['key'] 
                    : (string) $key;

                /** @var array<int, string> $value */
                $value = match (true) {
                    $item['color'] === 'custom' && isset($item['hex']) 
                        => Color::hex($item['hex']),
                    isset(Color::all()[$item['color']]) 
                        => Color::all()[$item['color']],
                    default => Color::Gray,
                };

                return [$keyStr => $value];
            }
        );
        
        return $mapped;
    }
}
