<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\VaccineSupplier
 *
 * @property int $id
 * @property string $name
 * @property int $doses_needed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vaccination[] $vaccinations
 * @property-read int|null $vaccinations_count
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineSupplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineSupplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineSupplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineSupplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineSupplier whereDosesNeeded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineSupplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineSupplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VaccineSupplier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VaccineSupplier extends Model
{
    protected $table = 'vaccine_suppliers';
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at'];

    public function vaccinations(): HasMany
    {
        return $this->hasMany(Vaccination::class, 'vaccine_supplier_id');
    }
}
