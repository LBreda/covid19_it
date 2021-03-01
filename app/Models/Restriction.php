<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Restriction
 *
 * @property int $id
 * @property int $restriction_type_id
 * @property int $region_id
 * @property \Illuminate\Support\Carbon $since
 * @property \Illuminate\Support\Carbon|null $until
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Region $region
 * @property-read \App\Models\RestrictionType $restriction_type
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction whereRestrictionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction whereSince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction whereUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Restriction extends Model
{
    protected $table = 'restrictions';
    protected $guarded = ['id'];
    protected $dates = ['since', 'until', 'created_at', 'updated_at'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function restriction_type(): BelongsTo
    {
        return $this->belongsTo(RestrictionType::class, 'restriction_type_id');
    }
}
