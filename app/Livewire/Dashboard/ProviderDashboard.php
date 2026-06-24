<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ProviderDashboard extends Component
{
    use \Livewire\WithFileUploads;
    use WithPagination;

    public $user;
    public $profile;
    public $selectedRole;
    public $providerRoles = [
        'veterinarian' => 'Veterinario',
        'walker' => 'Paseador',
        'groomer' => 'Estilista / Baño',
        'hotel' => 'Hospedaje',
        'shelter' => 'Albergue',
        'trainer' => 'Adiestrador',
        'pet_sitter' => 'Cuidador',
        'pet_taxi' => 'Pet Taxi',
        'pet_photographer' => 'Fotógrafo',
    ];
    
    // Campos editables (Comunes)
    public $bio;
    public $price_from;
    public $website_url;
    public $facebook_url;
    public $instagram_url;
    public $tiktok_url;
    public $whatsapp_number;
    public $district_id; // Ubicación para filtros
    public $latitude;
    public $longitude;
    
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

    // Servicios (Catálogo)
    public $providerServices = [];
    public $serviceId;
    public $serviceName;
    public $serviceDescription;
    public $servicePrice;
    public $serviceDuration;
    public $isEditingService = false;

    // Métodos de Pago
    public $yape_number;
    public $plin_number;
    public $yape_qr;
    public $plin_qr;
    public $existingYapeQr;
    public $existingPlinQr;

    // Sección principal de navegación (top nav)
    #[Url(as: 'section')]
    public string $mainSection = 'panel'; // panel | appointments | calendar | reviews

    // Tab activo (sidebar navigation - solo en sección 'panel')
    public string $activeTab = 'profile';

    // Citas (gestionadas inline en la sección 'appointments')
    public string $filterStatus = 'pending';
    public string $searchQuery = '';
    public string $dateFilter = 'all'; // all | today | tomorrow | this_week | custom
    public ?string $startDate = null;
    public ?string $endDate = null;
    public $confirmingCancel = null;
    public $showAppointmentModal = false;
    public $selectedAppointmentData = null;
    public $editPaymentAmount = 0.0;
    public $editPaymentDescription = '';
    public array $extraCharges = [];
    public string $newChargeConcept = '';
    public $newChargeAmount = '';

    // Reseñas Recibidas y Respuestas
    public $receivedReviews = [];
    public $replyText = [];

    // Onboarding checklist
    public $completenessScore = 0;
    public $completenessChecklist = [];
    public $providerLevel = [];

    // Estadísticas
    public $totalEarnings = 0;
    public $monthlyEarnings = 0;
    public $acceptanceRate = 100;
    public $completedAppointmentsCount = 0;
    public $activeAppointmentsCount = 0;
    public $averageRating = 0.0;
    public $recentPayments = [];
    public $todayAppointments = [];
    public $pendingAppointmentsCount = 0;
    public $totalReviewsCount = 0;

    public function switchTab(string $tab): void
    {
        $allowed = ['profile', 'schedule', 'portfolio', 'services', 'payments_config', 'stats'];
        if (in_array($tab, $allowed)) {
            $this->activeTab = $tab;
            $this->mainSection = 'panel';
        }
    }

    public function switchSection(string $section): void
    {
        $allowed = ['panel', 'appointments', 'calendar', 'reviews'];
        if (in_array($section, $allowed)) {
            $this->mainSection = $section;
            if ($section === 'appointments') {
                $this->loadAppointments();
            } elseif ($section === 'reviews') {
                $this->loadReviews();
            }
        }
    }

    protected function rules()
    {
        $rules = [
            'bio' => 'nullable|string|max:1000',
            'price_from' => 'nullable|numeric|min:0',
            'website_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string|max:20',
            'newImage' => 'nullable|image|max:10240', // 10MB
            'district_id' => 'required|exists:districts,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'availability' => 'array',
            'availability.*.active' => 'boolean',
            'availability.*.start' => 'required_if:availability.*.active,true',
            'availability.*.end' => 'required_if:availability.*.active,true',
        ];

        if ($this->selectedRole === 'veterinarian') {
            $rules['license_number'] = 'nullable|string|max:50';
            $rules['address'] = 'nullable|string|max:255';
            $rules['allows_home_visits'] = 'boolean';
            $rules['emergency_24h'] = 'boolean';
        } elseif ($this->selectedRole === 'walker') {
            $rules['hourly_rate'] = 'nullable|numeric|min:0';
        } elseif ($this->selectedRole === 'groomer') {
             $rules['address'] = 'nullable|string|max:255';
             $rules['allows_home_visits'] = 'boolean';
        } elseif ($this->selectedRole === 'hotel') {
            $rules['address'] = 'nullable|string|max:255';
            $rules['capacity'] = 'nullable|integer|min:1';
            $rules['check_in_time'] = 'nullable';
            $rules['check_out_time'] = 'nullable';
            $rules['cage_free'] = 'boolean';
            $rules['has_transport'] = 'boolean';
        } elseif ($this->selectedRole === 'shelter') {
            $rules['address'] = 'nullable|string|max:255';
            $rules['donation_info'] = 'nullable|string|max:1000';
            $rules['accepting_adoptions'] = 'boolean';
            $rules['accepting_volunteers'] = 'boolean';
            $rules['accepting_donations'] = 'boolean';
        } elseif ($this->selectedRole === 'trainer') {
            $rules['methodology'] = 'nullable|string|max:255';
            $rules['certification'] = 'nullable|string|max:255';
            $rules['allows_home_visits'] = 'boolean';
        } elseif ($this->selectedRole === 'pet_sitter') {
            $rules['housing_type'] = 'nullable|string|max:255';
            $rules['has_yard'] = 'boolean';
            $rules['allows_home_visits'] = 'boolean';
        } elseif ($this->selectedRole === 'pet_taxi') {
            $rules['vehicle_type'] = 'nullable|string|max:255';
            $rules['has_ac'] = 'boolean';
            $rules['provides_crate'] = 'boolean';
        } elseif ($this->selectedRole === 'pet_photographer') {
            $rules['specialty'] = 'nullable|string|max:255';
            $rules['has_studio'] = 'boolean';
        }

        return $rules;
    }

    public function mount()
    {
        $this->user = Auth::user();
        
        $userProviderRoles = array_values(array_intersect(
            $this->user->roles->pluck('name')->toArray(),
            array_keys($this->providerRoles)
        ));

        if (!empty($userProviderRoles)) {
            $this->selectedRole = $userProviderRoles[0];
        }

        $this->loadProfile();
        $this->loadUbigeo();
        $this->loadPortfolio();
        $this->loadServices();
        $this->loadStats();
        $this->loadReviews();
        $this->loadAppointments();
        $this->calculateCompleteness();

        $section = request()->query('section', 'panel');
        $this->switchSection($section);

        $tab = request()->query('tab');
        if ($tab) {
            $this->switchTab($tab);
        }
    }

    public function loadProfile()
    {
        $userProviderRoles = array_values(array_intersect(
            $this->user->roles->pluck('name')->toArray(),
            array_keys($this->providerRoles)
        ));

        if (empty($userProviderRoles)) {
            return redirect()->route('dashboard');
        }

        if (!$this->selectedRole || !in_array($this->selectedRole, $userProviderRoles)) {
            $this->selectedRole = $userProviderRoles[0];
        }

        $role = $this->selectedRole;

        if ($role === 'veterinarian') {
            $this->profile = $this->user->veterinarianProfile;
            if ($this->profile) {
                $this->bio = $this->profile->bio;
                $this->address = $this->profile->address;
                $this->allows_home_visits = $this->profile->allows_home_visits;
                $this->license_number = $this->profile->license_number;
                $this->emergency_24h = $this->profile->emergency_24h;
                $this->district_id = $this->profile->district_id;
            }
        } elseif ($role === 'walker') {
            $this->profile = $this->user->walkerProfile;
            if ($this->profile) {
                $this->bio = $this->profile->experience;
                $this->hourly_rate = $this->profile->hourly_rate;
                $this->district_id = $this->profile->district_id;
            }
        } elseif ($role === 'groomer') {
            $this->profile = $this->user->groomerProfile;
            if ($this->profile) {
                $this->bio = $this->profile->bio;
                $this->address = $this->profile->address;
                $this->allows_home_visits = $this->profile->allows_home_visits;
                $this->district_id = $this->profile->district_id;
            }
        } elseif ($role === 'hotel') {
            $this->profile = $this->user->hotelProfile;
            if ($this->profile) {
                $this->bio = $this->profile->bio;
                $this->address = $this->profile->address;
                $this->district_id = $this->profile->district_id;
                $this->capacity = $this->profile->capacity;
                $this->has_transport = $this->profile->has_transport;
                $this->cage_free = $this->profile->cage_free;
                $this->check_in_time = $this->profile->check_in_time;
                $this->check_out_time = $this->profile->check_out_time;
            }
        } elseif ($role === 'shelter') {
            $this->profile = $this->user->shelterProfile;
            if ($this->profile) {
                $this->bio = $this->profile->bio;
                $this->address = $this->profile->address;
                $this->district_id = $this->profile->district_id;
                $this->capacity = $this->profile->capacity;
                $this->accepting_adoptions = $this->profile->accepting_adoptions;
                $this->accepting_volunteers = $this->profile->accepting_volunteers;
                $this->accepting_donations = $this->profile->accepting_donations;
                $this->donation_info = $this->profile->donation_info;
            }
        } elseif ($role === 'trainer') {
            $this->profile = $this->user->trainerProfile;
            if ($this->profile) {
                $this->bio = $this->profile->bio;
                $this->district_id = $this->profile->district_id;
                $this->allows_home_visits = $this->profile->allows_home_visits;
                $this->methodology = $this->profile->methodology;
                $this->certification = $this->profile->certification;
            }
        } elseif ($role === 'pet_sitter') {
            $this->profile = $this->user->petSitterProfile;
            if ($this->profile) {
                $this->bio = $this->profile->bio;
                $this->district_id = $this->profile->district_id;
                $this->allows_home_visits = $this->profile->allows_home_visits;
                $this->housing_type = $this->profile->housing_type;
                $this->has_yard = $this->profile->has_yard;
            }
        } elseif ($role === 'pet_taxi') {
            $this->profile = $this->user->petTaxiProfile;
            if ($this->profile) {
                $this->bio = $this->profile->bio;
                $this->district_id = $this->profile->district_id;
                $this->vehicle_type = $this->profile->vehicle_type;
                $this->has_ac = $this->profile->has_ac;
                $this->provides_crate = $this->profile->provides_crate;
            }
        } elseif ($role === 'pet_photographer') {
            $this->profile = $this->user->petPhotographerProfile;
            if ($this->profile) {
                $this->bio = $this->profile->bio;
                $this->district_id = $this->profile->district_id;
                $this->specialty = $this->profile->specialty;
                $this->has_studio = $this->profile->has_studio;
            }
        }

        if (!$this->profile) {
            $this->createProfileForRole($role);
        }

        if ($this->profile) {
            // Cargar estado de visualización
            $this->verification_status = $this->profile->is_verified;

            // Cargar coordenadas (si existen)
            $this->latitude = $this->profile->latitude ?? null;
            $this->longitude = $this->profile->longitude ?? null;

            // Cargar precio base (común a todos los roles con precio)
            $this->price_from = $this->profile->price_from ?? null;

            // Cargar redes sociales (comunes)
            $this->website_url = $this->profile->website_url;
            $this->facebook_url = $this->profile->facebook_url;
            $this->instagram_url = $this->profile->instagram_url;
            $this->tiktok_url = $this->profile->tiktok_url;
            $this->whatsapp_number = $this->profile->whatsapp_number;
            
            // Cargar o inicializar horarios
            $this->availability = $this->profile->availability ?? $this->getDefaultAvailability();
        }

        // Cargar datos de Yape/Plin (de la tabla users)
        $this->yape_number = $this->user->yape_number;
        $this->plin_number = $this->user->plin_number;
        $this->existingYapeQr = $this->user->yape_qr_path;
        $this->existingPlinQr = $this->user->plin_qr_path;
    }

    public function createProfileForRole($role)
    {
        $modelMap = [
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

        $modelClass = $modelMap[$role] ?? null;
        if ($modelClass) {
            $profile = $modelClass::where('user_id', $this->user->id)->first();
            if (!$profile) {
                $profile = $modelClass::create([
                    'user_id' => $this->user->id,
                    'is_verified' => false,
                    'district_id' => $this->findExistingDistrictId() ?: (\App\Models\District::first()?->id ?? null),
                ]);
            }
            $this->profile = $profile;
        }
    }

    private function findExistingDistrictId()
    {
        foreach (['veterinarianProfile', 'walkerProfile', 'groomerProfile', 'hotelProfile', 'shelterProfile', 'trainerProfile', 'petSitterProfile', 'petTaxiProfile', 'petPhotographerProfile'] as $rel) {
            if ($this->user->$rel && $this->user->$rel->district_id) {
                return $this->user->$rel->district_id;
            }
        }
        return null;
    }

    public function selectRole($role)
    {
        $this->selectedRole = $role;
        $this->loadProfile();
        $this->loadUbigeo();
        $this->calculateCompleteness();
        // Disparar evento para reinicializar mapa en la vista si cambia de rol
        $this->dispatch('role-changed', latitude: $this->latitude, longitude: $this->longitude);
    }

    public function activateRole($role)
    {
        if (!array_key_exists($role, $this->providerRoles)) {
            return;
        }

        // Asignar el rol al usuario
        $this->user->assignRole($role);

        // Crear el perfil correspondiente
        $this->createProfileForRole($role);

        // Cambiar la selección al rol recién activado
        $this->selectedRole = $role;

        // Recargar perfil, ubigeo, checklist, etc.
        $this->loadProfile();
        $this->loadUbigeo();
        $this->calculateCompleteness();

        session()->flash('message', 'Nuevo rol/servicio "' . $this->providerRoles[$role] . '" activado con éxito.');
    }

    public function deactivateRole($role)
    {
        $userProviderRoles = array_values(array_intersect(
            $this->user->roles->pluck('name')->toArray(),
            array_keys($this->providerRoles)
        ));

        if (count($userProviderRoles) <= 1) {
            session()->flash('error', 'Debes tener al menos un servicio activo.');
            return;
        }

        if (!in_array($role, $userProviderRoles)) {
            return;
        }

        // Remueve el rol del usuario en la base de datos
        $this->user->removeRole($role);

        // Elimina el perfil específico del proveedor
        $modelMap = [
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

        $modelClass = $modelMap[$role] ?? null;
        if ($modelClass) {
            $modelClass::where('user_id', $this->user->id)->delete();
        }

        // Si el rol desactivado era el seleccionado actualmente, cambiamos a otro activo
        if ($this->selectedRole === $role) {
            $remainingRoles = array_values(array_diff($userProviderRoles, [$role]));
            $this->selectedRole = $remainingRoles[0];
        }

        // Recargar perfil, ubigeo, checklist, etc.
        $this->loadProfile();
        $this->loadUbigeo();
        $this->calculateCompleteness();

        session()->flash('message', 'Servicio de "' . $this->providerRoles[$role] . '" desactivado con éxito.');
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

    // =========================================================
    // GESTIÓN DE CITAS (Inline, sin salir del dashboard)
    // =========================================================

    public function loadAppointments(): void
    {
        // El listado de citas ahora se carga de forma dinámica y paginada en render().
        // Solo mantenemos la actualización del contador de citas pendientes aquí.
        $this->pendingAppointmentsCount = \App\Models\Appointment::where('provider_id', $this->user->id)
            ->where('status', 'pending')
            ->count();
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSearchQuery(): void
    {
        $this->resetPage();
    }

    public function updatingDateFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStartDate(): void
    {
        $this->resetPage();
    }

    public function updatingEndDate(): void
    {
        $this->resetPage();
    }

    public function openAppointmentModal(int $id): void
    {
        $appointment = \App\Models\Appointment::with(['client', 'pet', 'services', 'payment'])
            ->where('provider_id', $this->user->id)
            ->find($id);

        if ($appointment) {
            $this->selectedAppointmentData = $appointment;
            $this->showAppointmentModal = true;
            $this->editPaymentAmount = $appointment->payment ? floatval($appointment->payment->amount) : 0.0;
            $this->editPaymentDescription = $appointment->payment ? $appointment->payment->description : '';

            $this->extraCharges = [];
            if ($appointment->payment && $appointment->payment->description) {
                $decoded = json_decode($appointment->payment->description, true);
                if (is_array($decoded)) {
                    $this->extraCharges = $decoded;
                } else {
                    // Retrocompatibilidad
                    $this->extraCharges[] = [
                        'concept' => $appointment->payment->description,
                        'amount' => floatval($appointment->payment->amount)
                    ];
                }
            } else {
                if ($appointment->services && $appointment->services->isNotEmpty()) {
                    foreach ($appointment->services as $service) {
                        $this->extraCharges[] = [
                            'concept' => $service->name,
                            'amount' => floatval($service->pivot->price_at_booking ?? $service->price)
                        ];
                    }
                } else {
                    $this->extraCharges[] = [
                        'concept' => 'Servicio contratado',
                        'amount' => floatval($this->editPaymentAmount)
                    ];
                }
            }
            $this->calculateTotalFromCharges();
        }
    }

    public function closeAppointmentModal(): void
    {
        $this->showAppointmentModal = false;
        $this->selectedAppointmentData = null;
    }

    public function addExtraCharge(): void
    {
        $this->validate([
            'newChargeConcept' => 'required|string|max:100',
            'newChargeAmount' => 'required|numeric|min:0',
        ], [
            'newChargeConcept.required' => 'El concepto o servicio es obligatorio.',
            'newChargeAmount.required' => 'El monto es obligatorio.',
            'newChargeAmount.numeric' => 'El monto debe ser un valor numérico.',
            'newChargeAmount.min' => 'El monto no puede ser negativo.',
        ]);

        $this->extraCharges[] = [
            'concept' => $this->newChargeConcept,
            'amount' => floatval($this->newChargeAmount)
        ];

        $this->newChargeConcept = '';
        $this->newChargeAmount = '';
        
        $this->calculateTotalFromCharges();
    }

    public function removeExtraCharge(int $index): void
    {
        if (isset($this->extraCharges[$index])) {
            unset($this->extraCharges[$index]);
            $this->extraCharges = array_values($this->extraCharges);
        }
        $this->calculateTotalFromCharges();
    }

    public function calculateTotalFromCharges(): void
    {
        $total = 0.0;
        foreach ($this->extraCharges as $charge) {
            $total += floatval($charge['amount']);
        }
        $this->editPaymentAmount = round($total, 2);
    }

    public function confirmAppointment(int $id): void
    {
        $appointment = \App\Models\Appointment::where('provider_id', $this->user->id)->findOrFail($id);
        $appointment->update(['status' => 'confirmed']);
        $appointment->client->notify(new \App\Notifications\AppointmentStatusChanged($appointment));
        $this->loadAppointments();
        // Actualizar el modal si está abierto
        if ($this->showAppointmentModal && $this->selectedAppointmentData?->id === $id) {
            $this->openAppointmentModal($id);
        }
        $this->dispatch('notify', message: '¡Cita confirmada! El cliente fue notificado. ✓', type: 'success');
    }

    public function cancelAppointment(int $id): void
    {
        $appointment = \App\Models\Appointment::where('provider_id', $this->user->id)->findOrFail($id);
        $appointment->update(['status' => 'cancelled']);
        $appointment->client->notify(new \App\Notifications\AppointmentStatusChanged($appointment));
        $this->confirmingCancel = null;
        $this->loadAppointments();
        if ($this->showAppointmentModal && $this->selectedAppointmentData?->id === $id) {
            $this->closeAppointmentModal();
        }
        $this->dispatch('notify', message: 'Cita cancelada. El cliente fue notificado.', type: 'info');
    }

    public function completeAppointment(int $id): void
    {
        $appointment = \App\Models\Appointment::where('provider_id', $this->user->id)->findOrFail($id);
        
        $this->validate([
            'editPaymentAmount' => 'required|numeric|min:0',
            'extraCharges' => 'required|array|min:1',
            'extraCharges.*.concept' => 'required|string|max:100',
            'extraCharges.*.amount' => 'required|numeric|min:0',
        ], [
            'extraCharges.required' => 'Debes registrar al menos un concepto en el desglose de cobros.',
            'extraCharges.min' => 'Debes registrar al menos un concepto en el desglose de cobros.',
            'extraCharges.*.concept.required' => 'El concepto de cobro es obligatorio.',
            'extraCharges.*.amount.required' => 'El monto es obligatorio.',
            'extraCharges.*.amount.numeric' => 'El monto debe ser numérico.',
        ]);

        $appointment->update(['status' => 'completed']);

        // Serializar desglose interactivo a JSON string
        $descriptionJson = json_encode($this->extraCharges);

        if ($appointment->payment) {
            $appointment->payment->update([
                'amount' => $this->editPaymentAmount,
                'description' => $descriptionJson,
            ]);
        } else {
            $appointment->payment()->create([
                'amount' => $this->editPaymentAmount,
                'description' => $descriptionJson,
                'payment_method' => 'yape',
                'status' => 'pending',
            ]);
        }

        $appointment->client->notify(new \App\Notifications\AppointmentStatusChanged($appointment));
        $this->loadAppointments();
        if ($this->showAppointmentModal && $this->selectedAppointmentData?->id === $id) {
            $this->openAppointmentModal($id);
        }
        $this->dispatch('notify', message: '¡Cita completada con monto y desglose de cobros actualizados! ✓', type: 'success');
    }

    public function approveAppointmentPayment(int $id): void
    {
        $appointment = \App\Models\Appointment::where('provider_id', $this->user->id)->findOrFail($id);
        if ($appointment->payment && $appointment->payment->status === 'under_review') {
            $appointment->payment->update(['status' => 'completed']);
            $this->loadAppointments();
            if ($this->showAppointmentModal && $this->selectedAppointmentData?->id === $id) {
                $this->openAppointmentModal($id);
            }
            $this->dispatch('notify', message: '¡Pago aprobado correctamente! ✓', type: 'success');
        }
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
            'district_id' => $this->district_id,
            'price_from' => $this->price_from ?: null,
            'latitude' => $this->latitude ?: null,
            'longitude' => $this->longitude ?: null,
        ];

        if ($this->selectedRole === 'veterinarian') {
            $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'address' => $this->address,
                'allows_home_visits' => $this->allows_home_visits,
                'license_number' => $this->license_number,
                'emergency_24h' => $this->emergency_24h,
            ]));
        } elseif ($this->selectedRole === 'walker') {
            $this->profile->update(array_merge($data, [
                'experience' => $this->bio,
                'hourly_rate' => $this->hourly_rate,
            ]));
        } elseif ($this->selectedRole === 'groomer') {
             $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'address' => $this->address,
                'allows_home_visits' => $this->allows_home_visits,
            ]));
        } elseif ($this->selectedRole === 'hotel') {
            $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'address' => $this->address,
                'capacity' => $this->capacity,
                'has_transport' => $this->has_transport,
                'cage_free' => $this->cage_free,
                'check_in_time' => $this->check_in_time,
                'check_out_time' => $this->check_out_time,
            ]));
        } elseif ($this->selectedRole === 'shelter') {
            $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'address' => $this->address,
                'capacity' => $this->capacity,
                'accepting_adoptions' => $this->accepting_adoptions,
                'accepting_volunteers' => $this->accepting_volunteers,
                'accepting_donations' => $this->accepting_donations,
                'donation_info' => $this->donation_info,
            ]));
        } elseif ($this->selectedRole === 'trainer') {
            $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'allows_home_visits' => $this->allows_home_visits,
                'methodology' => $this->methodology,
                'certification' => $this->certification,
            ]));
        } elseif ($this->selectedRole === 'pet_sitter') {
            $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'allows_home_visits' => $this->allows_home_visits,
                'housing_type' => $this->housing_type,
                'has_yard' => $this->has_yard,
            ]));
        } elseif ($this->selectedRole === 'pet_taxi') {
             $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'vehicle_type' => $this->vehicle_type,
                'has_ac' => $this->has_ac,
                'provides_crate' => $this->provides_crate,
            ]));
        } elseif ($this->selectedRole === 'pet_photographer') {
             $this->profile->update(array_merge($data, [
                'bio' => $this->bio,
                'specialty' => $this->specialty,
                'has_studio' => $this->has_studio,
            ]));
        }
        
        if ($this->profile_photo) {
            $this->validate(['profile_photo' => 'image|max:5120']); // 5MB
            $path = $this->profile_photo->store('profile-photos', config('filesystems.default'));
            $this->user->update(['profile_photo_path' => $path]);
            $this->profile_photo = null;
        }

        // Guardar datos de Yape/Plin
        $userData = [
            'yape_number' => $this->yape_number ?: null,
            'plin_number' => $this->plin_number ?: null,
        ];

        if ($this->yape_qr) {
            $this->validate(['yape_qr' => 'image|max:5120']); // 5MB
            $path = $this->yape_qr->store('qrs', config('filesystems.default'));
            $userData['yape_qr_path'] = $path;
            $this->existingYapeQr = $path;
            $this->yape_qr = null;
        }

        if ($this->plin_qr) {
            $this->validate(['plin_qr' => 'image|max:5120']); // 5MB
            $path = $this->plin_qr->store('qrs', config('filesystems.default'));
            $userData['plin_qr_path'] = $path;
            $this->existingPlinQr = $path;
            $this->plin_qr = null;
        }

        $this->user->update($userData);

        // La lógica de documento ahora se maneja principalmente en uploadVerificationDocument
        // pero lo mantenemos aquí por si acaso el usuario usa el botón general
        if ($this->verification_document) {
           $this->uploadVerificationDocument();
        }

        $this->calculateCompleteness();

        $this->dispatch('notify', message: '¡Perfil actualizado correctamente! ✓', type: 'success');
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
        
        $this->calculateCompleteness();
        
        $this->dispatch('notify', message: 'Documento enviado para revisión. ¡Te avisaremos pronto! ✓', type: 'success');
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
        $this->calculateCompleteness();
        $this->dispatch('notify', message: 'Imagen agregada al portafolio correctamente. ✓', type: 'success');
    }

    public function deleteImage($id)
    {
        $image = $this->user->portfolio()->find($id);
        if ($image) {
            // Eliminar archivo físico
            \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->delete($image->image_path);
            $image->delete();
            $this->loadPortfolio();
            $this->calculateCompleteness();
        }
    }

    public function loadServices()
    {
        $this->providerServices = $this->user->services()->latest()->get();
    }

    public function saveService()
    {
        $this->validate([
            'serviceName' => 'required|string|max:100',
            'serviceDescription' => 'nullable|string|max:500',
            'servicePrice' => 'required|numeric|min:0',
            'serviceDuration' => 'nullable|integer|min:5|max:1440',
        ], [
            'serviceName.required' => 'El nombre del servicio es obligatorio.',
            'servicePrice.required' => 'El precio es obligatorio.',
            'servicePrice.numeric' => 'El precio debe ser un número.',
        ]);

        $data = [
            'name' => $this->serviceName,
            'description' => $this->serviceDescription,
            'price' => $this->servicePrice,
            'duration_minutes' => $this->serviceDuration ?: null,
        ];

        if ($this->isEditingService && $this->serviceId) {
            $service = $this->user->services()->find($this->serviceId);
            if ($service) {
                $service->update($data);
                $this->dispatch('notify', message: 'Servicio actualizado en el catálogo. ✓', type: 'success');
            }
        } else {
            $this->user->services()->create($data);
            $this->dispatch('notify', message: 'Servicio agregado al catálogo. ✓', type: 'success');
        }

        $this->resetServiceForm();
        $this->loadServices();
        $this->calculateCompleteness();
    }

    public function editService($id)
    {
        $service = $this->user->services()->find($id);
        if ($service) {
            $this->serviceId = $service->id;
            $this->serviceName = $service->name;
            $this->serviceDescription = $service->description;
            $this->servicePrice = $service->price;
            $this->serviceDuration = $service->duration_minutes;
            $this->isEditingService = true;
        }
    }

    public function deleteService($id)
    {
        $service = $this->user->services()->find($id);
        if ($service) {
            $service->delete();
            $this->loadServices();
            $this->calculateCompleteness();
            $this->dispatch('notify', message: 'Servicio eliminado del catálogo.', type: 'info');
        }
    }

    public function resetServiceForm()
    {
        $this->reset(['serviceId', 'serviceName', 'serviceDescription', 'servicePrice', 'serviceDuration', 'isEditingService']);
    }

    public function loadStats()
    {
        $this->completedAppointmentsCount = \App\Models\Appointment::where('provider_id', $this->user->id)
            ->where('status', 'completed')
            ->count();

        $this->activeAppointmentsCount = \App\Models\Appointment::where('provider_id', $this->user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $this->totalEarnings = \App\Models\Payment::whereHas('appointment', function($q) {
                $q->where('provider_id', $this->user->id);
            })
            ->where('status', 'completed')
            ->sum('amount');

        $this->monthlyEarnings = \App\Models\Payment::whereHas('appointment', function($q) {
                $q->where('provider_id', $this->user->id);
            })
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $totalAppointments = \App\Models\Appointment::where('provider_id', $this->user->id)->count();
        $acceptedAppointments = \App\Models\Appointment::where('provider_id', $this->user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();
        $this->acceptanceRate = $totalAppointments > 0 ? round(($acceptedAppointments / $totalAppointments) * 100) : 100;

        $this->todayAppointments = \App\Models\Appointment::where('provider_id', $this->user->id)
            ->whereDate('scheduled_at', now()->toDateString())
            ->with('client', 'pet')
            ->orderBy('scheduled_at')
            ->get();

        $this->averageRating = round($this->user->reviewsReceived()->avg('rating') ?? 5.0, 1);

        $this->recentPayments = \App\Models\Payment::whereHas('appointment', function($q) {
                $q->where('provider_id', $this->user->id);
            })
            ->with('appointment.client')
            ->latest()
            ->take(5)
            ->get();
    }

    public function loadReviews()
    {
        $this->receivedReviews = $this->user->reviewsReceived()->with('user')->latest()->get();
        foreach ($this->receivedReviews as $review) {
            $this->replyText[$review->id] = $review->provider_response ?? '';
        }
    }

    public function submitReply($reviewId)
    {
        $this->validate([
            'replyText.' . $reviewId => 'required|string|min:5|max:1000'
        ], [
            'replyText.*.required' => 'La respuesta es obligatoria.',
            'replyText.*.min' => 'La respuesta debe tener al menos 5 caracteres.',
            'replyText.*.max' => 'La respuesta no debe exceder los 1000 caracteres.',
        ]);

        $review = \App\Models\Review::where('provider_id', $this->user->id)->findOrFail($reviewId);
        $review->update([
            'provider_response' => $this->replyText[$reviewId],
            'replied_at' => now(),
        ]);

        $this->loadReviews();
        $this->calculateCompleteness();
        $this->dispatch('notify', message: 'Respuesta de reseña enviada con éxito. ✓', type: 'success');
    }

    public function deleteReply($reviewId)
    {
        $review = \App\Models\Review::where('provider_id', $this->user->id)->findOrFail($reviewId);
        $review->update([
            'provider_response' => null,
            'replied_at' => null,
        ]);

        $this->replyText[$reviewId] = '';
        $this->loadReviews();
        $this->calculateCompleteness();
        $this->dispatch('notify', message: 'Respuesta de reseña eliminada.', type: 'info');
    }

    public function calculateCompleteness()
    {
        $this->completenessScore = $this->user->getProfileCompleteness($this->profile);
        $this->providerLevel = $this->user->getProfileLevel($this->profile);

        $this->completenessChecklist = [
            'photo' => [
                'label' => 'Foto de Perfil',
                'complete' => !empty($this->user->profile_photo_path),
                'points' => 20,
                'tab' => 'profile'
            ],
            'location' => [
                'label' => 'Ubicación (Distrito)',
                'complete' => !empty($this->district_id),
                'points' => 20,
                'tab' => 'profile'
            ],
            'verification' => [
                'label' => 'Documento de Verificación',
                'complete' => !empty($this->profile->verification_document_path),
                'points' => 20,
                'tab' => 'profile'
            ],
            'services' => [
                'label' => 'Catálogo de Servicios',
                'complete' => $this->user->services()->exists(),
                'points' => 20,
                'tab' => 'services'
            ],
            'payment' => [
                'label' => 'Configuración Yape/Plin',
                'complete' => !empty($this->user->yape_number) || !empty($this->user->plin_number),
                'points' => 10,
                'tab' => 'payments_config'
            ],
            'portfolio' => [
                'label' => 'Imágenes en Portafolio',
                'complete' => $this->user->portfolio()->exists(),
                'points' => 10,
                'tab' => 'portfolio'
            ],
        ];
    }

    public function render()
    {
        // Ensure pending appointments count is always fresh for nav badges
        $this->pendingAppointmentsCount = \App\Models\Appointment::where('provider_id', $this->user->id)
            ->where('status', 'pending')
            ->count();

        // Dynamically load list data based on the active section to prevent serialization issues
        $appointmentsList = collect([]);
        if ($this->mainSection === 'appointments') {
            $appointmentsList = \App\Models\Appointment::with(['client', 'pet', 'services', 'payment'])
                ->where('provider_id', $this->user->id)
                ->when($this->filterStatus !== 'all', fn($q) => $q->where('status', $this->filterStatus))
                ->when($this->searchQuery !== '', function($q) {
                    $q->where(fn($sub) => $sub
                        ->whereHas('client', fn($c) => $c->where('name', 'like', '%' . $this->searchQuery . '%')
                            ->orWhere('email', 'like', '%' . $this->searchQuery . '%'))
                        ->orWhereHas('pet', fn($p) => $p->where('name', 'like', '%' . $this->searchQuery . '%'))
                    );
                })
                ->when($this->dateFilter !== 'all', function($q) {
                    $today = \Carbon\Carbon::today();
                    if ($this->dateFilter === 'today') {
                        $q->whereDate('scheduled_at', $today);
                    } elseif ($this->dateFilter === 'tomorrow') {
                        $q->whereDate('scheduled_at', \Carbon\Carbon::tomorrow());
                    } elseif ($this->dateFilter === 'this_week') {
                        $q->whereBetween('scheduled_at', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
                    } elseif ($this->dateFilter === 'custom' && !empty($this->startDate) && !empty($this->endDate)) {
                        try {
                            $q->whereBetween('scheduled_at', [
                                \Carbon\Carbon::parse($this->startDate)->startOfDay(),
                                \Carbon\Carbon::parse($this->endDate)->endOfDay()
                            ]);
                        } catch (\Exception $e) {
                            // Ignorar errores de parseo de fecha
                        }
                    }
                })
                ->orderBy('scheduled_at', 'asc')
                ->paginate(10);
        } elseif ($this->mainSection === 'reviews') {
            $this->loadReviews();
        }

        return view('livewire.dashboard.provider-dashboard', [
            'appointmentsList' => $appointmentsList
        ])->layout('components.layouts.app');
    }
}
