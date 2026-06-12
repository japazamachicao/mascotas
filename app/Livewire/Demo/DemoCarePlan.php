<?php

namespace App\Livewire\Demo;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\AIVisionService;
use App\Services\PetCareRecommendationService;

class DemoCarePlan extends Component
{
    use WithFileUploads;

    public $photo;
    public $generating = false;
    public $carePlan = null;
    public $petData = null;
    public $error = null;
    public $showRegistrationModal = false;
    public $demoUsed = false;

    public function mount()
    {
        // Verificar si ya usó la demo
        $this->demoUsed = session()->has('demo_care_used');
    }

    public function generateFromPhoto()
    {
        // Si ya usó la demo, mostrar modal de registro
        if ($this->demoUsed) {
            $this->showRegistrationModal = true;
            return;
        }

        $this->validate([
            'photo' => 'required|image|max:10240',
        ]);

        $this->generating = true;
        $this->error = null;

        try {
            $aiService = app(AIVisionService::class);
            $careService = app(PetCareRecommendationService::class);

            // Detectar raza
            $path = $this->photo->store('temp', 'public');
            $breedResult = $aiService->detectBreed('public/' . $path);

            // Verificar si hay error
            if (!$breedResult['success']) {
                throw new \Exception($breedResult['error'] ?? 'Error al detectar la raza');
            }

            $data = $breedResult['data'];
            $detectedBreeds = $data['breeds'] ?? [];

            $this->petData = [
                'name' => 'Tu Mascota',
                'species' => $data['primary_species'] === 'cat' ? 'Gato' : 'Perro',
                'breed' => !empty($detectedBreeds) ? $detectedBreeds[0]['name'] : 'Mestizo',
                'detected_breeds' => $detectedBreeds,
                'weight' => 15, // Default
                'age_months' => 24, // Default: 2 años
                'energy_level' => 'media',
            ];

            // Generar plan
            $this->carePlan = $careService->generateCompleteCareplan($this->petData);

            // Marcar demo como usada
            session()->put('demo_care_used', true);
            $this->demoUsed = true;

            // Mostrar modal después del resultado
            $this->showRegistrationModal = true;

        } catch (\Exception $e) {
            \Log::error('Demo Care Plan Error: ' . $e->getMessage());
            
            // Mensajes amigables según el tipo de error
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, '429') || str_contains($errorMessage, 'quota') || str_contains($errorMessage, 'RESOURCE_EXHAUSTED')) {
                $this->error = '⏱️ Hemos alcanzado el límite de planes por hoy. Por favor, intenta más tarde o regístrate para acceso prioritario.';
            } elseif (str_contains($errorMessage, 'API')) {
                $this->error = '🔧 Servicio temporalmente no disponible. Por favor, intenta en unos minutos.';
            } elseif (str_contains($errorMessage, 'imagen') || str_contains($errorMessage, 'raza')) {
                $this->error = '📸 ' . $errorMessage;
            } else {
                $this->error = '❌ No pudimos generar el plan. Por favor, intenta con otra foto o regístrate para soporte prioritario.';
            }
        } finally {
            $this->generating = false;
        }
    }

    public function resetPlan()
    {
        $this->reset(['carePlan', 'petData', 'photo', 'error']);
        
        // Si ya usó la demo, mostrar modal
        if ($this->demoUsed) {
            $this->showRegistrationModal = true;
        }
    }

    public function render()
    {
        return view('livewire.demo.demo-care-plan')
            ->layout('components.layouts.app');
    }
}
