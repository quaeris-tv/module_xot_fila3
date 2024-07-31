<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Extra.
 *
 * @property int                                               $id
 * @property int|null                                          $model_id
 * @property string|null                                       $model_type
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel disableCache()
 * @method static \Modules\Xot\Database\Factories\ExtraFactory    factory($count = null, $state = [])
 * @method static Builder|Extra                                   newModelQuery()
 * @method static Builder|Extra                                   newQuery()
 * @method static Builder|Extra                                   query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel withCacheCooldownSeconds(?int $seconds = null)
 * @method static Builder|Extra                                   withExtraAttributes()
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $updated_by
 * @property string|null                     $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null                     $deleted_by
 * @method static Builder|Extra whereCreatedAt($value)
 * @method static Builder|Extra whereCreatedBy($value)
 * @method static Builder|Extra whereDeletedAt($value)
 * @method static Builder|Extra whereDeletedBy($value)
 * @method static Builder|Extra whereExtraAttributes($value)
 * @method static Builder|Extra whereId($value)
 * @method static Builder|Extra whereModelId($value)
 * @method static Builder|Extra whereModelType($value)
 * @method static Builder|Extra whereUpdatedAt($value)
 * @method static Builder|Extra whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Extra extends BaseExtra
{
}
