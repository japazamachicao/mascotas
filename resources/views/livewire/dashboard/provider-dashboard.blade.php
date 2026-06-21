<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

        @if(session('onboarding'))
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <p class="text-sm font-semibold text-blue-800">{{ session('onboarding') }}</p>
                    <p class="text-xs text-blue-600 mt-1">Agrega tu ubicación, bio, horarios y al menos una foto de portafolio para destacar en las búsquedas.</p>
                </div>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Panel Profesional - {{ $providerRoles[$selectedRole] ?? 'Profesional' }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Gestiona tu disponibilidad, portafolio y perfil público.
            </p>
        </div>

        <!-- Multi-Role Selector Bar -->
        @php
            $userActiveRoles = array_values(array_intersect(
                $user->roles->pluck('name')->toArray(),
                array_keys($providerRoles)
            ));
            $inactiveRoles = array_diff(array_keys($providerRoles), $userActiveRoles);
        @endphp

        @if(count($userActiveRoles) > 1 || count($inactiveRoles) > 0)
        <div class="mb-6 bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider shrink-0">Mis Servicios:</span>
                    @foreach($userActiveRoles as $role)
                        <button wire:click="selectRole('{{ $role }}')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-bold rounded-lg border transition-all duration-200
                            {{ $selectedRole === $role 
                                ? 'bg-primary-600 border-primary-600 text-white shadow-sm' 
                                : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50 hover:border-gray-300' }}">
                            @php
                                $roleIcons = [
                                    'veterinarian' => '🩺', 'walker' => '🐕', 'groomer' => '✂️',
                                    'hotel' => '🏨', 'shelter' => '🏠', 'trainer' => '🎓',
                                    'pet_sitter' => '🐾', 'pet_taxi' => '🚗', 'pet_photographer' => '📸',
                                ];
                            @endphp
                            <span>{{ $roleIcons[$role] ?? '📋' }}</span>
                            {{ $providerRoles[$role] }}
                        </button>
                    @endforeach
                </div>

                @if(count($inactiveRoles) > 0)
                <div x-data="{ showAddRole: false }" class="relative">
                    <button @click="showAddRole = !showAddRole" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-bold rounded-lg border border-dashed border-primary-300 text-primary-600 bg-primary-50 hover:bg-primary-100 transition-all">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Agregar Servicio
                    </button>
                    <div x-show="showAddRole" @click.away="showAddRole = false" x-transition
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 z-50 py-2">
                        @foreach($inactiveRoles as $role)
                            <button wire:click="activateRole('{{ $role }}')" @click="showAddRole = false"
                                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors flex items-center gap-2">
                                <span>{{ $roleIcons[$role] ?? '📋' }}</span>
                                {{ $providerRoles[$role] }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

    

        <div x-data="{ activeTab: 'profile' }" class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Menú Lateral -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Perfil Card -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center text-center flex-col">
                        <div class="shrink-0 relative group">
                            <div class="h-24 w-24 rounded-full overflow-hidden border-2 border-primary-500 bg-gray-100">
                                @if ($profile_photo)
                                    <img src="{{ $profile_photo->temporaryUrl() }}" class="h-full w-full object-cover">
                                @elseif ($user->profile_photo_path)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) }}" class="h-full w-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=0ea5e9&color=fff" class="h-full w-full object-cover">
                                @endif
                                
                                
                                <!-- Overlay de carga -->
                                <div wire:loading wire:target="profile_photo" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-20">
                                    <svg class="animate-spin h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Botón de Cámara (Visible Siempre) -->
                            <div class="absolute bottom-0 right-0 bg-white rounded-full p-1.5 shadow-lg border border-gray-200 text-gray-600 group-hover:text-primary-600 group-hover:border-primary-500 transition-colors duration-200 z-10">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>

                            <label for="sidebar_profile_photo" class="absolute inset-0 w-full h-full cursor-pointer z-20">
                                <input type="file" wire:model.live="profile_photo" id="sidebar_profile_photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        
                        @if($profile_photo)
                            <button wire:click="save" class="mt-2 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Guardar Foto
                            </button>
                        @endif
                        <div class="mt-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                            @if(!empty($providerLevel))
                                <div class="mt-1.5 flex items-center justify-center">
                                    <x-level-badge level="{{ $providerLevel['name'] }}" size="sm" />
                                </div>
                            @endif
                            <a href="{{ route('profile.show', $user->id) }}" target="_blank" class="text-sm font-medium text-primary-600 hover:text-primary-500 flex items-center justify-center mt-1">
                                Ver Perfil Público 
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                            <div x-data="{ copied: false }" class="mt-4 w-full bg-primary-50/50 rounded-2xl p-3 border border-primary-100 flex flex-col gap-2 shadow-xs">
                                <div class="text-left">
                                    <p class="text-[9px] uppercase font-black tracking-widest text-primary-850">Comparte tu Perfil</p>
                                    <p class="text-[10px] text-gray-500 truncate mt-0.5">{{ route('profile.show', $user->id) }}</p>
                                </div>
                                <button @click="navigator.clipboard.writeText('{{ route('profile.show', $user->id) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        type="button"
                                        class="w-full py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-black transition flex items-center justify-center gap-1 shadow-sm">
                                    <span x-show="!copied">📋 Copiar Enlace</span>
                                    <span x-show="copied" style="display: none;" class="text-green-200">✓ Enlace Copiado</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navegación Vertical -->
                    <nav class="mt-8 space-y-1">
                        <button @click="activeTab = 'profile'" 
                            :class="activeTab === 'profile' ? 'bg-primary-50 text-primary-700 border-primary-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="group w-full flex items-center px-3 py-2 text-sm font-medium border-l-4">
                            <svg :class="activeTab === 'profile' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Perfil y Contacto
                        </button>
                        
                        <button @click="activeTab = 'schedule'" 
                            :class="activeTab === 'schedule' ? 'bg-primary-50 text-primary-700 border-primary-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="group w-full flex items-center px-3 py-2 text-sm font-medium border-l-4">
                            <svg :class="activeTab === 'schedule' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Horarios de Atención
                        </button>

                        <button @click="activeTab = 'calendar'" 
                            :class="activeTab === 'calendar' ? 'bg-primary-50 text-primary-700 border-primary-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="group w-full flex items-center px-3 py-2 text-sm font-medium border-l-4">
                            <svg :class="activeTab === 'calendar' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Calendario y Bloqueos
                        </button>

                        <button @click="activeTab = 'portfolio'"
                            :class="activeTab === 'portfolio' ? 'bg-primary-50 text-primary-700 border-primary-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="group w-full flex items-center px-3 py-2 text-sm font-medium border-l-4">
                            <svg :class="activeTab === 'portfolio' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Mi Portafolio
                        </button>

                        <button @click="activeTab = 'services'" 
                            :class="activeTab === 'services' ? 'bg-primary-50 text-primary-700 border-primary-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="group w-full flex items-center px-3 py-2 text-sm font-medium border-l-4">
                            <svg :class="activeTab === 'services' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Catálogo de Servicios
                        </button>

                        <button @click="activeTab = 'payments_config'" 
                            :class="activeTab === 'payments_config' ? 'bg-primary-50 text-primary-700 border-primary-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="group w-full flex items-center px-3 py-2 text-sm font-medium border-l-4">
                            <svg :class="activeTab === 'payments_config' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Cobros Yape/Plin
                        </button>

                        <button @click="activeTab = 'stats'" 
                            :class="activeTab === 'stats' ? 'bg-primary-50 text-primary-700 border-primary-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="group w-full flex items-center px-3 py-2 text-sm font-medium border-l-4">
                            <svg :class="activeTab === 'stats' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2zm9 0v-9a2 2 0 00-2-2h-2a2 2 0 00-2 2v9a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                            Estadísticas
                        </button>

                        <button @click="activeTab = 'reviews'" 
                            :class="activeTab === 'reviews' ? 'bg-primary-50 text-primary-700 border-primary-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="group w-full flex items-center px-3 py-2 text-sm font-medium border-l-4">
                            <svg :class="activeTab === 'reviews' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            Reseñas Recibidas
                        </button>

                        <a href="{{ route('dashboard.provider.appointments') }}"
                            class="group w-full flex items-center px-3 py-2 text-sm font-medium border-l-4 text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent">
                            <svg class="shrink-0 -ml-1 mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Mis Citas
                            @php $pendingCount = \App\Models\Appointment::where('provider_id', auth()->id())->where('status', 'pending')->count(); @endphp
                            @if($pendingCount > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="lg:col-span-3 space-y-8">
                
                @if (session()->has('message'))
                    <div class="rounded-md bg-green-50 p-4 border border-green-200 mb-4">
                        <div class="flex">
                            <div class="shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4 border border-red-200 mb-4">
                        <div class="flex">
                            <div class="shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Hay errores en el formulario:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- ONBOARDING CHECKLIST -->
                @if($completenessScore < 100)
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-2xl border border-indigo-100/70 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h4 class="text-base font-black text-indigo-955 flex items-center gap-2">
                                    🚀 ¡Completa tu perfil profesional!
                                </h4>
                                <p class="text-xs text-indigo-700 mt-1">Completa los siguientes pasos para destacar y generar mayor confianza en los clientes.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                @if(!empty($providerLevel))
                                    <x-level-badge level="{{ $providerLevel['name'] }}" size="sm" />
                                @endif
                                <span class="text-xs font-bold text-indigo-950">Progreso:</span>
                                <span class="px-2.5 py-1 rounded-full text-xs font-black bg-indigo-600 text-white shadow-sm">{{ $completenessScore }}%</span>
                            </div>
                        </div>
 
                        <!-- Progress Bar -->
                        <div class="w-full bg-indigo-200/50 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $completenessScore }}%"></div>
                        </div>
 
                        <!-- Checklist Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-2">
                            @foreach($completenessChecklist as $key => $item)
                                <button @click="activeTab = '{{ $item['tab'] }}'" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-indigo-100/50 text-left hover:shadow-sm hover:border-indigo-200 transition">
                                    @if($item['complete'])
                                        <span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold shrink-0">✓</span>
                                        <span class="text-xs font-bold text-gray-500 line-through">{{ $item['label'] }}</span>
                                    @else
                                        <span class="w-5 h-5 rounded-full bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center text-xs font-bold shrink-0 font-black">•</span>
                                        <span class="text-xs font-bold text-gray-700 hover:text-indigo-600 transition">{{ $item['label'] }} (+{{ $item['points'] }}%)</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-gradient-to-r from-cyan-50 to-emerald-50 p-6 rounded-2xl border border-cyan-100/70 shadow-sm space-y-2">
                        <h4 class="text-base font-black text-cyan-950 flex items-center gap-2">
                            💎 ¡Felicidades! Perfil Completo al 100%
                        </h4>
                        <p class="text-xs text-cyan-800">
                            Tu perfil tiene nivel <strong class="text-cyan-900">{{ $providerLevel['label'] ?? 'Diamante' }}</strong>. Estás destacando al máximo en las búsquedas de los clientes. ¡Sigue con el excelente trabajo!
                            <div class="mt-2.5 flex">
                                <x-level-badge level="{{ $providerLevel['name'] ?? 'diamante' }}" size="md" />
                            </div>
                        </p>
                    </div>
                @endif

                <!-- TAB: PERFIL -->
                <div x-show="activeTab === 'profile'" class="space-y-6">
                    <!-- Widget Hoy de un vistazo -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Citas de hoy -->
                        <div class="bg-gradient-to-br from-indigo-950 to-slate-900 text-white rounded-3xl p-6 md:col-span-2 shadow-md flex flex-col justify-between min-h-[180px]">
                            <div>
                                <div class="flex justify-between items-start">
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-white/20 text-indigo-100 border border-white/10">
                                        📅 HOY DE UN VISTAZO
                                    </span>
                                    <span class="text-xs font-bold text-indigo-200">
                                        {{ now()->format('d M, Y') }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-black mt-3">Agenda del día</h3>
                                
                                <div class="mt-4 space-y-3 max-h-[120px] overflow-y-auto scrollbar-none pr-1">
                                    @forelse($todayAppointments as $app)
                                        <div class="flex items-center justify-between p-2.5 bg-white/10 rounded-xl border border-white/5">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm">🐾</span>
                                                <div class="text-left">
                                                    <p class="text-xs font-bold">{{ $app->client->name }}</p>
                                                    <p class="text-[10px] text-indigo-200">{{ $app->pet->name ?? 'Mascota' }} • {{ $app->scheduled_at->format('H:i') }} hrs</p>
                                                </div>
                                            </div>
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black border 
                                                {{ $app->status === 'confirmed' ? 'bg-blue-500/20 text-blue-200 border-blue-400/20' : 'bg-emerald-500/20 text-emerald-200 border-emerald-400/20' }}">
                                                {{ $app->status === 'confirmed' ? 'Confirmada' : 'Completada' }}
                                            </span>
                                        </div>
                                    @empty
                                        <p class="text-xs text-indigo-200 italic pt-2">No tienes citas agendadas para el día de hoy. ¡Buen día para captar nuevos clientes!</p>
                                    @endforelse
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-between items-center pt-3 border-t border-white/10">
                                <a href="{{ route('dashboard.provider.appointments') }}" class="text-xs font-bold text-white hover:underline flex items-center gap-1">
                                    Administrar todas mis citas <span>➔</span>
                                </a>
                            </div>
                        </div>

                        <!-- Ingresos mensuales y KPI de Aceptación -->
                        <div class="bg-white rounded-3xl p-6 border border-gray-200/80 shadow-xs flex flex-col justify-between min-h-[180px]">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-400">Ingresos del Mes</span>
                                    <span class="text-xl">💰</span>
                                </div>
                                <div>
                                    <p class="text-2xl font-black text-gray-950">S/ {{ number_format($monthlyEarnings, 2) }}</p>
                                    <p class="text-[10px] font-bold text-emerald-650 mt-1 flex items-center gap-0.5">
                                        <span>✓</span> Aceptación del {{ $acceptanceRate }}%
                                    </p>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 pt-3 flex justify-between items-center text-xs font-bold">
                                <button @click="activeTab = 'stats'" class="text-primary-600 hover:text-primary-750 transition flex items-center gap-1">
                                    Ver historial financiero <span>➔</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 pb-2 border-b">Información Profesional</h3>
                        
                        <form wire:submit.prevent="save">
                            <!-- Estado de Verificación (Solo Documento) -->
                            <div class="mb-6 p-4 bg-gray-50 rounded-md border border-gray-200">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @if($verification_status)
                                            <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-3 w-full">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            @if($verification_status)
                                                Perfil Verificado
                                            @else
                                                Verificación de Identidad
                                            @endif
                                        </h3>
                                        
                                        @if($verification_status)
                                            <div class="mt-2 text-sm text-gray-500">
                                                <p>¡Felicidades! Tu identidad ha sido verificada. Tienes acceso a todas las insignias de confianza.</p>
                                            </div>
                                        @else
                                            <div class="mt-2">
                                                @php
                                                    $attempts = $profile->verification_attempts ?? 0;
                                                    $maxAttempts = 2;
                                                @endphp
    
                                                <div class="text-sm text-gray-500 mb-2">
                                                    <p class="mb-1">Sube un documento (DNI, Título, Certificado) para obtener la insignia de "Verificado".</p>
                                                    <ul class="list-disc list-inside text-xs text-gray-400 bg-white p-2 rounded border border-gray-200 mt-2">
                                                        <li>Formatos: <strong>PDF, JPG, PNG</strong>.</li>
                                                        <li>Peso Máximo: <strong>10MB</strong>.</li>
                                                        <li>Intentos: <strong>{{ $maxAttempts - $attempts }}</strong> de {{ $maxAttempts }} disponibles.</li>
                                                    </ul>
                                                </div>
    
                                                @if($attempts < $maxAttempts)
                                                    <div class="flex items-center mt-3">
                                                        <label class="cursor-pointer bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                            <span>Seleccionar Archivo</span>
                                                            <input type="file" wire:model.live="verification_document" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                                        </label>
                                                        
                                                        <!-- Feedback de Carga -->
                                                        <span class="ml-3 text-sm text-primary-600" wire:loading wire:target="verification_document">
                                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-primary-600 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            Cargando...
                                                        </span>
    
                                                        <!-- Botón de Enviar (Solo visible si hay archivo) -->
                                                        @if($verification_document) 
                                                            <button wire:click="uploadVerificationDocument" type="button" class="ml-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                                Enviar Documento
                                                            </button>
                                                        <!-- Feedback de Archivo ya Guardado -->
                                                        @elseif($profile->verification_document_path) 
                                                            <span class="ml-3 text-sm text-gray-500" wire:loading.remove wire:target="verification_document">
                                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                Documento en revisión (Intento {{ $attempts }})
                                                            </span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="mt-2 text-sm text-amber-700 bg-amber-50 p-3 rounded border border-amber-200 flex items-center">
                                                        <svg class="h-5 w-5 mr-2 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                        <span>
                                                            <strong>Límite alcanzado.</strong> Tus documentos están en cola de revisión.
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            @error('verification_document') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                
                                {{-- Ubicación (Común e Importante para Filtros) --}}
                                <div class="col-span-6 border-b border-gray-100 pb-4 mb-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-4 block">Ubicación (Para que te encuentren en búsquedas)</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Departamento</label>
                                            <select wire:model.live="selectedDepartment" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                <option value="">Seleccionar</option>
                                                @foreach($departments as $dep)
                                                    <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Provincia</label>
                                            <select wire:model.live="selectedProvince" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                <option value="">Seleccionar</option>
                                                @foreach($provinces as $prov)
                                                    <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Distrito</label>
                                            <select wire:model="district_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                <option value="">Seleccionar</option>
                                                @foreach($districts as $dist)
                                                    <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('district_id') <span class="text-red-500 text-xs">Selecciona un distrito</span> @enderror
                                        </div>
                                    </div>

                                    {{-- Map Coordinates Selector --}}
                                    <div class="mt-6" x-data="{ map: null, marker: null, initMap() { this.$nextTick(() => { window.initLeafletMapForDashboard(this); }); } }" x-init="initMap()">
                                        <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-2">Selecciona tu ubicación exacta en el mapa</label>
                                        <div id="dashboard-map" class="h-64 rounded-xl border border-gray-200 shadow-sm z-10" wire:ignore></div>
                                        <div class="grid grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <label class="block text-[10px] text-gray-400 font-bold uppercase">Latitud</label>
                                                <input type="text" wire:model="latitude" class="w-full text-xs bg-gray-50 border-gray-200 rounded-lg py-1.5 px-3" readonly placeholder="Ej: -12.0463">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] text-gray-400 font-bold uppercase">Longitud</label>
                                                <input type="text" wire:model="longitude" class="w-full text-xs bg-gray-50 border-gray-200 rounded-lg py-1.5 px-3" readonly placeholder="Ej: -77.0427">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bio / Experiencia --}}
                                <div class="sm:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ $selectedRole === 'veterinarian' ? 'Biografía y Especialidades' : 'Experiencia y Servicios' }}
                                    </label>
                                    <div class="mt-1">
                                        <textarea wire:model="bio" rows="4" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Esta es tu carta de presentación. Sé descriptivo y amable.</p>
                                </div>

                                {{-- Precio base (común) --}}
                                @if($selectedRole !== 'shelter')
                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Precio desde (S/)
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">S/</span>
                                        </div>
                                        <input type="number" min="0" step="0.50" wire:model="price_from" class="pl-8 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Precio mínimo de tu servicio. Ayuda a los clientes a encontrarte.</p>
                                </div>
                                @endif

                                {{-- Campos Específicos --}}
                                @if($selectedRole === 'veterinarian')
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Número de Colegiatura (CMVP)</label>
                                        <div class="mt-1">
                                            <input type="text" wire:model="license_number" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Dirección del Consultorio</label>
                                        <div class="mt-1">
                                            <input type="text" wire:model="address" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                    <div class="sm:col-span-6 flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="home_visits" wire:model="allows_home_visits" type="checkbox" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="home_visits" class="font-medium text-gray-700">Realizo Visitas a Domicilio</label>
                                        </div>
                                    </div>
                                @elseif($selectedRole === 'walker')
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Tarifa por Hora (S/)</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">S/</span>
                                            </div>
                                            <input type="number" wire:model="hourly_rate" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                @elseif($selectedRole === 'groomer')
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Dirección del Salón/Spa</label>
                                        <div class="mt-1">
                                            <input type="text" wire:model="address" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                    <div class="sm:col-span-6 flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="home_visits" wire:model="allows_home_visits" type="checkbox" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="home_visits" class="font-medium text-gray-700">Realizo Servicios a Domicilio</label>
                                        </div>
                                    </div>
                                @elseif($selectedRole === 'hotel')
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Dirección del Hotel</label>
                                        <div class="mt-1">
                                            <input type="text" wire:model="address" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Capacidad Máxima</label>
                                        <input type="number" wire:model="capacity" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Check-In</label>
                                        <input type="time" wire:model="check_in_time" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Check-Out</label>
                                        <input type="time" wire:model="check_out_time" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-6 flex items-center space-x-6">
                                        <div class="flex items-center">
                                            <input wire:model="cage_free" type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700">Sin Jaulas (Cage Free)</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input wire:model="has_transport" type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700">Servicio de Recojo</label>
                                        </div>
                                    </div>
                                @elseif($selectedRole === 'shelter')
                                    <div class="sm:col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Dirección del Albergue</label>
                                        <input type="text" wire:model="address" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Información para Donaciones</label>
                                        <textarea wire:model="donation_info" rows="3" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Cuentas Bancarias, Yape, Plin..."></textarea>
                                    </div>
                                    <div class="sm:col-span-6 flex items-center space-x-6">
                                        <div class="flex items-center">
                                            <input wire:model="accepting_adoptions" type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700">Adopciones Abiertas</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input wire:model="accepting_volunteers" type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700">Busco Voluntarios</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input wire:model="accepting_donations" type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700">Acepto Donaciones</label>
                                        </div>
                                    </div>
                                @elseif($selectedRole === 'trainer')
                                    <div class="sm:col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Metodología/Enfoque</label>
                                        <input type="text" wire:model="methodology" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="e.g., Refuerzo Positivo">
                                    </div>
                                    <div class="sm:col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Certificaciones</label>
                                        <input type="text" wire:model="certification" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="sm:col-span-6 flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="home_visits" wire:model="allows_home_visits" type="checkbox" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="home_visits" class="font-medium text-gray-700">Realizo Adiestramiento a Domicilio</label>
                                        </div>
                                    </div>
                                @elseif($selectedRole === 'pet_sitter')
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Tipo de Vivienda</label>
                                        <select wire:model="housing_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                            <option value="">Seleccionar</option>
                                            <option value="Casa">Casa</option>
                                            <option value="Departamento">Departamento</option>
                                            <option value="Campo">Casa de Campo</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-6 flex items-center space-x-6">
                                        <div class="flex items-center">
                                            <input wire:model="has_yard" type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700">Tengo Patio/Jardín</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input wire:model="allows_home_visits" type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700">Cuido en casa del dueño</label>
                                        </div>
                                    </div>
                                @elseif($selectedRole === 'pet_taxi')
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Tipo de Vehículo</label>
                                        <select wire:model="vehicle_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                            <option value="">Seleccionar</option>
                                            <option value="Auto">Auto Sedán/Hatchback</option>
                                            <option value="Camioneta">Camioneta/SUV</option>
                                            <option value="Van">Van</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-6 flex items-center space-x-6">
                                        <div class="flex items-center">
                                            <input wire:model="has_ac" type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700">Aire Acondicionado</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input wire:model="provides_crate" type="checkbox" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                                            <label class="ml-2 text-sm text-gray-700">Incluyo Jaula de Transporte</label>
                                        </div>
                                    </div>
                                @elseif($selectedRole === 'pet_photographer')
                                    <div class="sm:col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Especialidad</label>
                                        <input type="text" wire:model="specialty" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="e.g., Retratos, Exteriores, Eventos">
                                    </div>
                                    <div class="sm:col-span-6 flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="has_studio" wire:model="has_studio" type="checkbox" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="has_studio" class="font-medium text-gray-700">Cuento con Estudio Propio</label>
                                        </div>
                                    </div>
                                @endif

                                {{-- Redes Sociales --}}
                                <div class="col-span-6 border-t border-gray-100 mt-4 pt-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-4">Contacto y Redes</h4>
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                    <input type="text" wire:model="whatsapp_number" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="999888777">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Instagram</label>
                                    <input type="text" wire:model="instagram_url" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="URL completa">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">TikTok</label>
                                    <input type="text" wire:model="tiktok_url" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="URL completa">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Facebook</label>
                                    <input type="text" wire:model="facebook_url" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="URL completa">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Sitio Web</label>
                                    <input type="text" wire:model="website_url" class="mt-1 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="https://...">
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none">
                                    Guardar Perfil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TAB: HORARIOS -->
                <div x-show="activeTab === 'schedule'" style="display: none;" class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 pb-2 border-b">Horarios de Atención</h3>
                        
                        <form wire:submit.prevent="save">
                            @if($selectedRole === 'veterinarian')
                                <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="shrink-0 text-red-500">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">¿Atiendes Emergencias 24 Horas?</h3>
                                            <p class="text-xs text-red-700">Tu perfil tendrá un distintivo especial.</p>
                                        </div>
                                    </div>
                                    <div>
                                        <input wire:model="emergency_24h" type="checkbox" class="h-5 w-5 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-4">
                                @foreach(['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 'thursday' => 'Jueves', 'friday' => 'Viernes', 'saturday' => 'Sábado', 'sunday' => 'Domingo'] as $key => $label)
                                    <div class="flex items-center justify-between border-b pb-2 last:border-0 hover:bg-gray-50 p-2 rounded">
                                        <div class="flex items-center w-1/4">
                                            <input type="checkbox" wire:model.live="availability.{{ $key }}.active" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                            <label class="ml-2 block text-sm font-medium text-gray-900">{{ $label }}</label>
                                        </div>
                                        <div class="flex items-center space-x-2 w-3/4 justify-end">
                                            @if($availability[$key]['active'] ?? false)
                                                <input type="time" wire:model="availability.{{ $key }}.start" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block sm:text-sm border-gray-300 rounded-md">
                                                <span class="text-gray-500 text-sm">a</span>
                                                <input type="time" wire:model="availability.{{ $key }}.end" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block sm:text-sm border-gray-300 rounded-md">
                                            @else
                                                <span class="text-sm text-gray-400 italic">Cerrado</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none">
                                    Guardar Horarios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TAB: CALENDARIO -->
                <div x-show="activeTab === 'calendar'" style="display: none;" class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <livewire:dashboard.visual-calendar />
                    </div>
                </div>

                <!-- TAB: PORTAFOLIO -->
                <div x-show="activeTab === 'portfolio'" style="display: none;" class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 pb-2 border-b">Mi Portafolio</h3>
                        
                        <form wire:submit.prevent="uploadImage" class="mb-8 p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-end">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Título</label>
                                    <input type="text" wire:model="imageTitle" class="mt-1 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Imagen</label>
                                    <input type="file" wire:model="newImage" class="mt-1 block w-full text-sm">
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700" wire:loading.attr="disabled">Subir Foto</button>
                            </div>
                        </form>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse($portfolioImages as $image)
                                <div class="relative group aspect-w-10 aspect-h-7 bg-gray-200 rounded-lg overflow-hidden">
                                     <img src="{{ \Illuminate\Support\Facades\Storage::url($image->image_path) }}" class="object-cover w-full h-full">
                                     <button wire:click="deleteImage({{ $image->id }})" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition">
                                         <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                     </button>
                                     @if($image->title) <div class="absolute bottom-0 w-full bg-black bg-opacity-50 text-white text-xs p-1 truncate">{{ $image->title }}</div> @endif
                                </div>
                            @empty
                                <div class="col-span-full py-12 text-center text-gray-500">Sin fotos aún.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- TAB: SERVICIOS -->
                <div x-show="activeTab === 'services'" style="display: none;" class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 pb-2 border-b">Catálogo de Servicios</h3>
                        
                        <!-- Formulario de Servicio -->
                        <form wire:submit.prevent="saveService" class="mb-8 p-6 bg-gray-50 rounded-xl border border-gray-200">
                            <h4 class="text-sm font-bold text-gray-900 mb-4">{{ $isEditingService ? 'Editar Servicio' : 'Agregar Nuevo Servicio' }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700">Nombre del Servicio *</label>
                                    <input type="text" wire:model="serviceName" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-2 px-3" placeholder="Ej: Baño y corte de pelo mediano">
                                    @error('serviceName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700">Descripción</label>
                                    <textarea wire:model="serviceDescription" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-2 px-3" placeholder="Detalle qué incluye el servicio (shampoo medicado, corte de uñas, etc.)"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Precio (S/) *</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">S/</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" wire:model="servicePrice" class="block w-full pl-8 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-2 px-3" placeholder="0.00">
                                    </div>
                                    @error('servicePrice') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700">Duración Aproximada (minutos)</label>
                                    <input type="number" wire:model="serviceDuration" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-2 px-3" placeholder="Ej: 60">
                                    @error('serviceDuration') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end gap-2">
                                @if($isEditingService)
                                    <button type="button" wire:click="resetServiceForm" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium">Cancelar</button>
                                @endif
                                <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-bold shadow-sm">{{ $isEditingService ? 'Actualizar' : 'Agregar al Catálogo' }}</button>
                            </div>
                        </form>

                        <!-- Tabla de Servicios -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Servicio</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Duración</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Precio</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($providerServices as $service)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $service->name }}</div>
                                                @if($service->description)
                                                    <div class="text-xs text-gray-500 max-w-xs truncate">{{ $service->description }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $service->duration_minutes ? $service->duration_minutes . ' min' : '--' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm font-extrabold text-gray-900">
                                                S/ {{ number_format($service->price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                                <button wire:click="editService({{ $service->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold">Editar</button>
                                                <button wire:click="deleteService({{ $service->id }})" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 italic">No tienes servicios registrados en tu catálogo. Agrega uno arriba.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB: CONFIGURACIÓN PAGO -->
                <div x-show="activeTab === 'payments_config'" style="display: none;" class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-150">
                    <div class="px-6 py-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <span class="text-3xl">💳</span>
                            <div>
                                <h3 class="text-xl font-black text-gray-900">Métodos de Cobro Directo</h3>
                                <p class="text-xs text-gray-550 mt-1">Configura tus cuentas de Yape y Plin para que los clientes puedan transferirte al instante.</p>
                            </div>
                        </div>
                        
                        <!-- Guía rápida de ayuda -->
                        <div class="mb-8 bg-gradient-to-r from-primary-50 to-indigo-50/50 p-5 rounded-2xl border border-primary-100/60 flex flex-col md:flex-row gap-4 items-start md:items-center">
                            <div class="p-3 bg-white rounded-xl shadow-xs text-xl">💡</div>
                            <div class="flex-1 space-y-1">
                                <h4 class="text-sm font-bold text-gray-900">¿Cómo descargar tus códigos QR?</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1 text-xs text-gray-600">
                                    <div class="flex gap-2">
                                        <span class="font-black text-purple-600">Yape:</span>
                                        <p>Entra a Yape, ve al menú superior ☰, selecciona <strong class="text-gray-900">Código QR</strong> y pulsa <strong class="text-gray-900">Compartir o Descargar</strong>.</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <span class="font-black text-teal-600">Plin:</span>
                                        <p>Abre tu app móvil de Interbank, BBVA, Banbif o Scotiabank, busca la sección <strong class="text-gray-900">Plin</strong>, entra a <strong class="text-gray-900">Mi QR</strong> y descárgalo.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form wire:submit.prevent="save" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Yape -->
                                <div class="bg-purple-50/40 p-6 rounded-3xl border border-purple-100/60 space-y-4 hover:shadow-xs transition duration-300">
                                    <div class="flex items-center justify-between pb-3 border-b border-purple-100/40">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-8 h-8 rounded-xl bg-purple-600/10 text-purple-700 flex items-center justify-center font-bold">Y</span>
                                            <h4 class="font-black text-purple-950">Cobros con Yape</h4>
                                        </div>
                                        <span class="text-xs bg-purple-100 text-purple-800 font-bold px-2 py-0.5 rounded-full">Yapear</span>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-black uppercase tracking-wider text-purple-900">Número de celular asociado</label>
                                        <input type="text" wire:model="yape_number" class="w-full rounded-xl border-purple-200/80 bg-white/70 shadow-xs focus:border-purple-500 focus:ring-purple-500 text-sm py-2.5 px-4" placeholder="Ej: 999 888 777">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-xs font-black uppercase tracking-wider text-purple-900">Código QR de Yape</label>
                                        @if($existingYapeQr)
                                            <div class="relative group my-2 h-44 w-44 mx-auto rounded-2xl border border-purple-200 bg-white p-2 shadow-sm overflow-hidden">
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($existingYapeQr) }}" class="h-full w-full object-contain rounded-xl">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition duration-200">
                                                    <span class="text-[10px] text-white font-bold bg-purple-600 px-3 py-1.5 rounded-full shadow-md">QR Registrado</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-44 w-44 mx-auto border-2 border-dashed border-purple-200 bg-white/50 rounded-2xl flex flex-col items-center justify-center gap-2 text-purple-400 p-4">
                                                <span class="text-3xl">📷</span>
                                                <span class="text-[10px] font-bold text-center">Sube tu imagen QR de Yape</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center justify-center mt-3">
                                            <label class="cursor-pointer bg-white py-2 px-4 border border-purple-200 rounded-xl shadow-xs text-xs font-bold text-purple-700 hover:bg-purple-50 transition">
                                                <span>Seleccionar imagen QR</span>
                                                <input type="file" wire:model.live="yape_qr" class="hidden" accept="image/*">
                                            </label>
                                        </div>
                                        @error('yape_qr') <span class="text-red-500 text-xs text-center block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Plin -->
                                <div class="bg-teal-50/40 p-6 rounded-3xl border border-teal-100/60 space-y-4 hover:shadow-xs transition duration-300">
                                    <div class="flex items-center justify-between pb-3 border-b border-teal-100/40">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-8 h-8 rounded-xl bg-teal-600/10 text-teal-700 flex items-center justify-center font-bold">P</span>
                                            <h4 class="font-black text-teal-955">Cobros con Plin</h4>
                                        </div>
                                        <span class="text-xs bg-teal-100 text-teal-800 font-bold px-2 py-0.5 rounded-full">Plinear</span>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block text-xs font-black uppercase tracking-wider text-teal-900">Número de celular asociado</label>
                                        <input type="text" wire:model="plin_number" class="w-full rounded-xl border-teal-200/80 bg-white/70 shadow-xs focus:border-teal-500 focus:ring-teal-500 text-sm py-2.5 px-4" placeholder="Ej: 999 888 777">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-xs font-black uppercase tracking-wider text-teal-900">Código QR de Plin</label>
                                        @if($existingPlinQr)
                                            <div class="relative group my-2 h-44 w-44 mx-auto rounded-2xl border border-teal-200 bg-white p-2 shadow-sm overflow-hidden">
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($existingPlinQr) }}" class="h-full w-full object-contain rounded-xl">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition duration-200">
                                                    <span class="text-[10px] text-white font-bold bg-teal-600 px-3 py-1.5 rounded-full shadow-md">QR Registrado</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="h-44 w-44 mx-auto border-2 border-dashed border-teal-200 bg-white/50 rounded-2xl flex flex-col items-center justify-center gap-2 text-teal-400 p-4">
                                                <span class="text-3xl">📷</span>
                                                <span class="text-[10px] font-bold text-center">Sube tu imagen QR de Plin</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center justify-center mt-3">
                                            <label class="cursor-pointer bg-white py-2 px-4 border border-teal-200 rounded-xl shadow-xs text-xs font-bold text-teal-700 hover:bg-teal-50 transition">
                                                <span>Seleccionar imagen QR</span>
                                                <input type="file" wire:model.live="plin_qr" class="hidden" accept="image/*">
                                            </label>
                                        </div>
                                        @error('plin_qr') <span class="text-red-500 text-xs text-center block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-5 border-t border-gray-100">
                                <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-200">
                                    Guardar Configuración
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TAB: ESTADÍSTICAS -->
                <div x-show="activeTab === 'stats'" style="display: none;" class="space-y-8" x-transition>
                    <!-- Cards Grid Premium -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Ingresos Totales -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200/80 flex items-center gap-4 hover:shadow-md transition">
                            <span class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 font-extrabold text-2xl flex items-center justify-center">💵</span>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Histórico de Ingresos</p>
                                <p class="text-2xl font-black text-gray-900 mt-1">S/ {{ number_format($totalEarnings, 2) }}</p>
                            </div>
                        </div>

                        <!-- Ingresos Mes -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200/80 flex items-center gap-4 hover:shadow-md transition">
                            <span class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 font-extrabold text-2xl flex items-center justify-center">📈</span>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Ingresos de este Mes</p>
                                <p class="text-2xl font-black text-gray-900 mt-1">S/ {{ number_format($monthlyEarnings, 2) }}</p>
                            </div>
                        </div>

                        <!-- Citas Completadas -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200/80 flex items-center gap-4 hover:shadow-md transition">
                            <span class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 font-extrabold text-2xl flex items-center justify-center">✓</span>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Servicios Completados</p>
                                <p class="text-2xl font-black text-gray-900 mt-1">{{ $completedAppointmentsCount }}</p>
                            </div>
                        </div>

                        <!-- Valoración -->
                        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200/80 flex items-center gap-4 hover:shadow-md transition">
                            <span class="w-12 h-12 rounded-2xl bg-yellow-50 text-yellow-500 font-extrabold text-2xl flex items-center justify-center">★</span>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Calificación Promedio</p>
                                <p class="text-2xl font-black text-gray-900 mt-1">{{ $averageRating }} / 5.0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos Interactivos de Rendimiento (CSS) -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Tasa de aceptación y rendimiento de citas -->
                        <div class="bg-white rounded-3xl p-6 border border-gray-200/80 shadow-xs flex flex-col justify-between">
                            <div>
                                <h4 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-4">Eficiencia del Servicio</h4>
                                <div class="space-y-5">
                                    <!-- Aceptación -->
                                    <div>
                                        <div class="flex justify-between items-center text-xs font-bold text-gray-600 mb-1">
                                            <span>Tasa de Confirmación</span>
                                            <span>{{ $acceptanceRate }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2">
                                            <div class="bg-primary-600 h-2 rounded-full transition-all" style="width: {{ $acceptanceRate }}%"></div>
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-1">Porcentaje de citas aprobadas frente al total de solicitudes recibidas.</p>
                                    </div>

                                    <!-- Completado -->
                                    @php
                                        $totalApts = $completedAppointmentsCount + $activeAppointmentsCount;
                                        $completionRate = $totalApts > 0 ? round(($completedAppointmentsCount / $totalApts) * 100) : 100;
                                    @endphp
                                    <div>
                                        <div class="flex justify-between items-center text-xs font-bold text-gray-600 mb-1">
                                            <span>Tasa de Finalización</span>
                                            <span>{{ $completionRate }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2">
                                            <div class="bg-emerald-500 h-2 rounded-full transition-all" style="width: {{ $completionRate }}%"></div>
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-1">Porcentaje de citas finalizadas con éxito del total de citas agendadas.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Historial Financiero Desglose -->
                        <div class="bg-white rounded-3xl p-6 border border-gray-200/80 shadow-xs lg:col-span-2">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-4">Balance General</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                                    <span class="text-xs font-bold text-gray-600">Ingresos del Mes de {{ now()->translatedFormat('F') }}</span>
                                    <span class="text-sm font-black text-gray-900">S/ {{ number_format($monthlyEarnings, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                                    <span class="text-xs font-bold text-gray-600">Transacciones Completadas</span>
                                    <span class="text-sm font-black text-gray-900">{{ $recentPayments->where('status', 'completed')->count() }} trans.</span>
                                </div>
                                <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                                    <span class="text-xs font-bold text-gray-600">Promedio por Servicio</span>
                                    @php
                                        $avgPrice = $completedAppointmentsCount > 0 ? ($totalEarnings / $completedAppointmentsCount) : 0;
                                    @endphp
                                    <span class="text-sm font-black text-gray-900">S/ {{ number_format($avgPrice, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Payments -->
                    <div class="bg-white border border-gray-200/80 shadow-xs rounded-3xl overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Historial de Cobros Recientes</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente</th>
                                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Método de Pago</th>
                                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Monto</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($recentPayments as $payment)
                                        <tr class="hover:bg-gray-50/30 transition">
                                            <td class="px-6 py-4 text-sm font-bold text-gray-955">
                                                {{ $payment->appointment->client->name ?? 'Cliente' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 uppercase font-mono">
                                                {{ $payment->payment_method }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                    {{ $payment->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($payment->status === 'under_review' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-red-50 text-red-700 border border-red-100') }}">
                                                    {{ $payment->status === 'completed' ? 'Completado' : ($payment->status === 'under_review' ? 'En Revisión' : 'Fallido') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $payment->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-black text-gray-900">
                                                S/ {{ number_format($payment->amount, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-gray-550 bg-white">No se registran transacciones aún.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- TAB: RESEÑAS RECIBIDAS -->
                <div x-show="activeTab === 'reviews'" style="display: none;" class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 pb-2 border-b">Reseñas Recibidas</h3>
                        <p class="text-sm text-gray-500 mb-6">Aquí puedes leer las opiniones de tus clientes y responder a sus comentarios de manera pública.</p>

                        <div class="space-y-6 divide-y divide-gray-100">
                            @forelse($receivedReviews as $review)
                                <div class="pt-6 first:pt-0 space-y-4">
                                    <div class="flex items-start gap-4">
                                        <div class="shrink-0">
                                            <img class="h-10 w-10 rounded-full bg-gray-200" src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                        </div>
                                        <div class="flex-1 space-y-1">
                                            <div class="flex items-center justify-between">
                                                <h5 class="text-sm font-bold text-gray-900">{{ $review->user->name }}</h5>
                                                <span class="text-xs text-gray-400 font-semibold">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                @for($i=1; $i<=5; $i++)
                                                    <svg class="w-4 h-4 {{ $review->rating >= $i ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                            <p class="text-sm text-gray-600 font-medium italic">"{{ $review->comment }}"</p>
                                        </div>
                                    </div>

                                    <!-- Formulario / Visualización de Respuesta -->
                                    <div class="ml-14 bg-gray-50/50 p-4 rounded-2xl border border-gray-100 space-y-3">
                                        @if($review->provider_response)
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="space-y-1">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-bold text-gray-900">Tu respuesta</span>
                                                        <span class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($review->replied_at)->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs text-gray-600 font-medium">{{ $review->provider_response }}</p>
                                                </div>
                                                <button wire:click="deleteReply({{ $review->id }})" class="text-xs text-red-500 hover:text-red-700 font-bold transition shrink-0">
                                                    Eliminar
                                                </button>
                                            </div>
                                        @else
                                            <div class="space-y-2">
                                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Responder comentario</label>
                                                <div class="flex gap-2">
                                                    <input type="text" wire:model="replyText.{{ $review->id }}" placeholder="Escribe tu respuesta..." class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-xs py-2 px-3">
                                                    <button wire:click="submitReply({{ $review->id }})" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-xs font-bold transition">
                                                        Responder
                                                    </button>
                                                </div>
                                                @error('replyText.' . $review->id) <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 text-gray-500 italic">No has recibido reseñas todavía.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.initLeafletMapForDashboard = function(alpineComponent) {
        let lat = @this.get('latitude') || -12.046374;
        let lng = @this.get('longitude') || -77.042793;
        let zoom = @this.get('latitude') ? 16 : 12;

        const container = L.DomUtil.get('dashboard-map');
        if (container != null) {
            container._leaflet_id = null;
        }

        alpineComponent.map = L.map('dashboard-map').setView([lat, lng], zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(alpineComponent.map);

        let providerIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        alpineComponent.marker = L.marker([lat, lng], {icon: providerIcon, draggable: true}).addTo(alpineComponent.map);

        alpineComponent.marker.on('dragend', (e) => {
            let position = alpineComponent.marker.getLatLng();
            @this.set('latitude', position.lat.toFixed(8));
            @this.set('longitude', position.lng.toFixed(8));
        });

        alpineComponent.map.on('click', (e) => {
            let clickedLat = e.latlng.lat;
            let clickedLng = e.latlng.lng;
            alpineComponent.marker.setLatLng([clickedLat, clickedLng]);
            @this.set('latitude', clickedLat.toFixed(8));
            @this.set('longitude', clickedLng.toFixed(8));
        });
    }
</script>
@endpush
