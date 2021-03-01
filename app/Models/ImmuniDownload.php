<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ImmuniDownload
 *
 * @property int $id
 * @property string $date
 * @property int $ios_downloads
 * @property int $android_downloads
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int $total_downloads
 * @method static \Illuminate\Database\Eloquent\Builder|ImmuniDownload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImmuniDownload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImmuniDownload query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImmuniDownload whereAndroidDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImmuniDownload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImmuniDownload whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImmuniDownload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImmuniDownload whereIosDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImmuniDownload whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ImmuniDownload extends Model
{
    protected $table = 'immuni_downloads';
    protected $guarded = ['id'];

    public function getTotalDownloadsAttribute() : int{
        return $this->ios_downloads + $this->android_downloads;
    }
}
