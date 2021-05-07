<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    protected $fillable = [
        'province_id', 'name', 'slug', 'nep_name'
    ];

    /**
     * @return BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
