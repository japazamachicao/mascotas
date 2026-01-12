<?php

namespace App\Livewire\Dashboard;

use App\Models\Pet;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use App\Services\AIVisionService;
use App\Services\DietRecommendationService;

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
    
    // AI Detection
    public $detectedBreeds = [];
    public $breedConfidence = null;
    public $nutritionalNeeds = [];
    public $detectingBreed = false;
    
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
            
            // Cargar datos de IA si existen
            $this->detectedBreeds = $pet->detected_breeds ?? [];
            $this->breedConfidence = $pet->breed_confidence;
            $this->nutritionalNeeds = $pet->nutritional_needs ?? [];
        }
    }
    
    public function detectBreed()
    {
        if (!$this->photo) {
            session()->flash('error', 'Debes subir una foto primero');
            return;
        }
        
        $this->detectingBreed = true;
        
        try {
            // Guardar temporalmente la imagen
            $tempPath = $this->photo->store('temp', 'public');
            
            // Detectar raza con IA
            $aiService = new AIVisionService();
            $result = $aiService->detectBreed('public/' . $tempPath);
            
            if (!$result['success']) {
                throw new \Exception($result['error'] ?? 'Error al detectar la raza');
            }
            
            $data = $result['data'];
            $this->detectedBreeds = $data['breeds'] ?? [];
            $this->breedConfidence = $data['confidence_score'] ?? 0;
            
            // Si se detectaron razas, calcular necesidades nutricionales
            if (!empty($this->detectedBreeds) && $this->weight) {
                $dietService = new DietRecommendationService();
                $ageMonths = $this->birth_date 
                    ? now()->diffInMonths($this->birth_date) 
                    : 24; // Default 2 años si no hay fecha
                
                $this->nutritionalNeeds = $dietService->generateRecommendations(
                    $this->detectedBreeds,
                    (float) $this->weight,
                    $ageMonths
                );
            }
            
            // Autocompletar el campo de raza si está vacío
            if (empty($this->breed) && !empty($this->detectedBreeds)) {
                $primaryBreed = $this->detectedBreeds[0]['name'] ?? '';
                if (count($this->detectedBreeds) > 1) {
                    $this->breed = 'Mestizo (' . implode(', ', array_column(array_slice($this->detectedBreeds, 0, 2), 'name')) . ')';
                } else {
                    $this->breed = $primaryBreed;
                }
            }
            
            session()->flash('success', 'Raza detectada exitosamente!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al detectar raza: ' . $e->getMessage());
        } finally {
            $this->detectingBreed = false;
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
            'detected_breeds' => $this->detectedBreeds,
            'breed_confidence' => $this->breedConfidence,
            'nutritional_needs' => $this->nutritionalNeeds,
            'breed_detected_at' => !empty($this->detectedBreeds) ? now() : null,
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
