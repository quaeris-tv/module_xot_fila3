<?php

declare(strict_types=1);

namespace Modules\Xot\Contracts;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Xot\Contracts\PivotContract.
 *
<<<<<<< HEAD
 * @property string|null                         $title
 * @property string|null                         $subtitle
 * @property string|null                         $price
 * @property string|null                         $price_currency
 * @property int|null                            $status
 * @property Collection|array<ProductContract>   $products
 * @property Collection|array<ChangeCatContract> $changeCats
=======
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $price
 * @property string|null $price_currency
 * @property int|null    $status
>>>>>>> 0ffa67dd1155c8759a2c13bebc9f017bd62111e3
 *
 * @method mixed update($params)
 *
 * @phpstan-require-extends Model
 *
 * @mixin \Eloquent
 */
interface PivotContract
{
}
