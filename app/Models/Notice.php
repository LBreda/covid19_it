<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notices';
    protected $guarded = ['id'];
    protected $dates = ['date'];

    public function region() {
        return $this->belongsTo(Region::class);
    }
}
