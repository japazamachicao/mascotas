<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'reference',
        'district_id',
        'is_default',
        'coordinates',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'coordinates' => 'array',
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
