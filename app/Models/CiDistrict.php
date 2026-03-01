<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiDistrict extends Model
{
    protected $table = 'ci_districts';
    protected $fillable = ['region_id', 'name', 'code'];

    public function region()
    {
        return $this->belongsTo(CiRegion::class, 'region_id');
    }

    public function communes()
    {
        return $this->hasMany(CiCommune::class, 'district_id');
    }
}
