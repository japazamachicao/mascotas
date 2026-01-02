<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groomer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'district_id', 'verification_document_path', 'is_verified', 'verification_attempts', 'bio', 'address', 'allows_home_visits', 'website_url', 'facebook_url', 'instagram_url', 'tiktok_url', 'whatsapp_number', 'availability'];

    protected $casts = [
        'allows_home_visits' => 'boolean',
        'availability' => 'array',
        'is_verified' => 'boolean',
        'verification_document_path' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
