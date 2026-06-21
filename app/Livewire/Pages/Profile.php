<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Component;

class Profile extends Component
{
    use \Livewire\WithPagination;

    public $user;
    public $profile;
    public $allProfiles = [];
    public $activeTab = 'about'; 
    public $selectedRole;
    public $providerLevel = []; 
    
    // Review Form
    public $rating = 5;
    public $comment = '';
    public $sortBy = 'newest'; // newest, oldest, highest, lowest

    // Portfolio Modal
    public $selectedImage = null;

    // Appointment Form
    public $showBookingModal = false;
    public $appointmentDate;
    public $appointmentTime;
    public $appointmentNotes;
    public $selectedPetId = null;
    public $selectedServices = [];
    public $providerServices = [];
    public $totalPrice = 0.0;
    
    // Contact Modal
    public $showContactModal = false;

    // WhatsApp link tras agendar cita
    public $waLink = null;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:5|max:500',
    ];

    public function mount($id)
    {
        $this->user = User::with([
            'veterinarianProfile.district.province.department', 
            'walkerProfile.district.province.department',
            'groomerProfile.district.province.department',
            'hotelProfile.district.province.department',
            'shelterProfile.district.province.department',
            'trainerProfile.district.province.department',
            'petSitterProfile.district.province.department',
            'petTaxiProfile.district.province.department',
            'petPhotographerProfile.district.province.department',
            'portfolio',
        ])->findOrFail($id);

        $this->detectProfile();
        
        if (!$this->profile) {
            abort(404, 'Perfil de proveedor no encontrado.');
        }

        $this->allProfiles = $this->user->getActiveProviderProfiles();
        $this->providerServices = $this->user->services;

        if (\Illuminate\Support\Facades\Auth::check()) {
            $this->isFavorite = \Illuminate\Support\Facades\Auth::user()->favoriteProviders()->where('provider_id', $this->user->id)->exists();
        }
    }

    public $isFavorite = false;

    public function toggleFavorite()
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login');
        }

        $currentUser = \Illuminate\Support\Facades\Auth::user();

        if ($this->isFavorite) {
            $currentUser->favoriteProviders()->detach($this->user->id);
            $this->isFavorite = false;
        } else {
            $currentUser->favoriteProviders()->attach($this->user->id);
            $this->isFavorite = true;
        }
    }

    public function getReviewsProperty()
    {
        $query = $this->user->reviewsReceived()->with('user');

        switch ($this->sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'highest':
                $query->orderByDesc('rating');
                break;
            case 'lowest':
                $query->orderBy('rating');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        return $query->paginate(5);
    }

    public function getAverageRatingProperty()
    {
        // Cachear esto sería ideal en producción, pero por ahora en tiempo real está bien
        return round($this->user->reviewsReceived()->avg('rating'), 1);
    }

    public function getTotalReviewsProperty()
    {
        return $this->user->reviewsReceived()->count();
    }    

    public function detectProfile()
    {
        $role = $this->selectedRole ?: request()->query('role');
 
        $roleMap = [
            'veterinarian' => 'veterinarianProfile',
            'walker' => 'walkerProfile',
            'groomer' => 'groomerProfile',
            'hotel' => 'hotelProfile',
            'shelter' => 'shelterProfile',
            'trainer' => 'trainerProfile',
            'pet_sitter' => 'petSitterProfile',
            'pet_taxi' => 'petTaxiProfile',
            'pet_photographer' => 'petPhotographerProfile',
        ];

        if ($role && isset($roleMap[$role]) && $this->user->hasRole($role)) {
            $relation = $roleMap[$role];
            $this->profile = $this->user->$relation;
            $this->selectedRole = $role;
            $this->providerLevel = $this->user->getProfileLevel($this->profile);
            return;
        }

        foreach ($roleMap as $roleName => $relationName) {
            if ($this->user->hasRole($roleName) && $this->user->$relationName) {
                $this->profile = $this->user->$relationName;
                $this->selectedRole = $roleName;
                $this->providerLevel = $this->user->getProfileLevel($this->profile);
                return;
            }
        }
        $this->providerLevel = [];
    }

    public function switchProfileRole($role)
    {
        $this->selectedRole = $role;
        $this->detectProfile();
        
        $this->selectedServices = [];
        $this->totalPrice = $this->profile->price_from ?? 0.0;
        
        if ($this->profile && $this->profile->latitude && $this->profile->longitude) {
            $this->dispatch('profile-role-changed', latitude: $this->profile->latitude, longitude: $this->profile->longitude);
        }
    }

    public function saveReview()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->id() === $this->user->id) {
            $this->addError('review', 'No puedes reseñarte a ti mismo.');
            return;
        }

        // Validar que el usuario haya completado al menos un servicio con este proveedor antes de poder reseñar
        $hasCompletedAppointment = \App\Models\Appointment::where('client_id', auth()->id())
            ->where('provider_id', $this->user->id)
            ->where('status', 'completed')
            ->exists();
        if (!$hasCompletedAppointment) {
            $this->addError('review', 'Debes haber completado al menos una cita con este proveedor para poder dejar una reseña.');
            return;
        }

        // Evitar reseñas duplicadas del mismo usuario al mismo proveedor
        $existingReview = \App\Models\Review::where('user_id', auth()->id())
            ->where('provider_id', $this->user->id)
            ->exists();
        if ($existingReview) {
            $this->addError('review', 'Ya has dejado una reseña para este proveedor.');
            return;
        }

        $this->validate();

        \App\Models\Review::create([
            'user_id' => auth()->id(),
            'provider_id' => $this->user->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->reset(['rating', 'comment']);
        
        session()->flash('message', '¡Gracias por tu opinión!');
    }

    public function bookAppointment()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $hasServices = $this->user->services()->exists();

        $validationRules = [
            'appointmentDate' => 'required|date|after:today',
            'appointmentTime' => 'required',
            'selectedPetId' => 'required|exists:pets,id,user_id,' . auth()->id(),
            'appointmentNotes' => 'nullable|string|max:500',
        ];

        if ($hasServices) {
            $validationRules['selectedServices'] = 'required|array|min:1';
            $validationRules['selectedServices.*'] = 'exists:provider_services,id,user_id,' . $this->user->id;
        }

        $this->validate($validationRules, [
            'selectedServices.required' => 'Debes seleccionar al menos un servicio del catálogo.',
            'selectedServices.min' => 'Debes seleccionar al menos un servicio del catálogo.',
        ]);

        // Validar si la fecha seleccionada está bloqueada por el proveedor
        $isBlocked = \App\Models\BlockedDate::where('provider_id', $this->user->id)
            ->whereDate('blocked_date', $this->appointmentDate)
            ->exists();

        if ($isBlocked) {
            $this->addError('appointmentDate', 'El proveedor ha bloqueado esta fecha y no está disponible.');
            return;
        }

        $scheduledAt = $this->appointmentDate . ' ' . $this->appointmentTime . ':00';

        $appointment = \App\Models\Appointment::create([
            'client_id' => auth()->id(),
            'provider_id' => $this->user->id,
            'pet_id' => $this->selectedPetId,
            'scheduled_at' => $scheduledAt,
            'status' => 'pending',
            'notes' => $this->appointmentNotes,
        ]);

        // Enviar notificación al proveedor
        $this->user->notify(new \App\Notifications\AppointmentBooked($appointment));

        if ($hasServices) {
            foreach ($this->selectedServices as $serviceId) {
                $service = \App\Models\ProviderService::find($serviceId);
                if ($service) {
                    $appointment->services()->attach($serviceId, [
                        'price_at_booking' => $service->price,
                    ]);
                }
            }
        }

        $amount = $hasServices 
            ? \App\Models\ProviderService::whereIn('id', $this->selectedServices)->sum('price')
            : ($this->profile->price_from ?? 0.0);

        \App\Models\Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => $amount,
            'payment_method' => 'yape',
            'status' => 'pending',
        ]);

        // Generar link de WhatsApp al proveedor para que el cliente pueda confirmar directamente
        $whatsapp = $this->profile->whatsapp_number ?? null;
        $waLink = null;
        if ($whatsapp) {
            $phone = preg_replace('/\D/', '', $whatsapp);
            if (strlen($phone) === 9) {
                $phone = '51' . $phone; // Añadir código Perú
            }
            $date = \Carbon\Carbon::parse($scheduledAt)->format('d/m/Y H:i');
            $clientName = auth()->user()->name;
            $msg = urlencode("Hola {$this->user->name}, soy {$clientName}. Acabo de solicitar una cita para el {$date} a través de TodoPeludos.com. ¿Puedes confirmarme?");
            $waLink = "https://wa.me/{$phone}?text={$msg}";
        }

        $this->waLink = $waLink;
        $this->showBookingModal = false;
        $this->reset(['appointmentDate', 'appointmentTime', 'appointmentNotes', 'selectedPetId']);

        session()->flash('message', 'Solicitud enviada. ' . ($waLink ? 'Escríbele al proveedor por WhatsApp para confirmar.' : 'El proveedor confirmará pronto.'));
    }

    public function getPetsProperty()
    {
        return auth()->check() ? \App\Models\Pet::where('user_id', auth()->id())->get() : collect();
    }

    public function openBookingModal()
    {
        $this->selectedServices = [];
        $this->totalPrice = $this->profile->price_from ?? 0.0;
        $this->showBookingModal = true;
    }

    public function updatedSelectedServices()
    {
        $this->totalPrice = \App\Models\ProviderService::whereIn('id', $this->selectedServices)
            ->sum('price');
    }

    public function openContactModal()
    {
        $this->showContactModal = true;
    }

    public function closeContactModal()
    {
        $this->showContactModal = false;
    }

    public function openImage($path)
    {
        $this->selectedImage = $path;
    }

    public function closeImage()
    {
        $this->selectedImage = null;
    }

    public function render()
    {
        return view('livewire.pages.profile', [
            'reviews' => $this->reviews
        ]);
    }
}
