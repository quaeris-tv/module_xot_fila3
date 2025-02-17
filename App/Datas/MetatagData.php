<?php

declare(strict_types=1);

namespace Modules\Xot\Datas;

use Filament\Support\Colors\Color;
use Illuminate\Support\Arr;
use Livewire\Wireable;
use Modules\Tenant\Services\TenantService;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

/**
 * Undocumented class.
 */
class MetatagData extends Data implements Wireable
{
    use WireableData;

    public string $title;

    public string $sitename;

    public string $subtitle;

    public ?string $generator = 'xot';

    public string $charset = 'UTF-8';

    public ?string $author = 'xot';

    public ?string $description;

    public ?string $keywords;

    public string $nome_regione;

    public string $nome_comune;

    public string $site_title;

    public string $logo;

    public string $logo_square;

    public string $logo_header;

    public string $logo_header_dark;

    public string $logo_height = '2em';

    public string $logo_footer;

    public string $logo_alt;

    public string $hide_megamenu;

    public string $hero_type;

    public string $facebook_href;

    public string $twitter_href;

    public string $youtube_href;

    public string $fastlink;

    public string $color_primary;

    public string $color_title;

    public string $color_megamenu;

    public string $color_hamburger;

    public string $color_banner;

    public string $favicon = '/favicon.ico';

    public array $colors = [];

    private static ?self $instance = null;

    public static function make(): self
    {
        if (! self::$instance) {
            $data = TenantService::getConfig('metatag');
            self::$instance = self::from($data);
        }

        return self::$instance;
    }

    public function getLogoHeader(): string
    {
        return asset(app(\Modules\Xot\Actions\File\AssetAction::class)->execute($this->logo_header));
    }

    public function getLogoHeaderDark(): string
    {
        return asset(app(\Modules\Xot\Actions\File\AssetAction::class)->execute($this->logo_header_dark));
    }

    public function getLogoHeight(): string
    {
        return $this->logo_height;
    }

    public function getFavicon(): string
    {
        return app(\Modules\Xot\Actions\File\AssetAction::class)->execute($this->favicon);
    }

    /**
     * @return array<array<string>|string>
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
     * @return array<array<string>|string>
     */
    public function getAllColors(): array
    {
        $colors = array_keys(Color::all());
        $colors = array_combine($colors, $colors);

        return $colors;
    }

    /**
     * @return array<string, array<string>|string>
     */
    public function getColors(): array
    {
        /** @var array<string, array<string>|string> $mapped */
        $mapped = Arr::mapWithKeys(
            $this->colors,
            function (mixed $item, mixed $key): array {
                if (! is_array($item)) {
                    return [(string) $key => ''];
                }

                $keyStr = is_string($item['key'] ?? null) ? $item['key'] : (string) $key;
                $colorValue = is_string($item['color'] ?? null) ? $item['color'] : '';

                $value = match (true) {
                    $colorValue === 'custom' && is_string($item['hex'] ?? null) => Color::hex($item['hex']),
                    $colorValue !== 'custom' => Arr::get(Color::all(), $colorValue, ''),
                    default => '',
                };

                return [$keyStr => $value];
            }
        );

        return $mapped;
    }
}
