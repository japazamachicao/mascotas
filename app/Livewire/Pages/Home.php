<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Component;

class Home extends Component
{
    public function getProviderData(User $user)
    {
        $data = [
            'name' => $user->name,
            'title' => 'Proveedor de Servicios',
            'rating' => round($user->reviewsReceived()->avg('rating') ?? 5.0, 1),
            'reviews_count' => $user->reviewsReceived()->count(),
            'price' => null,
            'district' => 'Lima',
            'photo' => $user->profile_photo_path 
                ? \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) 
                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0ea5e9&color=fff',
        ];

        if ($user->hasRole('veterinarian') && $user->veterinarianProfile) {
            $data['title'] = 'Médico Veterinario 🩺';
            $data['price'] = $user->veterinarianProfile->price_from;
            $data['district'] = $user->veterinarianProfile->district->name ?? 'Lima';
        } elseif ($user->hasRole('walker') && $user->walkerProfile) {
            $data['title'] = 'Paseador de Perros 🐕';
            $data['price'] = $user->walkerProfile->price_from;
            $data['district'] = $user->walkerProfile->district->name ?? 'Lima';
        } elseif ($user->hasRole('groomer') && $user->groomerProfile) {
            $data['title'] = 'Estilista de Mascotas ✂️';
            $data['price'] = $user->groomerProfile->price_from;
            $data['district'] = $user->groomerProfile->district->name ?? 'Lima';
        } elseif ($user->hasRole('hotel') && $user->hotelProfile) {
            $data['title'] = 'Hospedaje Canino 🏠';
            $data['price'] = $user->hotelProfile->price_from;
            $data['district'] = $user->hotelProfile->district->name ?? 'Lima';
        } elseif ($user->hasRole('trainer') && $user->trainerProfile) {
            $data['title'] = 'Adiestrador Profesional 🦴';
            $data['price'] = $user->trainerProfile->price_from;
            $data['district'] = $user->trainerProfile->district->name ?? 'Lima';
        }

        return $data;
    }

    public function render()
    {
        // Obtener 3 proveedores registrados (Veterinario, Paseador, Estilista)
        $featuredProviders = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['veterinarian', 'walker', 'groomer', 'hotel', 'trainer']);
        })
        ->with([
            'roles',
            'veterinarianProfile.district',
            'walkerProfile.district',
            'groomerProfile.district',
            'hotelProfile.district',
            'trainerProfile.district'
        ])
        ->take(3)
        ->get();

        return view('livewire.pages.home', [
            'featuredProviders' => $featuredProviders
        ]);
    }
}
