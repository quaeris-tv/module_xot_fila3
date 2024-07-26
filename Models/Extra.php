<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Extra.
 *
 * @property int                                               $id
 * @property int|null                                          $model_id
 * @property string|null                                       $model_type
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes $extra_attributes
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel disableCache()
 * @method static \Modules\Xot\Database\Factories\ExtraFactory    factory($count = null, $state = [])
 * @method static Builder|Extra                                   newModelQuery()
 * @method static Builder|Extra                                   newQuery()
 * @method static Builder|Extra                                   query()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel withCacheCooldownSeconds(?int $seconds = null)
 * @method static Builder|Extra                                   withExtraAttributes()
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $updated_by
 * @property string|null                     $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null                     $deleted_by
 *
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
 *
=======
/**
 * 
 *
 * @property int $id
 * @property string $model_type
 * @property string $model_id
 * @property \Spatie\SchemalessAttributes\SchemalessAttributes|null $extra_attributes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $updated_by
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $deleted_by
 * @method static \Illuminate\Database\Eloquent\Builder|Extra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Extra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Extra query()
 * @method static \Illuminate\Database\Eloquent\Builder|Extra whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Extra whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Extra whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Extra whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Extra whereExtraAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Extra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Extra whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Extra whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Extra whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Extra whereUpdatedBy($value)
 * @method static Builder|Extra withExtraAttributes()
>>>>>>> c51288a377571f8fb6aefb83656823ab0e2a0339
 * @mixin \Eloquent
 */
class Extra extends BaseExtra
{
}
