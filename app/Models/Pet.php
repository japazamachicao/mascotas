<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'species',
        'breed',
        'birth_date',
        'gender',
        'color',
        'weight',
        'chip_id',
        'is_sterilized',
        'medical_notes',
        'behavior',
        'health_features',
        'profile_photo_path',
        'uuid',
        'qr_code_path',
        'detected_breeds',
        'breed_confidence',
        'nutritional_needs',
        'breed_detected_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'weight' => 'decimal:2',
        'is_sterilized' => 'boolean',
        'behavior' => 'array',
        'health_features' => 'array',
        'detected_breeds' => 'array',
        'breed_confidence' => 'float',
        'nutritional_needs' => 'array',
        'breed_detected_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function healthAnalyses()
    {
        return $this->hasMany(HealthAnalysis::class);
    }
}
