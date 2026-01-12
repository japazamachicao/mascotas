<?php

namespace App\Livewire\Dashboard;

use App\Models\Pet;
use App\Services\AIVisionService;
use App\Services\PetCareRecommendationService;
use Livewire\Component;
use Livewire\WithFileUploads;

class CarePlanGenerator extends Component
{
    use WithFileUploads;

    public $pets;
    public $selectedMode = 'select'; // 'select' o 'upload'
    public $selectedPetId;
    public $photo;
    public $generating = false;
    public $carePlan = null;
    public $petData = null;
    public $error = null;

    public function mount()
    {
        $this->pets = auth()->user()->pets;
    }

    public function generateFromPet()
    {
        $this->validate([
            'selectedPetId' => 'required|exists:pets,id',
        ], [
            'selectedPetId.required' => 'Debes seleccionar una mascota',
        ]);

        $this->generating = true;
        $this->error = null;

        try {
            $pet = Pet::findOrFail($this->selectedPetId);
            
            // Verificar pertenencia
            if ($pet->user_id !== auth()->id()) {
                abort(403);
            }

            $this->petData = [
                'name' => $pet->name,
                'species' => $pet->species,
                'breed' => $pet->breed,
                'detected_breeds' => $pet->detected_breeds ?? [],
                'weight' => $pet->weight,
                'age_months' => $pet->birth_date ? now()->diffInMonths($pet->birth_date) : 24,
                'energy_level' => $pet->behavior['energy_level'] ?? 'media',
                'photo' => $pet->profile_photo_path,
            ];

            // Generar plan de cuidado
            $careService = new PetCareRecommendationService();
            $this->carePlan = $careService->generateCompleteCareplan($this->petData);

            // Guardar el plan en la base de datos
            \App\Models\CarePlan::create([
                'user_id' => auth()->id(),
                'pet_id' => $pet->id,
                'pet_data' => $this->petData,
                'plan_data' => $this->carePlan,
                'generation_method' => 'pet',
            ]);

        } catch (\Exception $e) {
            $this->error = 'Error: ' . $e->getMessage();
        } finally {
            $this->generating = false;
        }
    }

    public function generateFromPhoto()
    {
        $this->validate([
            'photo' => 'required|image|max:10240',
        ], [
            'photo.required' => 'Debes subir una foto',
            'photo.image' => 'El archivo debe ser una imagen',
            'photo.max' => 'La imagen no debe pesar más de 10MB',
        ]);

        $this->generating = true;
        $this->error = null;

        try {
            // Guardar imagen temporalmente
            $path = $this->photo->store('temp', 'public');

            // Detectar raza con IA
            $aiService = new AIVisionService();
            $result = $aiService->detectBreed('public/' . $path);

            if (!$result['success']) {
                throw new \Exception($result['error'] ?? 'Error al detectar la raza');
            }

            $data = $result['data'];
            $detectedBreeds = $data['breeds'] ?? [];
            
            // Datos estimados (el usuario puede ajustar después)
            $this->petData = [
                'name' => 'Tu mascota',
                'species' => $data['primary_species'] === 'cat' ? 'Gato' : 'Perro',
                'breed' => !empty($detectedBreeds) ? $detectedBreeds[0]['name'] : 'Mestizo',
                'detected_breeds' => $detectedBreeds,
                'weight' => 15, // Default
                'age_months' => 24, // Default: 2 años
                'energy_level' => 'media',
                'photo' => $path,
            ];

            // Generar plan de cuidado
            $careService = new PetCareRecommendationService();
            $this->carePlan = $careService->generateCompleteCareplan($this->petData);

            // Guardar el plan en la base de datos
            \App\Models\CarePlan::create([
                'user_id' => auth()->id(),
                'pet_id' => null, // No hay mascota asociada
                'pet_data' => $this->petData,
                'plan_data' => $this->carePlan,
                'generation_method' => 'photo',
            ]);

        } catch (\Exception $e) {
            $this->error = 'Error: ' . $e->getMessage();
        } finally {
            $this->generating = false;
        }
    }

    public function resetPlan()
    {
        $this->carePlan = null;
        $this->petData = null;
        $this->error = null;
        $this->photo = null;
        $this->selectedPetId = null;
    }

    public function render()
    {
        return view('livewire.dashboard.care-plan-generator');
    }
}
