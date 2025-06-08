<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class House extends Model
{
    protected $fillable = [
        'house_number',
        'is_occupied'
    ];

    public function inhabitantHistories()
    {
        return $this->hasMany(InhabitantHistories::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
