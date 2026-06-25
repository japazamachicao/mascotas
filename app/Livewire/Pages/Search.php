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
    
    // Filtros Específicos
    public $filterHomeVisits = false;
    public $filterHasTransport = false;
    public $filterCageFree = false;
    public $filterHasYard = false;
    public $filterHasAc = false;

    // Nuevos Filtros
    public $filterVerified = false;
    public $filter24h = false;

    // Filtros Generales
    public $search = '';
    public $sortBy = 'best_rated';
    public $showMap = false;

    // Colecciones para los selects
    public $departments;
    public $provinces = [];
    public $districts = [];
    public $favoriteIds = [];

    protected $queryString = [
        'serviceType', 'search', 'department_id', 'province_id', 'district_id', 'sortBy',
        'filterHomeVisits', 'filterHasTransport', 'filterCageFree', 'filterHasYard', 'filterHasAc',
        'filterVerified', 'filter24h', 'showMap'
    ];

    public function mount($serviceType = null, $districtName = null)
    {
        $this->departments = Department::orderBy('name')->get();
 
        if ($serviceType) {
            $validServices = ['veterinarian', 'walker', 'trainer', 'pet_sitter', 'groomer', 'pet_photographer', 'pet_taxi', 'pet_hotel'];
            if (in_array(strtolower($serviceType), $validServices)) {
                $this->serviceType = strtolower($serviceType);
            }
        }
 
        if ($districtName) {
            $normalizedName = str_replace('-', ' ', urldecode($districtName));
            $district = District::where('name', 'like', '%' . $normalizedName . '%')->first();
            if ($district) {
                $this->district_id = $district->id;
                $this->province_id = $district->province_id;
                $this->department_id = $district->department_id;
            }
        }
        
        if ($this->department_id) {
            $this->provinces = Province::where('department_id', $this->department_id)->orderBy('name')->get();
        }
        if ($this->province_id) {
            $this->districts = District::where('province_id', $this->province_id)->orderBy('name')->get();
        }
        
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
        $this->province_id = '';
        $this->district_id = '';
        $this->districts = [];
        
        if ($value) {
            $this->provinces = Province::where('department_id', $value)->orderBy('name')->get();
        } else {
            $this->provinces = [];
        }
    }

    public function updatedProvinceId($value)
    {
        $this->district_id = '';
        
        if ($value) {
            $this->districts = District::where('province_id', $value)->orderBy('name')->get();
        } else {
            $this->districts = [];
        }
    }

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

        $hasHourlyRate = \Illuminate\Support\Facades\Schema::hasColumn($tableName, 'hourly_rate');
        $hasPriceFrom = \Illuminate\Support\Facades\Schema::hasColumn($tableName, 'price_from');

        $query = $modelClass::query()
            ->with(['user.services', 'district.province.department'])
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

        // 1b. Filtros Generales Nuevos
        if ($this->filterVerified) {
            $query->where("{$tableName}.is_verified", true);
        }

        if ($this->filter24h && $this->serviceType === 'veterinarian') {
            $query->where('emergency_24h', true);
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
            case 'price_asc':
                if ($hasPriceFrom && $hasHourlyRate) {
                    $query->orderByRaw('COALESCE((SELECT AVG(price) FROM provider_services WHERE provider_services.user_id = users.id), price_from, hourly_rate, 999999) ASC');
                } elseif ($hasPriceFrom) {
                    $query->orderByRaw('COALESCE((SELECT AVG(price) FROM provider_services WHERE provider_services.user_id = users.id), price_from, 999999) ASC');
                } elseif ($hasHourlyRate) {
                    $query->orderByRaw('COALESCE((SELECT AVG(price) FROM provider_services WHERE provider_services.user_id = users.id), hourly_rate, 999999) ASC');
                } else {
                    $query->orderByRaw('COALESCE((SELECT AVG(price) FROM provider_services WHERE provider_services.user_id = users.id), 999999) ASC');
                }
                break;
            case 'price_desc':
                if ($hasPriceFrom && $hasHourlyRate) {
                    $query->orderByRaw('COALESCE((SELECT AVG(price) FROM provider_services WHERE provider_services.user_id = users.id), price_from, hourly_rate, 0) DESC');
                } elseif ($hasPriceFrom) {
                    $query->orderByRaw('COALESCE((SELECT AVG(price) FROM provider_services WHERE provider_services.user_id = users.id), price_from, 0) DESC');
                } elseif ($hasHourlyRate) {
                    $query->orderByRaw('COALESCE((SELECT AVG(price) FROM provider_services WHERE provider_services.user_id = users.id), hourly_rate, 0) DESC');
                } else {
                    $query->orderByRaw('COALESCE((SELECT AVG(price) FROM provider_services WHERE provider_services.user_id = users.id), 0) DESC');
                }
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

        $mapMarkers = collect($results->items())
            ->filter(function ($item) {
                return !empty($item->latitude) && !empty($item->longitude);
            })
            ->map(function ($item) {
                $serviceLabels = [
                    'veterinarian' => 'Veterinario',
                    'walker' => 'Paseador',
                    'trainer' => 'Adiestrador',
                    'pet_sitter' => 'Cuidador',
                    'groomer' => 'Estilista',
                    'pet_photographer' => 'Fotógrafo',
                    'pet_taxi' => 'Pet Taxi',
                    'pet_hotel' => 'Hospedaje',
                ];
                $rating = round($item->user->reviewsReceived()->avg('rating') ?? 0, 1);
                $ratingCount = $item->user->reviewsReceived()->count();
                $minPrice = $item->user->minServicePrice();
                $price = $minPrice > 0 ? $minPrice : ($item->hourly_rate ?? 0);
                $imageUrl = $item->user->profile_photo_path 
                    ? \Illuminate\Support\Facades\Storage::url($item->user->profile_photo_path) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($item->user->name) . '&background=0ea5e9&color=fff';
                $lvl = $item->user->getProfileLevel($item);

                return [
                    'id' => $item->id,
                    'name' => $item->user->name,
                    'lat' => (float)$item->latitude,
                    'lng' => (float)$item->longitude,
                    'service' => $serviceLabels[$this->serviceType] ?? 'Profesional',
                    'rating' => $rating,
                    'reviews_count' => $ratingCount,
                    'price' => $price > 0 ? 'S/ ' . number_format($price, 0) : 'A convenir',
                    'image' => $imageUrl,
                    'url' => $item->user->profileUrl($this->serviceType),
                    'level_badge' => !empty($lvl) ? $lvl['badge'] . ' ' . $lvl['label'] : null
                ];
            })
            ->values();

        $this->dispatch('markers-updated', markers: $mapMarkers);

        return view('livewire.pages.search', [
            'results' => $results,
            'mapMarkers' => $mapMarkers
        ]);
    }
}
