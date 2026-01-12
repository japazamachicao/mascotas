<?php

namespace App\Livewire\Dashboard;

use App\Models\HealthAnalysis;
use App\Models\Pet;
use App\Services\AIVisionService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class HealthAnalyzer extends Component
{
    use WithFileUploads;

    public $pets;
    public $selectedPetId;
    public $analysisType = 'feces';
    public $photo;
    public $analyzing = false;
    public $result = null;
    public $error = null;
    public $maxAnalyses;
    public $analysesCount;

    public function mount()
    {
        $this->pets = auth()->user()->pets;
        $this->maxAnalyses = config('ai.max_daily_analyses', 10);
        $this->analysesCount = \App\Models\HealthAnalysis::where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->count();
    }

    public function analyze()
    {
        $this->validate([
            'selectedPetId' => 'required|exists:pets,id',
            'analysisType' => 'required|in:feces,urine,skin',
            'photo' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:10240', // Max 10MB
        ], [
            'selectedPetId.required' => 'Debes seleccionar una mascota',
            'analysisType.required' => 'Debes seleccionar el tipo de análisis',
            'photo.required' => 'Debes subir una foto',
            'photo.mimes' => 'La foto debe ser JPG, JPEG, PNG, GIF o WebP',
            'photo.max' => 'La imagen no debe pesar más de 10MB',
        ]);

        $this->analyzing = true;
        $this->result = null;
        $this->error = null;

        try {
            // Verificar límite diario
            $today = now()->startOfDay();
            $todayAnalyses = HealthAnalysis::where('user_id', auth()->id())
                ->where('created_at', '>=', $today)
                ->count();

            $maxAnalyses = config('ai.max_analyses_per_day');
            
            if ($todayAnalyses >= $maxAnalyses) {
                $this->error = "Has alcanzado el límite de {$maxAnalyses} análisis por día. Intenta mañana.";
                $this->analyzing = false;
                return;
            }

            // Guardar imagen
            $path = $this->photo->store('health-analyses', 'public');

            // Analizar con IA
            $aiService = new AIVisionService();
            
            $analysis = match($this->analysisType) {
                'feces' => $aiService->analyzeFeces('public/' . $path),
                'urine' => $aiService->analyzeUrine('public/' . $path),
                'skin' => $aiService->analyzeSkin('public/' . $path),
                default => throw new \Exception('Tipo de análisis no válido'),
            };

            if (!$analysis['success']) {
                throw new \Exception($analysis['error'] ?? 'Error al analizar la imagen');
            }

            $data = $analysis['data'];

            // Guardar en base de datos
            $healthAnalysis = HealthAnalysis::create([
                'pet_id' => $this->selectedPetId,
                'user_id' => auth()->id(),
                'analysis_type' => $this->analysisType,
                'image_path' => $path,
                'ai_response' => $analysis['raw_response'],
                'findings' => $data['findings'] ?? [],
                'requires_attention' => $data['requires_attention'] ?? false,
                'recommendations' => $data['recommendations'] ?? '',
                'confidence_score' => $data['confidence_score'] ?? 0,
            ]);

            $this->result = $healthAnalysis;
            $this->analysesCount++; // Actualizar contador
            $this->reset(['photo', 'selectedPetId']);

        } catch (\Exception $e) {
            $this->error = 'Error: ' . $e->getMessage();
        } finally {
            $this->analyzing = false;
        }
    }

    public function resetAnalysis()
    {
        $this->reset(['result', 'error', 'photo', 'selectedPetId']);
    }

    public function render()
    {
        return view('livewire.dashboard.health-analyzer');
    }
}
