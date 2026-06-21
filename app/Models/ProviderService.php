<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderService extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'duration_minutes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services')
            ->withPivot('price_at_booking')
            ->withTimestamps();
    }
}
