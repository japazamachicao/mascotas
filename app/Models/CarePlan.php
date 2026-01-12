<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarePlan extends Model
{
    protected $fillable = [
        'user_id',
        'pet_id',
        'pet_data',
        'plan_data',
        'generation_method',
        'is_favorite',
    ];

    protected $casts = [
        'pet_data' => 'array',
        'plan_data' => 'array',
        'is_favorite' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function getPetNameAttribute(): string
    {
        return $this->pet_data['name'] ?? 'Mascota';
    }

    public function getPetBreedAttribute(): string
    {
        return $this->pet_data['breed'] ?? 'Desconocido';
    }
}
