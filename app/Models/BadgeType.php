<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'emoji',
        'description',
        'condition',
        'required_value',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot('awarded_at', 'reason')
            ->withTimestamps();
    }
}
