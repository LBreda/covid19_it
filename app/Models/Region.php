<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * App\Models\Region
 *
 * @property int $id
 * @property string $name
 * @property int $code
 * @property float $latitude
 * @property float $longitude
 * @property int|null $population
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Datum[] $data
 * @property-read int|null $data_count
 * @property-read array $severity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notice[] $notices
 * @property-read int|null $notices_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Restriction[] $restrictions
 * @property-read int|null $restrictions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Vaccination[] $vaccinations
 * @property-read int|null $vaccinations_count
 * @method static Builder|Region newModelQuery()
 * @method static Builder|Region newQuery()
 * @method static Builder|Region query()
 * @method static Builder|Region whereCode($value)
 * @method static Builder|Region whereCreatedAt($value)
 * @method static Builder|Region whereId($value)
 * @method static Builder|Region whereLatitude($value)
 * @method static Builder|Region whereLongitude($value)
 * @method static Builder|Region whereName($value)
 * @method static Builder|Region wherePhone($value)
 * @method static Builder|Region wherePopulation($value)
 * @method static Builder|Region whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Region extends Model
{
    protected $table = "regions";
    protected $guarded = ['id'];

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    public function data(): HasMany
    {
        return $this->hasMany(Datum::class);
    }

    public function vaccinations(): HasMany
    {
        return $this->hasMany(Vaccination::class);
    }

    public function restrictions(): HasMany
    {
        return $this->hasMany(Restriction::class);
    }

    public function getSeverityAttribute(): array
    {
        return $this->restrictions
            ->where('since', '<=', Carbon::now()->startOfDay())
            ->filter(function (Restriction $r) {
                return $r->until === null
                    or $r->until->greaterThan(Carbon::now()->startOfDay());
            })->map(fn (Restriction $r) => ['level' => $r->restriction_type->id, 'color' => $r->restriction_type->color])
            ->last() ?: ['level' => 0, 'color' => 'ffffff'];
    }
}
