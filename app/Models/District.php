<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['id', 'name', 'province_id', 'department_id'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relación con proveedores que residen en este distrito
    public function veterinarians()
    {
        return $this->hasMany(Veterinarian::class);
    }
}
