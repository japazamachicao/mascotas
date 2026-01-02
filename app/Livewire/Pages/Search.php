<?php

namespace App\Livewire\Pages;

use App\Models\Department;
use App\Models\District;
use App\Models\Province;
use App\Models\Veterinarian;
use App\Models\Walker;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    // Filtros
    public $serviceType = 'veterinarian'; // 'veterinarian' o 'walker'
    public $department_id = '';
    public $province_id = '';
    public $district_id = '';
    
    // Colecciones para los selects
    public $departments;
    public $provinces = [];
    public $districts = [];

    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        // Cargar Lima por defecto para que no se vea vacío el select (opcional)
        // $this->department_id = '15'; 
        // $this->updatedDepartmentId('15');
    }

    public function updatedDepartmentId($value)
    {
        $this->provinces = Province::where('department_id', $value)->orderBy('name')->get();
        $this->province_id = '';
        $this->districts = [];
        $this->district_id = '';
        $this->resetPage();
    }

    public function updatedProvinceId($value)
    {
        $this->districts = District::where('province_id', $value)->orderBy('name')->get();
        $this->district_id = '';
        $this->resetPage();
    }

    public function updatedServiceType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $results = [];

        if ($this->serviceType === 'veterinarian') {
            $query = Veterinarian::with(['user', 'district.province.department']);

            if ($this->district_id) {
                $query->where('district_id', $this->district_id);
            } elseif ($this->province_id) {
                $query->whereHas('district', function($q) {
                    $q->where('province_id', $this->province_id);
                });
            } elseif ($this->department_id) {
                $query->whereHas('district.province', function($q) {
                    $q->where('department_id', $this->department_id);
                });
            }

            $results = $query->paginate(12);

        } elseif ($this->serviceType === 'walker') {
            $query = Walker::with(['user', 'district.province.department']);

            if ($this->district_id) {
                $query->where('district_id', $this->district_id);
            } elseif ($this->province_id) {
                $query->whereHas('district', function($q) {
                    $q->where('province_id', $this->province_id);
                });
            } elseif ($this->department_id) {
                $query->whereHas('district.province', function($q) {
                    $q->where('department_id', $this->department_id);
                });
            }

            $results = $query->paginate(12);
        }

        return view('livewire.pages.search', [
            'results' => $results
        ]);
    }
}
