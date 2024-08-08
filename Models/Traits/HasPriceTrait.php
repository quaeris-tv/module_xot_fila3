<?php

declare(strict_types=1);

namespace Modules\Xot\Models\Traits;

use Cknow\Money\Money;

// use Laravel\Scout\Searchable;
// ----- models------
// ---- services -----
// use Modules\Cms\Services\PanelService;
// ------ traits ---
/**
 * Modules\Food\Models\Traits\HasPriceTrait.
 *
 * @property string $currency
 * @property float  $price
 * @property string $price_complete
 * @property int    $qty
 */
trait HasPriceTrait
{
    public function getPriceCurrencyAttribute($value): Money
    {
        return @money($this->price, $this->currency);
    }

    public function getPriceCompleteCurrencyAttribute($value): Money
    {
        return @money($this->price_complete, $this->currency);
    }

    public function getSubtotalCurrencyAttribute($value): Money
    {
        $value = $this->qty > 0 ? $this->qty * $this->price : $this->price;

        return @money($value, $this->currency);
    }

    public function getCurrency(float $number): Money
    {
        return @money($number, $this->currency);
    }
}
