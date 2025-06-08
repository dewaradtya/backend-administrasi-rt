<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resident extends Model
{
    protected $fillable = [
        'name',
        'ktp_photo',
        'status',
        'phone',
        'is_married'
    ];

    public function house()
    {
        return $this->belongsTo(House::class, 'house_id');
    }

    public function inhabitantHistories(): HasMany
    {
        return $this->hasMany(InhabitantHistories::class);
    }
}
