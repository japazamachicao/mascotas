<?php

namespace App\Livewire\Pages;

use App\Models\Department;
use App\Models\District;
use App\Models\Province;
use App\Models\Groomer;
use App\Models\PetHotel;
use App\Models\PetPhotographer;
use App\Models\PetSitter;
use App\Models\PetTaxi;
use App\Models\Trainer;
use App\Models\Veterinarian;
use App\Models\Walker;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    // Filtros
    public $serviceType = 'veterinarian'; // 'veterinarian', 'walker', 'trainer', 'pet_sitter', 'groomer', 'pet_photographer', 'pet_taxi', 'pet_hotel'
    public $department_id = '';
    public $province_id = '';
    public $district_id = '';
    
    // Colecciones para los selects
    // Filtros Específicos
    public $filterHomeVisits = false;
    public $filterHasTransport = false;
    public $filterCageFree = false;
    public $filterHasYard = false;
    public $filterHasAc = false;

    // Filtros Generales
    public $search = '';
    public $sortBy = 'best_rated';

    // Colecciones para los selects
    public $departments;
    public $provinces = [];
    public $districts = [];
    public $favoriteIds = [];

    protected $queryString = [
        'serviceType', 'search', 'department_id', 'province_id', 'district_id', 'sortBy',
        'filterHomeVisits', 'filterHasTransport', 'filterCageFree', 'filterHasYard', 'filterHasAc'
    ];

    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        if (\Illuminate\Support\Facades\Auth::check()) {
            $this->favoriteIds = \Illuminate\Support\Facades\Auth::user()->favoriteProviders()->pluck('users.id')->toArray();
        }
    }

    public function toggleFavorite($providerId)
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login');
        }

        $user = \Illuminate\Support\Facades\Auth::user();

        if (in_array($providerId, $this->favoriteIds)) {
            $user->favoriteProviders()->detach($providerId);
            $this->favoriteIds = array_diff($this->favoriteIds, [$providerId]);
        } else {
            $user->favoriteProviders()->attach($providerId);
            $this->favoriteIds[] = $providerId;
        }
    }
    
    // ...

    public function updatedDepartmentId($value)
    {
        // ...
    }

    // ...

    public function render()
    {
        // ... (Model mapping logic remains) ...
        
        $results = [];

        // Definir la clase del modelo según el tipo de servicio
        $modelMap = [
            'veterinarian' => Veterinarian::class,
            'walker' => Walker::class,
            'trainer' => Trainer::class,
            'pet_sitter' => PetSitter::class,
            'groomer' => Groomer::class,
            'pet_photographer' => PetPhotographer::class,
            'pet_taxi' => PetTaxi::class,
            'pet_hotel' => PetHotel::class,
        ];
        
        $modelClass = $modelMap[$this->serviceType] ?? Veterinarian::class;
        $instance = new $modelClass;
        $tableName = $instance->getTable();
        $foreignKey = 'user_id';

        $query = $modelClass::query()
            ->with(['user', 'district.province.department'])
            ->withAggregate('user', 'name')
            ->withAvg(['user as reviews_avg_rating' => function($query) {
                $query->select(\DB::raw('avg(rating)'))->from('reviews')->whereColumn('provider_id', 'users.id');
            }], 'rating');

        $query->join('users', 'users.id', '=', "{$tableName}.{$foreignKey}")
              ->select("{$tableName}.*");

        // 1. Filtro por Nombre
        if ($this->search) {
            $query->where('users.name', 'like', '%' . $this->search . '%');
        }

        // 2. Filtros Específicos
        if ($this->filterHomeVisits && in_array($this->serviceType, ['veterinarian', 'trainer', 'groomer', 'pet_sitter'])) {
            $query->where('allows_home_visits', true);
        }

        if ($this->filterHasTransport && in_array($this->serviceType, ['pet_hotel', 'pet_taxi'])) {
             if ($this->serviceType === 'pet_hotel') { // PetTaxi siempre tiene
                 $query->where('has_transport', true);
             }
        }

        if ($this->filterCageFree && $this->serviceType === 'pet_hotel') {
            $query->where('cage_free', true);
        }

        if ($this->filterHasYard && $this->serviceType === 'pet_sitter') {
            $query->where('has_yard', true);
        }
        
        if ($this->filterHasAc && $this->serviceType === 'pet_taxi') {
            $query->where('has_ac', true);
        }

        // 3. Filtros Geográficos
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

        // Ordenamiento
        switch ($this->sortBy) {
            case 'newest':
                $query->latest('created_at');
                break;
            case 'name_asc':
                $query->orderBy('users.name', 'asc');
                break;
            case 'best_rated':
            default:
                // Ordenar por el promedio de reviews calculado manual o via subquery
                // Como ya hicimos join con users, podemos usar subquery en order
                $query->orderByDesc(function ($query) {
                    $query->selectRaw('avg(rating)')
                          ->from('reviews')
                          ->whereColumn('reviews.provider_id', 'users.id');
                });
                break;
        }

        $results = $query->paginate(12);

        return view('livewire.pages.search', [
            'results' => $results
        ]);
    }
}
