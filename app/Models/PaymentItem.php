<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'type',
        'amount',
        'start_date',
        'end_date',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
