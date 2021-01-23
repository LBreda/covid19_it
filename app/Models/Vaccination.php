<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Vaccination
 *
 * @property int $id
 * @property int $region_id
 * @property int|null $vaccine_supplier_id
 * @property \Illuminate\Support\Carbon $date
 * @property int $daily_first_doses
 * @property int $daily_second_doses
 * @property int $daily_shipped
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int $daily_vaccinated
 * @property-read \App\Models\VaccineSupplier|null $vaccine_supplier
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereDailyFirstDoses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereDailySecondDoses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereDailyShipped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vaccination whereVaccineSupplierId($value)
 * @mixin \Eloquent
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
