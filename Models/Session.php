<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * Modules\Xot\Models\Session.
 *
 * @property int                             $id
 * @property int|null                        $user_id
 * @property string|null                     $ip_address
 * @property string|null                     $user_agent
 * @property string                          $payload
 * @property int                             $last_activity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $created_by
 * @property string|null                     $updated_by
 * @method static \Modules\Xot\Database\Factories\SessionFactory factory($count = null, $state = [])
 * @method static Builder|Session                                newModelQuery()
 * @method static Builder|Session                                newQuery()
 * @method static Builder|Session                                query()
 * @method static Builder|Session                                whereCreatedAt($value)
 * @method static Builder|Session                                whereCreatedBy($value)
 * @method static Builder|Session                                whereId($value)
 * @method static Builder|Session                                whereIpAddress($value)
 * @method static Builder|Session                                whereLastActivity($value)
 * @method static Builder|Session                                wherePayload($value)
 * @method static Builder|Session                                whereUpdatedAt($value)
 * @method static Builder|Session                                whereUpdatedBy($value)
 * @method static Builder|Session                                whereUserAgent($value)
 * @method static Builder|Session                                whereUserId($value)
 * @property int                             $id
 * @property int|null                        $user_id
 * @property string|null                     $ip_address
 * @property string|null                     $user_agent
 * @property string                          $payload
 * @property int                             $last_activity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $created_by
 * @property string|null                     $updated_by
 * @method static \Modules\Xot\Database\Factories\SessionFactory factory($count = null, $state = [])
 * @method static Builder|Session                                newModelQuery()
 * @method static Builder|Session                                newQuery()
 * @method static Builder|Session                                query()
 * @method static Builder|Session                                whereCreatedAt($value)
 * @method static Builder|Session                                whereCreatedBy($value)
 * @method static Builder|Session                                whereId($value)
 * @method static Builder|Session                                whereIpAddress($value)
 * @method static Builder|Session                                whereLastActivity($value)
 * @method static Builder|Session                                wherePayload($value)
 * @method static Builder|Session                                whereUpdatedAt($value)
 * @method static Builder|Session                                whereUpdatedBy($value)
 * @method static Builder|Session                                whereUserAgent($value)
 * @method static Builder|Session                                whereUserId($value)
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null                     $deleted_by
 * @method static Builder|Session whereDeletedAt($value)
 * @method static Builder|Session whereDeletedBy($value)
 * @mixin \Eloquent
 */
class Session extends BaseModel
{
    protected $fillable = ['id', 'user_id', 'ip_address', 'user_agent', 'payload', 'last_activity'];
}
