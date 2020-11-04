<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Region
 *
 * @property int $id
 * @property string $name
 * @property int $code
 * @property float $latitude
 * @property float $longitude
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notice[] $notices
 * @property-read int|null $notices_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $population
 * @property mixed|null $geometry
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region whereGeometry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region wherePopulation($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Datum[] $data
 * @property-read int|null $data_count
 * @property int $severity
 * @method static \Illuminate\Database\Eloquent\Builder|Region whereSeverity($value)
 */
class Region extends Model
{
    protected $table = "regions";
    protected $guarded = ['id'];

    public function notices() {
        return $this->hasMany(Notice::class);
    }

    public function data() {
        return $this->hasMany(Datum::class);
    }
}
