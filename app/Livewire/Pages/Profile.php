<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Component;

class Profile extends Component
{
    use \Livewire\WithPagination;

    public $user;
    public $profile;
    public $activeTab = 'about'; 
    
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
    
    // Contact Modal
    public $showContactModal = false;

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
        if ($this->user->hasRole('veterinarian')) $this->profile = $this->user->veterinarianProfile;
        elseif ($this->user->hasRole('walker')) $this->profile = $this->user->walkerProfile;
        elseif ($this->user->hasRole('groomer')) $this->profile = $this->user->groomerProfile;
        elseif ($this->user->hasRole('hotel')) $this->profile = $this->user->hotelProfile;
        elseif ($this->user->hasRole('shelter')) $this->profile = $this->user->shelterProfile;
        elseif ($this->user->hasRole('trainer')) $this->profile = $this->user->trainerProfile;
        elseif ($this->user->hasRole('pet_sitter')) $this->profile = $this->user->petSitterProfile;
        elseif ($this->user->hasRole('pet_taxi')) $this->profile = $this->user->petTaxiProfile;
        elseif ($this->user->hasRole('pet_photographer')) $this->profile = $this->user->petPhotographerProfile;
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

        $this->validate([
            'appointmentDate' => 'required|date|after:today',
            'appointmentTime' => 'required',
            'appointmentNotes' => 'nullable|string|max:500',
        ]);

        $scheduledAt = $this->appointmentDate . ' ' . $this->appointmentTime . ':00';

        \App\Models\Appointment::create([
            'client_id' => auth()->id(),
            'provider_id' => $this->user->id,
            'scheduled_at' => $scheduledAt,
            'status' => 'pending',
            'notes' => $this->appointmentNotes,
        ]);

        $this->showBookingModal = false;
        $this->reset(['appointmentDate', 'appointmentTime', 'appointmentNotes']);
        
        session()->flash('message', 'Solicitud de cita enviada. El proveedor confirmará pronto.');
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
