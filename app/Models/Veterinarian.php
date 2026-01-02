<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veterinarian extends Model
{
    protected $fillable = ['user_id', 'district_id', 'verification_document_path', 'is_verified', 'verification_attempts', 'license_number', 'address', 'allows_home_visits', 'emergency_24h', 'bio', 'website_url', 'facebook_url', 'instagram_url', 'tiktok_url', 'whatsapp_number', 'availability'];

    protected $casts = [
        'allows_home_visits' => 'boolean',
        'emergency_24h' => 'boolean',
        'availability' => 'array',
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
