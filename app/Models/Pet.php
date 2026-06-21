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
    ];

    protected $casts = [
        'birth_date' => 'date',
        'weight' => 'decimal:2',
        'is_sterilized' => 'boolean',
        'behavior' => 'array',
        'health_features' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
