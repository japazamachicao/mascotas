<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'appointment_id',
        'amount',
        'payment_method',
        'status',
        'transaction_reference',
        'receipt_photo_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
