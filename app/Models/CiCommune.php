<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiCommune extends Model
{
    protected $table = 'ci_communes';
    protected $fillable = ['district_id', 'name', 'code'];

    public function district()
    {
        return $this->belongsTo(CiDistrict::class, 'district_id');
    }

    public function quartiers()
    {
        return $this->hasMany(CiQuartier::class, 'commune_id');
    }
}
