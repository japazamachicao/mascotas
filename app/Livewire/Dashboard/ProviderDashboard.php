<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProviderDashboard extends Component
{
    use \Livewire\WithFileUploads;

    public $user;
    public $profile;
    
    // Campos editables (Comunes)
    public $bio;
    public $website_url;
    public $facebook_url;
    public $instagram_url;
    public $tiktok_url;
    public $whatsapp_number;
    public $district_id; // Ubicación para filtros
    
    // Específicos Veterinario
    public $license_number;
    public $address;
    public $allows_home_visits;
    public $emergency_24h;

    // Específicos Paseador
    public $hourly_rate;

    // Específicos Hotel
    public $capacity;
    public $has_transport;
    public $cage_free;
    public $check_in_time;
    public $check_out_time;

    // Específicos Albergue
    public $accepting_adoptions;
    public $accepting_volunteers;
    public $accepting_donations;
    public $donation_info;

    // Nuevos Roles
    public $methodology; // Trainer
    public $certification; // Trainer
    public $housing_type; // Sitter
    public $has_yard; // Sitter
    public $vehicle_type; // Taxi
    public $has_ac; // Taxi
    public $provides_crate; // Taxi
    public $specialty; // Photographer
    public $has_studio; // Photographer
    
    public $profile_photo; // Foto de Perfil (User)
    public $verification_document; // Documento (Provider)
    public $verification_status; // Estado (is_verified)
    
    public $availability = []; // Todos (Horarios)

    // Ubigeo (Selectores)
    public $departments = [];
    public $provinces = [];
    public $districts = [];
    
    public $selectedDepartment = null;
    public $selectedProvince = null;

    // Portafolio
    public $newImage;
    public $imageTitle;
    public $portfolioImages = [];

    protected function rules()
    {
        $rules = [
            'bio' => 'nullable|string|max:1000',
            'website_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string|max:20',
            'newImage' => 'nullable|image|max:10240', // 10MB
            'district_id' => 'required|exists:districts,id',
            'availability' => 'array',
            'availability.*.active' => 'boolean',
            'availability.*.start' => 'required_if:availability.*.active,true',
            'availability.*.end' => 'required_if:availability.*.active,true',
        ];

        if ($this->user->hasRole('veterinarian')) {
            $rules['license_number'] = 'nullable|string|max:50';
            $rules['address'] = 'nullable|string|max:255';
            $rules['allows_home_visits'] = 'boolean';
            $rules['emergency_24h'] = 'boolean';
        } elseif ($this->user->hasRole('walker')) {
            $rules['hourly_rate'] = 'nullable|numeric|min:0';
        } elseif ($this->user->hasRole('groomer')) {
             $rules['address'] = 'nullable|string|max:255';
             $rules['allows_home_visits'] = 'boolean';
        } elseif ($this->user->hasRole('hotel')) {
            $rules['address'] = 'nullable|string|max:255';
            $rules['capacity'] = 'nullable|integer|min:1';
            $rules['check_in_time'] = 'nullable';
            $rules['check_out_time'] = 'nullable';
            $rules['cage_free'] = 'boolean';
            $rules['has_transport'] = 'boolean';
        } elseif ($this->user->hasRole('shelter')) {
            $rules['address'] = 'nullable|string|max:255';
            $rules['donation_info'] = 'nullable|string|max:1000';
            $rules['accepting_adoptions'] = 'boolean';
            $rules['accepting_volunteers'] = 'boolean';
            $rules['accepting_donations'] = 'boolean';
        } elseif ($this->user->hasRole('trainer')) {
            $rules['methodology'] = 'nullable|string|max:255';
            $rules['certification'] = 'nullable|string|max:255';
            $rules['allows_home_visits'] = 'boolean';
        } elseif ($this->user->hasRole('pet_sitter')) {
            $rules['housing_type'] = 'nullable|string|max:255';
            $rules['has_yard'] = 'boolean';
            $rules['allows_home_visits'] = 'boolean';
        } elseif ($this->user->hasRole('pet_taxi')) {
            $rules['vehicle_type'] = 'nullable|string|max:255';
            $rules['has_ac'] = 'boolean';
            $rules['provides_crate'] = 'boolean';
        } elseif ($this->user->hasRole('pet_photographer')) {
            $rules['specialty'] = 'nullable|string|max:255';
            $rules['has_studio'] = 'boolean';
        }

        return $rules;
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadProfile(); // Carga datos del perfil
        $this->loadUbigeo(); // Carga lógica de departamentos/provincias
        $this->loadPortfolio();
    }

    public function loadProfile()
    {
        if ($this->user->hasRole('veterinarian')) {
            $this->profile = $this->user->veterinarianProfile;
            $this->bio = $this->profile->bio;
            $this->address = $this->profile->address;
            $this->allows_home_visits = $this->profile->allows_home_visits;
            $this->license_number = $this->profile->license_number;
            $this->emergency_24h = $this->profile->emergency_24h;
            $this->district_id = $this->profile->district_id;
        } elseif ($this->user->hasRole('walker')) {
            $this->profile = $this->user->walkerProfile;
            $this->bio = $this->profile->experience;
            $this->hourly_rate = $this->profile->hourly_rate;
            $this->district_id = $this->profile->district_id;
        } elseif ($this->user->hasRole('groomer')) {
            $this->profile = $this->user->groomerProfile;
            $this->bio = $this->profile->bio;
            $this->address = $this->profile->address;
            $this->allows_home_visits = $this->profile->allows_home_visits;
            $this->district_id = $this->profile->district_id;
        } elseif ($this->user->hasRole('hotel')) {
            $this->profile = $this->user->hotelProfile;
            $this->bio = $this->profile->bio;
            $this->address = $this->profile->address;
            $this->district_id = $this->profile->district_id;
            $this->capacity = $this->profile->capacity;
            $this->has_transport = $this->profile->has_transport;
            $this->cage_free = $this->profile->cage_free;
            $this->check_in_time = $this->profile->check_in_time;
            $this->check_out_time = $this->profile->check_out_time;
        } elseif ($this->user->hasRole('shelter')) {
            $this->profile = $this->user->shelterProfile;
            $this->bio = $this->profile->bio;
            $this->address = $this->profile->address;
            $this->district_id = $this->profile->district_id;
            $this->capacity = $this->profile->capacity;
            $this->accepting_adoptions = $this->profile->accepting_adoptions;
            $this->accepting_volunteers = $this->profile->accepting_volunteers;
            $this->accepting_donations = $this->profile->accepting_donations;
            $this->donation_info = $this->profile->donation_info;
        } elseif ($this->user->hasRole('trainer')) {
            $this->profile = $this->user->trainerProfile;
            $this->bio = $this->profile->bio;
            $this->district_id = $this->profile->district_id;
            $this->allows_home_visits = $this->profile->allows_home_visits;
            $this->methodology = $this->profile->methodology;
            $this->certification = $this->profile->certification;
        } elseif ($this->user->hasRole('pet_sitter')) {
            $this->profile = $this->user->petSitterProfile;
            $this->bio = $this->profile->bio;
            $this->district_id = $this->profile->district_id;
            $this->allows_home_visits = $this->profile->allows_home_visits;
            $this->housing_type = $this->profile->housing_type;
            $this->has_yard = $this->profile->has_yard;
        } elseif ($this->user->hasRole('pet_taxi')) {
            $this->profile = $this->user->petTaxiProfile;
            $this->bio = $this->profile->bio;
            $this->district_id = $this->profile->district_id;
            $this->vehicle_type = $this->profile->vehicle_type;
            $this->has_ac = $this->profile->has_ac;
            $this->provides_crate = $this->profile->provides_crate;
        } elseif ($this->user->hasRole('pet_photographer')) {
            $this->profile = $this->user->petPhotographerProfile;
            $this->bio = $this->profile->bio;
            $this->district_id = $this->profile->district_id;
            $this->specialty = $this->profile->specialty;
            $this->has_studio = $this->profile->has_studio;
        } else {
            return redirect()->route('dashboard');
        }
        
        // Cargar estado de visualización
        $this->verification_status = $this->profile->is_verified;

        // Cargar redes sociales (comunes)
        $this->website_url = $this->profile->website_url;
        $this->facebook_url = $this->profile->facebook_url;
        $this->instagram_url = $this->profile->instagram_url;
        $this->tiktok_url = $this->profile->tiktok_url;
        $this->whatsapp_number = $this->profile->whatsapp_number;
        
        // Cargar o inicializar horarios
        $this->availability = $this->profile->availability ?? $this->getDefaultAvailability();
    }

    public function loadUbigeo()
    {
        $this->departments = \App\Models\Department::all();
        
        if ($this->district_id) {
            $district = \App\Models\District::find($this->district_id);
            if ($district) {
                $this->selectedDepartment = $district->department_id;
                $this->selectedProvince = $district->province_id;
                
                // Cargar listas dependientes
                $this->provinces = \App\Models\Province::where('department_id', $this->selectedDepartment)->get();
                $this->districts = \App\Models\District::where('province_id', $this->selectedProvince)->get();
            }
        }
    }

    // Livewire Hooks para actualizar selectores
    public function updatedSelectedDepartment($department_id)
    {
        $this->provinces = \App\Models\Province::where('department_id', $department_id)->get();
        $this->districts = [];
        $this->selectedProvince = null;
        $this->district_id = null;
    }

    public function updatedSelectedProvince($province_id)
    {
        $this->districts = \App\Models\District::where('province_id', $province_id)->get();
        $this->district_id = null;
    }

    public function getDefaultAvailability()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $schedule = [];
        foreach ($days as $day) {
            $schedule[$day] = [
                'active' => in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']),
                'start' => '09:00',
                'end' => '18:00'
            ];
        }
        return $schedule;
    }

    public function loadPortfolio()
    {
        $this->portfolioImages = $this->user->portfolio()->latest()->get();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'website_url' => $this->website_url,
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'tiktok_url' => $this->tiktok_url,
            'whatsapp_number' => $this->whatsapp_number,
            'availability' => $this->availability,
            'district_id' => $this->district_id, // Guardar ubicación para filtros
        ];

        if ($this->user->hasRole('veterinarian')) {
            $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'address' => $this->address,
                'allows_home_visits' => $this->allows_home_visits,
                'license_number' => $this->license_number,
                'emergency_24h' => $this->emergency_24h,
            ]));
        } elseif ($this->user->hasRole('walker')) {
            $this->profile->update(array_merge($data, [
                'experience' => $this->bio,
                'hourly_rate' => $this->hourly_rate,
            ]));
        } elseif ($this->user->hasRole('groomer')) {
             $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'address' => $this->address,
                'allows_home_visits' => $this->allows_home_visits,
            ]));
        } elseif ($this->user->hasRole('hotel')) {
            $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'address' => $this->address,
                'capacity' => $this->capacity,
                'has_transport' => $this->has_transport,
                'cage_free' => $this->cage_free,
                'check_in_time' => $this->check_in_time,
                'check_out_time' => $this->check_out_time,
            ]));
        } elseif ($this->user->hasRole('shelter')) {
            $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'address' => $this->address,
                'capacity' => $this->capacity,
                'accepting_adoptions' => $this->accepting_adoptions,
                'accepting_volunteers' => $this->accepting_volunteers,
                'accepting_donations' => $this->accepting_donations,
                'donation_info' => $this->donation_info,
            ]));
        } elseif ($this->user->hasRole('trainer')) {
            $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'allows_home_visits' => $this->allows_home_visits,
                'methodology' => $this->methodology,
                'certification' => $this->certification,
            ]));
        } elseif ($this->user->hasRole('pet_sitter')) {
            $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'allows_home_visits' => $this->allows_home_visits,
                'housing_type' => $this->housing_type,
                'has_yard' => $this->has_yard,
            ]));
        } elseif ($this->user->hasRole('pet_taxi')) {
             $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'vehicle_type' => $this->vehicle_type,
                'has_ac' => $this->has_ac,
                'provides_crate' => $this->provides_crate,
            ]));
        } elseif ($this->user->hasRole('pet_photographer')) {
             $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'specialty' => $this->specialty,
                'has_studio' => $this->has_studio,
            ]));
        }
        
        if ($this->profile_photo) {
            $this->validate(['profile_photo' => 'image|max:5120']); // 5MB
            $path = $this->profile_photo->store('profile-photos', config('filesystems.default'));
            $this->profile_photo = null; // Reseteamos para que desaparezca el botón y se muestre la guardada
        }

        // La lógica de documento ahora se maneja principalmente en uploadVerificationDocument
        // pero lo mantenemos aquí por si acaso el usuario usa el botón general
        if ($this->verification_document) {
           $this->uploadVerificationDocument();
        }

        session()->flash('message', 'Perfil actualizado correctamente.');
    }

    public function uploadVerificationDocument()
    {
        if (!$this->verification_document) {
            return;
        }

        // Verificar límite de intentos (Máx 2)
        $attempts = $this->profile->verification_attempts ?? 0;
        if ($attempts >= 2) {
            $this->addError('verification_document', 'Has alcanzado el límite de 2 intentos. Espera la evaluación del administrador.');
            return;
        }

        // Validar Archivo: PDF o Imágenes, Máximo 10MB (10240 KB)
        $this->validate([
            'verification_document' => 'file|mimes:pdf,jpg,jpeg,png|max:10240'
        ], [
            'verification_document.mimes' => 'El documento debe ser PDF, JPG o PNG.',
            'verification_document.max' => 'El documento no debe pesar más de 10MB.',
        ]);

        $path = $this->verification_document->store('verification-docs', config('filesystems.default'));
        
        $this->profile->update([
            'verification_document_path' => $path,
            'verification_attempts' => $attempts + 1,
        ]);
        
        // Limpiar input
        $this->verification_document = null;
        
        session()->flash('message', 'Documento enviado correctamente para revisión.');
    }

    public function uploadImage()
    {
        $this->validate([
            'newImage' => 'required|image|max:10240', // 10MB
        ]);

        $path = $this->newImage->store('portfolio', config('filesystems.default'));

        $this->user->portfolio()->create([
            'image_path' => $path,
            'title' => $this->imageTitle,
        ]);

        $this->reset(['newImage', 'imageTitle']);
        $this->loadPortfolio();
        session()->flash('message', 'Imagen agregada al portafolio.');
    }

    public function deleteImage($id)
    {
        $image = $this->user->portfolio()->find($id);
        if ($image) {
            // Eliminar archivo físico (opcional, recomendado)
            // Storage::disk(env('FILESYSTEM_DISK', 'public'))->delete($image->image_path);
            $image->delete();
            $this->loadPortfolio();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.provider-dashboard')->layout('components.layouts.app');
    }
}
