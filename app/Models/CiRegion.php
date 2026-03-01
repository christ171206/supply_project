<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiRegion extends Model
{
    protected $table = 'ci_regions';
    protected $fillable = ['name', 'code'];

    public function districts()
    {
        return $this->hasMany(CiDistrict::class, 'region_id');
    }
}
