<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Notice
 *
 * @property int $id
 * @property int|null $region_id
 * @property \Illuminate\Support\Carbon $date
 * @property string $notice
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Region|null $region
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notice whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notice whereNotice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notice whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notice whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $restriction_type_id
 * @property \Illuminate\Support\Carbon $since
 * @property \Illuminate\Support\Carbon|null $until
 * @property-read \App\Models\RestrictionType $restriction_type
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction whereRestrictionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction whereSince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Restriction whereUntil($value)
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
