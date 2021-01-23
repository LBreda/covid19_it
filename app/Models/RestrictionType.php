<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RestrictionType
 *
 * @property int $id
 * @property string $data_json
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $color
 * @method static \Illuminate\Database\Eloquent\Builder|RestrictionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RestrictionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RestrictionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|RestrictionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestrictionType whereDataJson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestrictionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RestrictionType whereUpdatedAt($value)
 * @mixin \Eloquent
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
