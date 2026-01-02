<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Walker extends Model
{
    protected $fillable = ['user_id', 'district_id', 'verification_document_path', 'is_verified', 'verification_attempts', 'experience', 'hourly_rate', 'website_url', 'facebook_url', 'instagram_url', 'tiktok_url', 'whatsapp_number', 'availability'];

    protected $casts = [
        'is_verified' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'availability' => 'array',
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
