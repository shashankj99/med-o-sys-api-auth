<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $fillable = [
        'district_id', 'name', 'slug', 'nep_name'
    ];

    /**
     * @return BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
