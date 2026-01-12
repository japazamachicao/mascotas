<?php

namespace App\Livewire\Dashboard;

use App\Models\HealthAnalysis;
use App\Models\CarePlan;
use Livewire\Component;
use Livewire\WithPagination;

class HealthHistory extends Component
{
    use WithPagination;

    public $filterType = 'all'; // 'all', 'feces', 'urine', 'care_plan'
    public $selectedItem = null;
    public $showModal = false;

    public function filterBy($type)
    {
        $this->filterType = $type;
        $this->resetPage();
    }

    public function viewDetails($id, $type)
    {
        if ($type === 'care_plan') {
            $this->selectedItem = CarePlan::findOrFail($id);
            $this->selectedItem->type = 'care_plan';
        } else {
            $this->selectedItem = HealthAnalysis::with('pet')->findOrFail($id);
            $this->selectedItem->type = 'health_analysis';
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedItem = null;
    }

    public function render()
    {
        // Obtener análisis de salud
        $healthAnalyses = HealthAnalysis::with('pet')
            ->where('user_id', auth()->id())
            ->when($this->filterType !== 'all' && $this->filterType !== 'care_plan', function ($query) {
                $query->where('analysis_type', $this->filterType);
            })
            ->when($this->filterType === 'care_plan', function ($query) {
                return $query->whereRaw('1 = 0'); // No mostrar análisis si el filtro es care_plan
            })
            ->latest()
            ->get();

        // Obtener planes de cuidado
        $carePlans = CarePlan::with('pet')
            ->where('user_id', auth()->id())
            ->when($this->filterType !== 'all' && $this->filterType !== 'care_plan', function ($query) {
                return $query->whereRaw('1 = 0'); // No mostrar planes si el filtro no es 'all' o 'care_plan'
            })
            ->latest()
            ->get();

        // Combinar y ordenar por fecha
        $allItems = collect();
        
        if ($this->filterType === 'all' || $this->filterType !== 'care_plan') {
            foreach ($healthAnalyses as $analysis) {
                $allItems->push([
                    'id' => $analysis->id,
                    'type' => 'health_analysis',
                    'item' => $analysis,
                    'created_at' => $analysis->created_at,
                ]);
            }
        }
        
        if ($this->filterType === 'all' || $this->filterType === 'care_plan') {
            foreach ($carePlans as $plan) {
                $allItems->push([
                    'id' => $plan->id,
                    'type' => 'care_plan',
                    'item' => $plan,
                    'created_at' => $plan->created_at,
                ]);
            }
        }

        $allItems = $allItems->sortByDesc('created_at')->take(50); // Limitar a 50 items

        return view('livewire.dashboard.health-history', [
            'items' => $allItems,
        ]);
    }
}
