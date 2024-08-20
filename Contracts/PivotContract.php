<?php

declare(strict_types=1);

namespace Modules\Xot\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Xot\Contracts\PivotContract.
 *
<<<<<<< HEAD
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $price
 * @property string|null $price_currency
 * @property int|null $status
 * @property Collection|array<ProductContract> $products
=======
 * @property string|null                         $title
 * @property string|null                         $subtitle
 * @property string|null                         $price
 * @property string|null                         $price_currency
 * @property int|null                            $status
 * @property Collection|array<ProductContract>   $products
>>>>>>> 35d9347 (.)
 * @property Collection|array<ChangeCatContract> $changeCats
 *
 * @method mixed update($params)
 *
 * @phpstan-require-extends Model
 *
 * @mixin \Eloquent
 */
<<<<<<< HEAD
interface PivotContract {}
=======
interface PivotContract
{
}
>>>>>>> 35d9347 (.)
