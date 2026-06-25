<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Importamos Spatie

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles; // Usamos el trait HasRoles

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'yape_number',
        'plin_number',
        'yape_qr_path',
        'plin_qr_path',
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

    public function services()
    {
        return $this->hasMany(ProviderService::class);
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

    public function getActiveProviderProfiles()
    {
        $profiles = [];
        $providerRoles = [
            'veterinarian' => 'veterinarianProfile',
            'walker' => 'walkerProfile',
            'groomer' => 'groomerProfile',
            'hotel' => 'hotelProfile',
            'shelter' => 'shelterProfile',
            'trainer' => 'trainerProfile',
            'pet_sitter' => 'petSitterProfile',
            'pet_taxi' => 'petTaxiProfile',
            'pet_photographer' => 'petPhotographerProfile',
        ];

        foreach ($providerRoles as $role => $relation) {
            if ($this->hasRole($role) && $this->$relation) {
                $profiles[$role] = $this->$relation;
            }
        }
        return $profiles;
    }

    public function getProviderProfileAttribute()
    {
        $active = $this->getActiveProviderProfiles();
        return !empty($active) ? reset($active) : null;
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

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function conversationsAsClient()
    {
        return $this->hasMany(Conversation::class, 'client_id');
    }

    public function conversationsAsProvider()
    {
        return $this->hasMany(Conversation::class, 'provider_id');
    }

    public function blockedDates()
    {
        return $this->hasMany(BlockedDate::class, 'provider_id');
    }

    public function getProfileCompleteness($profile = null): int
    {
        if (!$profile) {
            $active = $this->getActiveProviderProfiles();
            $profile = !empty($active) ? reset($active) : null;
        }

        if (!$profile) {
            return 0;
        }

        $checklist = [
            'photo' => !empty($this->profile_photo_path),
            'location' => !empty($profile->district_id),
            'verification' => !empty($profile->verification_document_path),
            'services' => $this->services()->exists(),
            'payment' => !empty($this->yape_number) || !empty($this->plin_number),
            'portfolio' => $this->portfolio()->exists(),
        ];

        $points = [
            'photo' => 20,
            'location' => 20,
            'verification' => 20,
            'services' => 20,
            'payment' => 10,
            'portfolio' => 10,
        ];

        $score = 0;
        foreach ($checklist as $key => $complete) {
            if ($complete) {
                $score += $points[$key];
            }
        }

        return $score;
    }

    public function getProfileLevel($profile = null): array
    {
        $score = $this->getProfileCompleteness($profile);

        if ($score < 50) {
            return [
                'name' => 'bronce',
                'label' => 'Bronce',
                'badge' => '🥉',
                'class' => 'bg-amber-50 text-amber-700 border-amber-200',
                'score' => $score,
            ];
        } elseif ($score < 80) {
            return [
                'name' => 'plata',
                'label' => 'Plata',
                'badge' => '🥈',
                'class' => 'bg-slate-50 text-slate-700 border-slate-200',
                'score' => $score,
            ];
        } elseif ($score < 100) {
            return [
                'name' => 'oro',
                'label' => 'Oro',
                'badge' => '🥇',
                'class' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                'score' => $score,
            ];
        } else {
            return [
                'name' => 'diamante',
                'label' => 'Diamante',
                'badge' => '💎',
                'class' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                'score' => $score,
            ];
        }
    }

    public function minServicePrice(): float
    {
        return (float) ($this->services()->min('price') ?? 0.0);
    }

    public function syncProfilesPriceFrom(): void
    {
        $minPrice = $this->minServicePrice();
        foreach ($this->getActiveProviderProfiles() as $profile) {
            $profile->update(['price_from' => $minPrice > 0 ? $minPrice : null]);
        }
    }

    public function profileUrl($role = null): string
    {
        $params = ['id' => \Illuminate\Support\Str::slug($this->name) . '-' . $this->id];
        if ($role) {
            $params['role'] = $role;
        }
        return route('profile.show', $params);
    }
}
