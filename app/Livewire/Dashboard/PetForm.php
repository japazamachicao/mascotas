<?php

namespace App\Livewire\Dashboard;

use App\Models\Pet;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PetForm extends Component
{
    use \Livewire\WithFileUploads;

    public $name;
    public $species = 'Perro';
    public $breed;
    public $birth_date;
    public $gender = 'M';
    public $color;
    public $chip_id;
    public $weight;
    public $is_sterilized = false;
    public $medical_notes;
    public $photo; // Para la imagen

    protected $rules = [
        'name' => 'required|min:2',
        'species' => 'required',
        'breed' => 'nullable|string',
        'gender' => 'required|in:M,F',
        'weight' => 'required|numeric|min:0.1|max:999.99', // Ahora es requerido
        'color' => 'required|string', // Sugerido por usuario como opción
        'photo' => 'nullable|image|max:5120',
        'chip_id' => 'nullable|string|max:50',
    ];

    public function getBreedsProperty()
    {
        return $this->species === 'Perro' 
            ? ['Mestizo', 'Labrador', 'Golden Retriever', 'Bulldog', 'Poodle', 'Beagle', 'Chihuahua', 'Pastor Alemán', 'Schnauzer', 'Otro'] 
            : ['Mestizo', 'Persa', 'Siames', 'Angora', 'Maine Coon', 'Bengala', 'Sphynx', 'Otro'];
    }

    public function getColorsProperty()
    {
        return ['Blanco', 'Negro', 'Marrón', 'Dorado', 'Gris', 'Crema', 'Manchado', 'Tricolor', 'Otro'];
    }

    protected $messages = [
        'weight.max' => '¡Epa! ¿Tu mascota pesa más de una tonelada? El límite es 999kg.',
        'photo.image' => 'El archivo debe ser una imagen válida.',
        'photo.max' => 'La foto no debe pesar más de 5MB.',
    ];

    public function save()
    {
        $this->validate();

        $photoPath = null;
        if ($this->photo) {
            // Usa el disco configurado en .env (public en local, gcs en prod)
            $photoPath = $this->photo->store('pets', env('FILESYSTEM_DISK', 'public'));
        }

        Pet::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'species' => $this->species,
            'breed' => $this->breed,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'color' => $this->color,
            'chip_id' => $this->chip_id,
            'weight' => $this->weight,
            'is_sterilized' => $this->is_sterilized,
            'medical_notes' => $this->medical_notes,
            'profile_photo_path' => $photoPath,
            'uuid' => Str::uuid(),
        ]);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.dashboard.pet-form')->layout('components.layouts.app');
    }
}
