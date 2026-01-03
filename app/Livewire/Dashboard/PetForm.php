<?php

namespace App\Livewire\Dashboard;

use App\Models\Pet;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;

class PetForm extends Component
{
    use \Livewire\WithFileUploads;

    public $pet; // Instancia de mascota para edición
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
    // Comportamiento
    public $energy_level = 'media';
    public $sociable_kids = false;
    public $sociable_dogs = false;
    public $sociable_cats = false;
    public $fear_fireworks = false;
    public $fear_cars = false;
    
    // Salud
    public $vaccination_date;
    public $deworming_date;

    public $photo; // Para la imagen nueva
    
    // UI State
    #[Url(as: 'section')]
    public $activeTab = 'general';

    // Propiedad para mostrar foto existente si no se carga nueva
    public $existingPhoto;

    protected $rules = [
        'name' => 'required|min:2',
        'species' => 'required',
        'breed' => 'nullable|string',
        'gender' => 'required|in:M,F',
        'weight' => 'required|numeric|min:0.1|max:999.99',
        'color' => 'required|string',
        'is_sterilized' => 'boolean',
        'energy_level' => 'required|in:baja,media,alta',
        'sociable_kids' => 'boolean',
        'sociable_dogs' => 'boolean',
        'sociable_cats' => 'boolean',
        'fear_fireworks' => 'boolean',
        'fear_cars' => 'boolean',
        'vaccination_date' => 'nullable|date',
        'deworming_date' => 'nullable|date',
        'photo' => 'nullable|image|max:5120',
        'chip_id' => 'nullable|string|max:50',
    ];

    public function mount(Pet $pet = null)
    {
        if ($pet && $pet->exists) {
            $this->pet = $pet;
            
            // Verificar pertenencia (Seguridad simple)
            if ($pet->user_id !== Auth::id()) {
                abort(403);
            }

            $this->name = $pet->name;
            $this->species = $pet->species;
            $this->breed = $pet->breed;
            $this->birth_date = $pet->birth_date ? $pet->birth_date->format('Y-m-d') : null;
            $this->gender = $pet->gender;
            $this->color = $pet->color;
            $this->chip_id = $pet->chip_id;
            $this->weight = $pet->weight;
            $this->is_sterilized = (bool) $pet->is_sterilized;
            $this->medical_notes = $pet->medical_notes;
            $this->existingPhoto = $pet->profile_photo_path;

            // Cargar Comportamiento
            $behavior = $pet->behavior ?? [];
            $this->energy_level = $behavior['energy_level'] ?? 'media';
            $this->sociable_kids = $behavior['sociable_kids'] ?? false;
            $this->sociable_dogs = $behavior['sociable_dogs'] ?? false;
            $this->sociable_cats = $behavior['sociable_cats'] ?? false;
            $this->fear_fireworks = $behavior['fear_fireworks'] ?? false;
            $this->fear_cars = $behavior['fear_cars'] ?? false;

            // Cargar Salud
            $health = $pet->health_features ?? [];
            $this->vaccination_date = $health['vaccination_date'] ?? null;
            $this->deworming_date = $health['deworming_date'] ?? null;
        }
    }

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

        $photoPath = $this->existingPhoto;
        
        if ($this->photo) {
            $photoPath = $this->photo->store('pets', env('FILESYSTEM_DISK', 'public'));
        }

        $behaviorData = [
            'energy_level' => $this->energy_level,
            'sociable_kids' => $this->sociable_kids,
            'sociable_dogs' => $this->sociable_dogs,
            'sociable_cats' => $this->sociable_cats,
            'fear_fireworks' => $this->fear_fireworks,
            'fear_cars' => $this->fear_cars,
        ];

        $healthData = [
            'vaccination_date' => $this->vaccination_date,
            'deworming_date' => $this->deworming_date,
        ];

        $data = [
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
            'behavior' => $behaviorData,
            'health_features' => $healthData,
        ];

        if ($this->pet) {
            $this->pet->update($data);
            session()->flash('message', 'Mascota actualizada correctamente.');
        } else {
            Pet::create(array_merge($data, [
                'user_id' => Auth::id(),
                'uuid' => Str::uuid(),
            ]));
            session()->flash('message', 'Mascota creada correctamente.');
        }

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.dashboard.pet-form')->layout('components.layouts.app');
    }
}
