<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Datum
 *
 * @property int $id
 * @property int $region_id
 * @property string $date
 * @property int $hospitalized_home
 * @property int $hospitalized_light
 * @property int $hospitalized_severe
 * @property int $healed
 * @property int $dead
 * @property int $tests
 * @property int|null $tested
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Datum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Datum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Datum query()
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereDead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereHealed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereHospitalizedHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereHospitalizedLight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereHospitalizedSevere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereTested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereTests($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Datum whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Datum extends Model
{
    protected $table = 'data';
    protected $guarded = ['id'];
}
