<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    protected $fillable = ['name', 'slug', 'nep_name'];

    /**
     * @return HasMany
     */
    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
