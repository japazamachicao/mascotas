<?php

namespace App\Livewire\Demo;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\AIVisionService;
use Illuminate\Support\Facades\Storage;

class DemoHealthAnalyzer extends Component
{
    use WithFileUploads;

    public $photo;
    public $analysisType = 'feces';
    public $analyzing = false;
    public $result = null;
    public $error = null;
    public $showRegistrationModal = false;
    public $demoUsed = false;

    public function mount()
    {
        // Verificar si ya usó la demo
        $this->demoUsed = session()->has('demo_health_used');
    }

    public function analyze()
    {
        // Si ya usó la demo, mostrar modal de registro
        if ($this->demoUsed) {
            $this->showRegistrationModal = true;
            return;
        }

        $this->validate([
            'photo' => 'required|image|max:10240',
            'analysisType' => 'required|in:feces,urine,skin',
        ]);

        $this->analyzing = true;
        $this->error = null;

        try {
            // Guardar imagen temporalmente
            $path = $this->photo->store('temp', 'public');

            $aiService = app(AIVisionService::class);

            // Análisis según tipo
            if ($this->analysisType === 'feces') {
                $analysis = $aiService->analyzeFeces('public/' . $path);
            } elseif ($this->analysisType === 'urine') {
                $analysis = $aiService->analyzeUrine('public/' . $path);
            } elseif ($this->analysisType === 'skin') {
                $analysis = $aiService->analyzeSkin('public/' . $path);
            } else {
                throw new \Exception('Tipo de análisis no válido');
            }

            // Verificar si hay error
            if (!$analysis['success']) {
                throw new \Exception($analysis['error'] ?? 'Error desconocido');
            }

            $data = $analysis['data'];

            $this->result = (object) [
                'findings' => $data['findings'] ?? 'No se detectaron hallazgos específicos',
                'requires_attention' => $data['requires_attention'] ?? false,
                'recommendations' => $data['recommendations'] ?? 'Continuar monitoreo regular',
                'confidence_score' => $data['confidence_score'] ?? 0.8,
                'analysis_type' => $this->analysisType,
                'image_path' => $path,
            ];

            // Marcar demo como usada
            session()->put('demo_health_used', true);
            $this->demoUsed = true;

            // Mostrar modal después del resultado
            $this->showRegistrationModal = true;

            // Limpiar imagen temporal después de 1 hora
            Storage::disk('public')->delete($path);

        } catch (\Exception $e) {
            \Log::error('Demo Health Analysis Error: ' . $e->getMessage());
            
            // Mensajes amigables según el tipo de error
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, '429') || str_contains($errorMessage, 'quota') || str_contains($errorMessage, 'RESOURCE_EXHAUSTED')) {
                $this->error = '⏱️ Hemos alcanzado el límite de análisis por hoy. Por favor, intenta más tarde o regístrate para acceso prioritario.';
            } elseif (str_contains($errorMessage, 'API')) {
                $this->error = '🔧 Servicio temporalmente no disponible. Por favor, intenta en unos minutos.';
            } elseif (str_contains($errorMessage, 'imagen') || str_contains($errorMessage, 'raza')) {
                $this->error = '📸 ' . $errorMessage;
            } else {
                $this->error = '❌ No pudimos procesar tu solicitud. Por favor, intenta con otra imagen o regístrate para soporte prioritario.';
            }
        } finally {
            $this->analyzing = false;
        }
    }

    public function render()
    {
        return view('livewire.demo.demo-health-analyzer')
            ->layout('components.layouts.app');
    }
}
