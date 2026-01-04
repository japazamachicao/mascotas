<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Importamos Spatie

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // Usamos el trait HasRoles

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relaciones
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function veterinarianProfile()
    {
        return $this->hasOne(Veterinarian::class);
    }

    public function walkerProfile()
    {
        return $this->hasOne(Walker::class);
    }

    public function groomerProfile()
    {
        return $this->hasOne(Groomer::class);
    }

    public function hotelProfile()
    {
        return $this->hasOne(PetHotel::class);
    }

    public function shelterProfile()
    {
        return $this->hasOne(Shelter::class);
    }

    public function trainerProfile()
    {
        return $this->hasOne(Trainer::class);
    }

    public function petSitterProfile()
    {
        return $this->hasOne(PetSitter::class);
    }

    public function petTaxiProfile()
    {
        return $this->hasOne(PetTaxi::class);
    }

    public function petPhotographerProfile()
    {
        return $this->hasOne(PetPhotographer::class);
    }

    public function portfolio()
    {
        return $this->hasMany(PortfolioImage::class);
    }

    // Métodos Helper
    public function isVeterinarian(): bool
    {
        return $this->hasRole('veterinarian');
    }
    
    public function isWalker(): bool
    {
        return $this->hasRole('walker');
    }

    public function isGroomer(): bool
    {
        return $this->hasRole('groomer');
    }

    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    // Relaciones de Favoritos
    public function favoriteProviders()
    {
        return $this->belongsToMany(User::class, 'favorites', 'user_id', 'provider_id')->withTimestamps();
    }

    // Nuevas relaciones para Reseñas y Citas
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    public function reviewsWritten()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function appointmentsAsClient()
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    public function appointmentsAsProvider()
    {
        return $this->hasMany(Appointment::class, 'provider_id');
    }
}
