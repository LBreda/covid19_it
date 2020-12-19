<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @property string $data_json
 * @method static \Illuminate\Database\Eloquent\Builder|RestrictionType whereDataJson($value)
 * @property-read string $color
 */
class RestrictionType extends Model
{
    protected $table = 'restriction_types';
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at'];

    public function getColorAttribute(): string
    {
        return '#' . json_decode($this->data_json)->color;
    }
}
