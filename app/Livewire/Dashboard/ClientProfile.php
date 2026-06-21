<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClientProfile extends Component
{
    use WithFileUploads;

    // Profile fields
    public $name;
    public $email;
    public $photo;
    public $existingPhoto;

    // Password fields
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->existingPhoto = $user->profile_photo_path;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|max:1024', // max 1MB
        ]);

        $photoPath = $this->existingPhoto;

        if ($this->photo) {
            $photoPath = $this->photo->store('profile-photos', config('filesystems.default'));
        }

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'profile_photo_path' => $photoPath,
        ]);

        $this->existingPhoto = $photoPath;
        $this->reset('photo');

        // Refrescar usuario logueado en la sesión
        Auth::setUser($user);

        session()->flash('profile_message', 'Perfil actualizado correctamente.');
    }

    public function updatePassword()
    {
        $user = Auth::user();

        $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'new_password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('password_message', 'Contraseña actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.dashboard.client-profile')->layout('components.layouts.app');
    }
}
