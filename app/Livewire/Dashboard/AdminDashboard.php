<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class AdminDashboard extends Component
{
    use WithPagination;

    public $activeTab = 'stats'; // stats, verifications, users
    public $search = '';
    public $filterRole = 'all';

    protected $queryString = [
        'activeTab' => ['except' => 'stats'],
        'search' => ['except' => ''],
        'filterRole' => ['except' => 'all'],
    ];

    public function mount()
    {
        if (!auth()->check() || !auth()->user()->hasRole('super-admin')) {
            abort(403, 'Acceso no autorizado.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function approveVerification($profileId, $role)
    {
        $classes = $this->getProfileClasses();
        
        if (isset($classes[$role])) {
            $profile = $classes[$role]::findOrFail($profileId);
            $profile->update(['is_verified' => true]);
            session()->flash('message', 'Proveedor verificado con éxito.');
        }
    }

    public function rejectVerification($profileId, $role)
    {
        $classes = $this->getProfileClasses();
        
        if (isset($classes[$role])) {
            $profile = $classes[$role]::findOrFail($profileId);
            
            if ($profile->verification_document_path) {
                \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))
                    ->delete($profile->verification_document_path);
            }
            
            $profile->update([
                'is_verified' => false,
                'verification_document_path' => null,
            ]);
            
            session()->flash('message', 'Documento rechazado. El proveedor podrá subir un nuevo documento.');
        }
    }

    private function getProfileClasses()
    {
        return [
            'veterinarian' => \App\Models\Veterinarian::class,
            'walker' => \App\Models\Walker::class,
            'groomer' => \App\Models\Groomer::class,
            'hotel' => \App\Models\PetHotel::class,
            'shelter' => \App\Models\Shelter::class,
            'trainer' => \App\Models\Trainer::class,
            'pet_sitter' => \App\Models\PetSitter::class,
            'pet_taxi' => \App\Models\PetTaxi::class,
            'pet_photographer' => \App\Models\PetPhotographer::class,
        ];
    }

    public function getPendingVerificationsProperty()
    {
        $pending = [];
        $classes = $this->getProfileClasses();
        
        $labels = [
            'veterinarian' => 'Veterinario',
            'walker' => 'Paseador',
            'groomer' => 'Estilista',
            'hotel' => 'Hotel Canino',
            'shelter' => 'Albergue',
            'trainer' => 'Adiestrador',
            'pet_sitter' => 'Cuidador',
            'pet_taxi' => 'Transporte',
            'pet_photographer' => 'Fotógrafo',
        ];

        foreach ($classes as $role => $class) {
            $records = $class::with('user')
                ->where('is_verified', false)
                ->whereNotNull('verification_document_path')
                ->get();
                
            foreach ($records as $record) {
                if ($record->user) {
                    $pending[] = [
                        'id' => $record->id,
                        'role' => $role,
                        'label' => $labels[$role],
                        'user_name' => $record->user->name,
                        'user_email' => $record->user->email,
                        'document_path' => $record->verification_document_path,
                        'attempts' => $record->verification_attempts,
                    ];
                }
            }
        }
        
        return $pending;
    }

    public function render()
    {
        $kpis = [
            'totalUsers' => User::count(),
            'totalClients' => User::role('client')->count(),
            'totalProviders' => User::whereHas('roles', function($q) {
                $q->where('name', '!=', 'client')->where('name', '!=', 'super-admin');
            })->count(),
            'totalAppointments' => Appointment::count(),
            'appointments_pending' => Appointment::where('status', 'pending')->count(),
            'appointments_confirmed' => Appointment::where('status', 'confirmed')->count(),
            'appointments_completed' => Appointment::where('status', 'completed')->count(),
            'appointments_cancelled' => Appointment::where('status', 'cancelled')->count(),
            'totalReviews' => Review::count(),
            'averageRating' => round(Review::avg('rating') ?? 0, 1),
            
            // Categorías de proveedores
            'vets' => \App\Models\Veterinarian::count(),
            'walkers' => \App\Models\Walker::count(),
            'groomers' => \App\Models\Groomer::count(),
            'hotels' => \App\Models\PetHotel::count(),
            'shelters' => \App\Models\Shelter::count(),
            'trainers' => \App\Models\Trainer::count(),
            'sitters' => \App\Models\PetSitter::count(),
            'taxis' => \App\Models\PetTaxi::count(),
            'photographers' => \App\Models\PetPhotographer::count(),
        ];

        $users = User::with('roles')
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole !== 'all', function ($query) {
                return $query->role($this->filterRole);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.dashboard.admin-dashboard', [
            'kpis' => $kpis,
            'users' => $users,
            'pendingVerifications' => $this->pendingVerifications,
        ])->layout('components.layouts.app');
    }
}
