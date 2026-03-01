<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CiQuartier extends Model
{
    protected $table = 'ci_quartiers';
    protected $fillable = ['commune_id', 'name'];

    public function commune()
    {
        return $this->belongsTo(CiCommune::class, 'commune_id');
    }
}
