<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Veterinarian;
use App\Models\Walker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    // Campos del formulario
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = 'client'; // Rol por defecto

    // Reglas de validación
    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:8',
        'role' => 'required|in:client,veterinarian,walker,hotel,groomer,shelter,trainer,pet_sitter,pet_taxi,pet_photographer',
    ];

    public function register()
    {
        $this->validate();

        // 1. Crear Usuario
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // 2. Asignar Rol (usando Spatie)
        $user->assignRole($this->role);

        // 3. Crear perfil de proveedor si corresponde
        if ($this->role === 'veterinarian') {
            \App\Models\Veterinarian::create(['user_id' => $user->id]);
        } elseif ($this->role === 'walker') {
            \App\Models\Walker::create(['user_id' => $user->id]);
        } elseif ($this->role === 'hotel') {
            $user->hotelProfile()->create([]);
        } elseif ($this->role === 'groomer') {
            $user->groomerProfile()->create([]);
        } elseif ($this->role === 'shelter') {
            $user->shelterProfile()->create([]);
        } elseif ($this->role === 'trainer') {
            $user->trainerProfile()->create([]);
        } elseif ($this->role === 'pet_sitter') {
            $user->petSitterProfile()->create([]);
        } elseif ($this->role === 'pet_taxi') {
            $user->petTaxiProfile()->create([]);
        } elseif ($this->role === 'pet_photographer') {
            $user->petPhotographerProfile()->create([]);
        }

        // 4. Iniciar sesión y redirigir
        Auth::login($user);

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
