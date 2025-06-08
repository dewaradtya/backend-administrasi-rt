<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InhabitantHistories extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'house_id',
        'start_date',
        'end_date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }
}
