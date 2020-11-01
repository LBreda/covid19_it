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
 * @property int $tested
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereDead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereHealed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereHospitalizedHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereHospitalizedLight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereHospitalizedSevere($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereTested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $tests
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Datum whereTests($value)
 * @property int $ios_downloads
 * @property int $android_downloads
 * @property-read mixed $total_downloads
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImmuniDownload whereAndroidDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ImmuniDownload whereIosDownloads($value)
 */
class ImmuniDownload extends Model
{
    protected $table = 'immuni_downloads';
    protected $guarded = ['id'];

    public function getTotalDownloadsAttribute() : int{
        return $this->ios_downloads + $this->android_downloads;
    }
}
