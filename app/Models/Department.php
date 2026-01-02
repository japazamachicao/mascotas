<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $incrementing = false; // El ID es un string ('15')
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['id', 'name'];

    public function provinces()
    {
        return $this->hasMany(Province::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
