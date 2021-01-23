<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property int $vaccinated
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereVaccinated($value)
 * @property int $daily_vaccinated
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereDailyVaccinated($value)
 * @property int $daily_shipped
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereDailyShipped($value)
 * @property int $daily_first_doses
 * @property int $daily_second_doses
 * @property-read int $daily_vaccinations
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereDailyFirstDoses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereDailySecondDoses($value)
 * @property int|null $vaccine_supplier_id
 * @property-read \App\Models\VaccineSupplier|null $vaccine_supplier
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereVaccineSupplierId($value)
 */
class Vaccination extends Model
{
    protected $table = 'vaccinations';
    protected $guarded = ['id'];
    protected $dates = ['date', 'created_at', 'updated_at'];

    /**
     * @return BelongsTo
     */
    public function vaccine_supplier(): BelongsTo
    {
        return $this->belongsTo(VaccineSupplier::class, 'vaccine_supplier_id');
    }

    /**
     * Total daily vaccinations
     * @return int
     */
    public function getDailyVaccinatedAttribute(): int
    {
        return $this->daily_first_doses + $this->daily_second_doses;
    }
}
