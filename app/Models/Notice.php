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
 * @method static \Illuminate\Database\Eloquent\Builder|Notice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereNotice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Notice extends Model
{
    protected $table = 'notices';
    protected $guarded = ['id'];
    protected $dates = ['date'];

    public function region() {
        return $this->belongsTo(Region::class);
    }
}
