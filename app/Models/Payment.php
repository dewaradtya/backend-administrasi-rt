<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'house_id',
        'total_amount',
        'note',
        'status',
        'payment_date'
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function house()
    {
        return $this->belongsTo(House::class);
    }

    public function payment_items()
    {
        return $this->hasMany(PaymentItem::class);
    }
}
