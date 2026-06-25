<div class="py-12"
    x-data="{
        toastShow: false,
        toastMsg: '',
        toastType: 'success',
        showToast(msg, type = 'success') {
            this.toastMsg = msg;
            this.toastType = type;
            this.toastShow = true;
            setTimeout(() => this.toastShow = false, 3500);
        }
    }"
    @notify.window="showToast($event.detail.message, $event.detail.type ?? 'success')"
    x-on:livewire:navigated.window="toastShow = false"
>
    <!-- Toast Notificación Global -->
    <div x-show="toastShow" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-6 right-6 z-[9999] max-w-sm w-full pointer-events-none"
        style="display: none;">
        <div :class="toastType === 'success' ? 'bg-emerald-600 border-emerald-500' : (toastType === 'error' ? 'bg-red-600 border-red-500' : 'bg-sky-600 border-sky-500')"
            class="flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl border text-white">
            <template x-if="toastType === 'success'">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </template>
            <template x-if="toastType === 'error'">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </template>
            <template x-if="toastType === 'info'">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </template>
            <p class="text-sm font-bold flex-1" x-text="toastMsg"></p>
            <button @click="toastShow = false" class="text-white/70 hover:text-white transition pointer-events-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
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

        @if($mainSection === 'panel')
        <div wire:key="main-section-panel-container">
            <div wire:key="provider-panel-header">
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
            $roleIcons = [
                'veterinarian' => '🩺', 'walker' => '🐕', 'groomer' => '✂️',
                'hotel' => '🏨', 'shelter' => '🏠', 'trainer' => '🎓',
                'pet_sitter' => '🐾', 'pet_taxi' => '🚗', 'pet_photographer' => '📸',
            ];
        @endphp

        @if(count($userActiveRoles) > 1 || count($inactiveRoles) > 0)
        <div class="mb-8 bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-1.5 bg-gray-200/50 text-gray-700 px-3 py-1.5 rounded-xl text-xs font-extrabold uppercase tracking-wider shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Mis Servicios
                    </div>
                    
                    <div class="flex items-center gap-2.5 flex-wrap">
                        @foreach($userActiveRoles as $role)
                            <div class="inline-flex items-center rounded-xl border transition-all duration-300 overflow-hidden shrink-0 group
                                {{ $selectedRole === $role 
                                    ? 'border-primary-600 bg-gradient-to-r from-primary-600 to-indigo-600 shadow-md shadow-primary-500/20 scale-[1.02]' 
                                    : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm hover:scale-[1.01]' }}">
                                
                                <!-- Botón de seleccionar -->
                                <button wire:click="selectRole('{{ $role }}')"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold transition-all duration-200 focus:outline-none cursor-pointer whitespace-nowrap
                                    {{ $selectedRole === $role 
                                        ? 'text-white' 
                                        : 'text-gray-750 hover:bg-gray-50' }}">
                                    <span class="text-base transform group-hover:scale-110 transition duration-300">{{ $roleIcons[$role] ?? '📋' }}</span>
                                    <span>{{ $providerRoles[$role] }}</span>
                                </button>
                                
                                <!-- Botón de desactivar (solo si hay más de 1) -->
                                @if(count($userActiveRoles) > 1)
                                    <button onclick="confirm('¿Estás seguro de que deseas desactivar el servicio de {{ $providerRoles[$role] }}? Se eliminarán los datos asociados a este perfil.') || event.stopImmediatePropagation()"
                                        wire:click="deactivateRole('{{ $role }}')"
                                        class="px-2.5 py-2 border-l text-sm font-bold transition-all duration-200 focus:outline-none cursor-pointer
                                        {{ $selectedRole === $role 
                                            ? 'border-white/10 text-white/70 hover:bg-black/10 hover:text-white' 
                                            : 'border-gray-150 text-red-500 hover:bg-red-50' }}"
                                        title="Desactivar {{ $providerRoles[$role] }}">
                                        <svg class="w-4 h-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                @if(count($inactiveRoles) > 0)
                <div x-data="{ showAddRole: false }" class="relative shrink-0">
                    <button @click="showAddRole = !showAddRole" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-extrabold rounded-xl border border-dashed border-primary-300 text-primary-600 bg-primary-50 hover:bg-primary-100 hover:border-primary-400 transition-all shadow-xs focus:outline-none">
                        <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Ofrecer otro servicio
                    </button>
                    <div x-show="showAddRole" @click.away="showAddRole = false" x-transition
                         class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 py-2">
                        <div class="px-4 py-1.5 border-b border-gray-100 mb-1">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Servicios disponibles</span>
                        </div>
                        @foreach($inactiveRoles as $role)
                            <button wire:click="activateRole('{{ $role }}')" @click="showAddRole = false"
                                class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors flex items-center gap-2.5 font-bold">
                                <span class="text-base">{{ $roleIcons[$role] ?? '📋' }}</span>
                                {{ $providerRoles[$role] }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" wire:key="provider-panel-grid font-sans">
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
                            <a href="{{ $user->profileUrl() }}" target="_blank" class="text-sm font-medium text-primary-600 hover:text-primary-500 flex items-center justify-center mt-1">
                                Ver Perfil Público 
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                            <div x-data="{ copied: false }" class="mt-4 w-full bg-primary-50/50 rounded-2xl p-3 border border-primary-100 flex flex-col gap-2 shadow-xs">
                                <div class="text-left">
                                    <p class="text-[9px] uppercase font-black tracking-widest text-primary-850">Comparte tu Perfil</p>
                                    <p class="text-[10px] text-gray-500 truncate mt-0.5">{{ $user->profileUrl() }}</p>
                                </div>
                                <button @click="navigator.clipboard.writeText('{{ $user->profileUrl() }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        type="button"
                                        class="w-full py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-black transition flex items-center justify-center gap-1 shadow-sm">
                                    <span x-show="!copied">📋 Copiar Enlace</span>
                                    <span x-show="copied" style="display: none;" class="text-green-200">✓ Enlace Copiado</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navegación Vertical -->
                    <nav class="mt-6 space-y-0.5">

                        <!-- Perfil y Contacto -->
                        <button wire:click="switchTab('profile')"
                            class="group w-full flex items-center gap-3 px-3 py-2.5 text-sm border-l-4 rounded-r-lg transition-all duration-150 text-left {{ $activeTab === 'profile' ? 'bg-primary-50 text-primary-700 border-primary-500 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-transparent' }}">
                            <svg class="shrink-0 w-5 h-5 {{ $activeTab === 'profile' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Perfil y Contacto</span>
                        </button>

                        <!-- Horarios -->
                        <button wire:click="switchTab('schedule')"
                            class="group w-full flex items-center gap-3 px-3 py-2.5 text-sm border-l-4 rounded-r-lg transition-all duration-150 text-left {{ $activeTab === 'schedule' ? 'bg-primary-50 text-primary-700 border-primary-500 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-transparent' }}">
                            <svg class="shrink-0 w-5 h-5 {{ $activeTab === 'schedule' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Horarios de Atención</span>
                        </button>


                        <!-- Portafolio -->
                        <button wire:click="switchTab('portfolio')"
                            class="group w-full flex items-center gap-3 px-3 py-2.5 text-sm border-l-4 rounded-r-lg transition-all duration-150 text-left {{ $activeTab === 'portfolio' ? 'bg-primary-50 text-primary-700 border-primary-500 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-transparent' }}">
                            <svg class="shrink-0 w-5 h-5 {{ $activeTab === 'portfolio' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>Mi Portafolio</span>
                        </button>

                        <!-- Catálogo de Servicios -->
                        <button wire:click="switchTab('services')"
                            class="group w-full flex items-center gap-3 px-3 py-2.5 text-sm border-l-4 rounded-r-lg transition-all duration-150 text-left {{ $activeTab === 'services' ? 'bg-primary-50 text-primary-700 border-primary-500 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-transparent' }}">
                            <svg class="shrink-0 w-5 h-5 {{ $activeTab === 'services' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Catálogo de Servicios</span>
                        </button>

                        <!-- Cobros -->
                        <button wire:click="switchTab('payments_config')"
                            class="group w-full flex items-center gap-3 px-3 py-2.5 text-sm border-l-4 rounded-r-lg transition-all duration-150 text-left {{ $activeTab === 'payments_config' ? 'bg-primary-50 text-primary-700 border-primary-500 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-transparent' }}">
                            <svg class="shrink-0 w-5 h-5 {{ $activeTab === 'payments_config' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span>Cobros Yape/Plin</span>
                        </button>

                        <!-- Estadísticas -->
                        <button wire:click="switchTab('stats')"
                            class="group w-full flex items-center gap-3 px-3 py-2.5 text-sm border-l-4 rounded-r-lg transition-all duration-150 text-left {{ $activeTab === 'stats' ? 'bg-primary-50 text-primary-700 border-primary-500 font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-transparent' }}">
                            <svg class="shrink-0 w-5 h-5 {{ $activeTab === 'stats' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Estadísticas</span>
                        </button>


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

                @if (session()->has('error'))
                    <div class="rounded-md bg-red-50 p-4 border border-red-200 mb-4">
                        <div class="flex">
                            <div class="shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
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
                @endif

                <!-- ONBOARDING CHECKLIST -->
                @if($completenessScore < 100)
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-2xl border border-indigo-100/70 shadow-sm space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h4 class="text-base font-black text-indigo-955 flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-xl bg-indigo-600 shadow-sm">
                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </span>
                                    ¡Completa tu perfil profesional!
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
                                <button wire:click="switchTab('{{ $item['tab'] }}')" class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-indigo-100/50 text-left hover:shadow-sm hover:border-indigo-200 transition">
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

                <!-- Alertas de Cuentas y Pagos Pendientes -->
                @php
                    $underReviewCount = \App\Models\Appointment::where('provider_id', $user->id)
                        ->whereHas('payment', function($qp) {
                            $qp->where('status', 'under_review');
                        })->count();
                    $pendingPaymentsCount = \App\Models\Appointment::where('provider_id', $user->id)
                        ->whereIn('status', ['confirmed', 'completed'])
                        ->whereHas('payment', function($qp) {
                            $qp->whereIn('status', ['pending', 'failed']);
                        })->count();
                @endphp

                @if($underReviewCount > 0)
                    <div class="mb-6 bg-indigo-50 border border-indigo-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-[0_4px_12px_rgba(79,70,229,0.05)] transition duration-200 hover:shadow-[0_6px_16px_rgba(79,70,229,0.08)]">
                        <div class="flex items-start gap-4 text-indigo-900">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 shadow-xs shrink-0 border border-indigo-200/50">
                                <svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            <div class="text-left">
                                <h4 class="font-extrabold text-sm text-indigo-955">Tienes pagos pendientes de revisión</h4>
                                <p class="text-xs text-indigo-700 mt-1 leading-relaxed font-medium">Hay {{ $underReviewCount }} {{ $underReviewCount === 1 ? 'cliente que ha subido su comprobante' : 'clientes que han subido sus comprobantes' }} de transferencia Yape/Plin. Por favor, revísalos para confirmarlos.</p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard.provider', ['section' => 'appointments', 'status' => 'payment_under_review']) }}"
                            class="shrink-0 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl shadow-sm transition hover:scale-[1.02] flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Revisar Pagos
                        </a>
                    </div>
                @endif

                @if($pendingPaymentsCount > 0)
                    <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-[0_4px_12px_rgba(217,119,6,0.05)] transition duration-200 hover:shadow-[0_6px_16px_rgba(217,119,6,0.08)]">
                        <div class="flex items-start gap-4 text-amber-900">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 shadow-xs shrink-0 border border-amber-200/50">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </span>
                            <div class="text-left">
                                <h4 class="font-extrabold text-sm text-amber-955">Servicios completados/confirmados sin registrar pago</h4>
                                <p class="text-xs text-amber-700 mt-1 leading-relaxed font-medium">Tienes {{ $pendingPaymentsCount }} {{ $pendingPaymentsCount === 1 ? 'cita donde el cliente aún no ha realizado' : 'citas donde los clientes aún no han realizado' }} el pago de Yape/Plin.</p>
                            </div>
                        </div>
                        <a href="{{ route('dashboard.provider', ['section' => 'appointments', 'status' => 'payment_pending']) }}"
                            class="shrink-0 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-black rounded-xl shadow-sm transition hover:scale-[1.02] flex items-center justify-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Ver Citas por Cobrar
                        </a>
                    </div>
                @endif

                <!-- TAB: PERFIL -->
                @if($activeTab === 'profile')
                <div class="space-y-6">
                    <!-- Widget Hoy de un vistazo -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Citas de hoy -->
                        <div class="bg-gradient-to-br from-indigo-950 to-slate-900 text-white rounded-3xl p-6 md:col-span-2 shadow-md flex flex-col justify-between min-h-[180px]">
                            <div>
                                <div class="flex justify-between items-start">
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-white/20 text-indigo-100 border border-white/10 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        HOY DE UN VISTAZO
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
                                                <span class="text-primary-400 shrink-0" title="Mascota">
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="4.5" cy="10.5" r="2.5"/>
                                                        <circle cx="9" cy="6" r="2.5"/>
                                                        <circle cx="15" cy="6" r="2.5"/>
                                                        <circle cx="19.5" cy="10.5" r="2.5"/>
                                                        <path d="M12 10.5c-2.485 0-4.5 2.015-4.5 4.5 0 2.22 1.455 4.103 3.456 4.757l.006.002.5.5.5-.5c2.001-.654 3.456-2.537 3.456-4.759 0-2.485-2.015-4.5-4.5-4.5z"/>
                                                    </svg>
                                                </span>
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
                                <a href="{{ route('dashboard.provider', ['section' => 'appointments']) }}" class="text-xs font-bold text-white hover:underline flex items-center gap-1">
                                    Administrar todas mis citas <span>➔</span>
                                </a>
                            </div>
                        </div>

                        <!-- Ingresos mensuales y KPI de Aceptación -->
                        <div class="bg-white rounded-3xl p-6 border border-gray-200/80 shadow-xs flex flex-col justify-between min-h-[180px]">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-gray-400">Ingresos del Mes</span>
                                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
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
                @elseif($activeTab === 'schedule')
                <div class="space-y-6">
                    <!-- Header premium -->
                    <div class="bg-gradient-to-r from-sky-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center shadow-inner">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black">Horarios de Atención</h3>
                                <p class="text-sky-100 text-xs mt-0.5">Define los días y horas en que recibes clientes. Los clientes verán esta información en tu perfil.</p>
                            </div>
                        </div>
                    </div>

                    @if(empty($district_id))
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/85 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 text-left">
                                    <h4 class="text-sm font-black text-amber-900">Ubicación requerida en tu Perfil</h4>
                                    <p class="text-xs text-amber-750 mt-1">Para poder guardar tu horario o configurar otros datos, primero debes definir tu **Ubicación (Departamento, Provincia y Distrito)** en la pestaña de <strong>Perfil y Contacto</strong>. Sin esto, no podrás guardar los cambios.</p>
                                    <button type="button" wire:click="switchTab('profile')" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                        Ir a Perfil y Contacto
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form wire:submit.prevent="save" class="space-y-4" @if(empty($district_id)) onsubmit="return false;" @endif>
                        @if($selectedRole === 'veterinarian')
                            <div class="flex items-center justify-between bg-red-50 border border-red-200 rounded-2xl p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-red-900">¿Atiendes Emergencias 24 Horas?</h4>
                                        <p class="text-xs text-red-700">Tu perfil tendrá un distintivo especial visible para todos los clientes.</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input wire:model="emergency_24h" type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                                </label>
                            </div>
                        @endif

                        @php
                            $dayIcons = [
                                'monday'    => ['letter' => 'L', 'color' => 'indigo'],
                                'tuesday'   => ['letter' => 'M', 'color' => 'violet'],
                                'wednesday' => ['letter' => 'X', 'color' => 'purple'],
                                'thursday'  => ['letter' => 'J', 'color' => 'sky'],
                                'friday'    => ['letter' => 'V', 'color' => 'blue'],
                                'saturday'  => ['letter' => 'S', 'color' => 'emerald'],
                                'sunday'    => ['letter' => 'D', 'color' => 'rose'],
                            ];
                        @endphp

                        <div class="grid grid-cols-1 gap-3">
                            @foreach(['monday' => 'Lunes', 'tuesday' => 'Martes', 'wednesday' => 'Miércoles', 'thursday' => 'Jueves', 'friday' => 'Viernes', 'saturday' => 'Sábado', 'sunday' => 'Domingo'] as $key => $label)
                                @php $isActive = $availability[$key]['active'] ?? false; @endphp
                                <div class="flex items-center gap-4 p-4 rounded-2xl border transition-all duration-200
                                    {{ $isActive ? 'bg-sky-50/60 border-sky-200 shadow-sm' : 'bg-white border-gray-100 hover:border-gray-200' }}">

                                    <!-- Día letra -->
                                    <div class="w-10 h-10 rounded-xl shrink-0 flex items-center justify-center font-black text-sm
                                        {{ $isActive ? 'bg-sky-600 text-white shadow-sm' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $dayIcons[$key]['letter'] }}
                                    </div>

                                    <!-- Nombre día + toggle -->
                                    <div class="flex items-center gap-2 w-28 shrink-0">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:model.live="availability.{{ $key }}.active" class="sr-only peer">
                                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-sky-600"></div>
                                        </label>
                                        <span class="text-sm font-bold {{ $isActive ? 'text-sky-900' : 'text-gray-400' }}">{{ $label }}</span>
                                    </div>

                                    <!-- Horas -->
                                    <div class="flex-1 flex items-center gap-3 justify-end">
                                        @if($isActive)
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <input type="time" wire:model="availability.{{ $key }}.start"
                                                    class="text-sm font-bold border-sky-200 rounded-xl bg-white shadow-xs focus:border-sky-500 focus:ring-sky-500 py-1.5 px-3">
                                                <span class="text-gray-400 text-xs font-bold">—</span>
                                                <input type="time" wire:model="availability.{{ $key }}.end"
                                                    class="text-sm font-bold border-sky-200 rounded-xl bg-white shadow-xs focus:border-sky-500 focus:ring-sky-500 py-1.5 px-3">
                                            </div>
                                        @else
                                            <span class="text-xs font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Cerrado</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-200">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Guardar Horarios
                            </button>
                        </div>
                    </form>
                </div>


                <!-- TAB: PORTAFOLIO -->
                @elseif($activeTab === 'portfolio')
                <div class="space-y-6">
                    <!-- Header premium -->
                    <div class="bg-gradient-to-r from-pink-600 to-rose-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center shadow-inner shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-xl font-black">Mi Portafolio</h3>
                                <p class="text-pink-100 text-xs mt-0.5">Muestra tus mejores trabajos. Las fotos generan hasta 3x más confianza en los clientes.</p>
                            </div>
                        </div>
                    </div>

                    @if(empty($district_id))
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/85 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 text-left">
                                    <h4 class="text-sm font-black text-amber-900">Ubicación requerida en tu Perfil</h4>
                                    <p class="text-xs text-amber-750 mt-1">Para poder subir fotos a tu portafolio o configurar otros datos, primero debes definir tu **Ubicación (Departamento, Provincia y Distrito)** en la pestaña de <strong>Perfil y Contacto</strong>. Sin esto, no podrás guardar los cambios.</p>
                                    <button type="button" wire:click="switchTab('profile')" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                        Ir a Perfil y Contacto
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Zona de upload -->
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-wider">Subir Nueva Foto</h4>
                        </div>
                        <form wire:submit.prevent="uploadImage" class="p-6"
                            x-data="{ previewUrl: null, previewName: null }"
                            x-on:livewire-upload-finish="if($wire.newImage) { previewName = 'Imagen lista para subir'; }">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-start">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-1.5">Título de la Foto</label>
                                    <input type="text" wire:model="imageTitle"
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 text-sm py-2.5 px-4"
                                        placeholder="Ej: Corte de raza schnauzer">
                                </div>
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-1.5">Imagen</label>
                                    <label class="relative flex flex-col items-center justify-center w-full rounded-xl border-2 border-dashed border-pink-200 bg-pink-50/40 cursor-pointer hover:bg-pink-50 transition overflow-hidden"
                                        :class="previewUrl ? 'h-32 border-pink-400 border-solid bg-pink-50' : 'h-24'">
                                        <!-- Preview -->
                                        <template x-if="previewUrl">
                                            <div class="absolute inset-0">
                                                <img :src="previewUrl" class="w-full h-full object-cover rounded-xl opacity-80">
                                                <div class="absolute inset-0 flex items-center justify-center bg-black/30 rounded-xl">
                                                    <div class="text-center text-white">
                                                        <svg class="w-6 h-6 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        <span class="text-xs font-black">Imagen seleccionada</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="!previewUrl">
                                            <div class="flex flex-col items-center gap-1">
                                                <svg class="w-8 h-8 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span class="text-xs font-bold text-pink-600">Seleccionar imagen</span>
                                                <span class="text-[10px] text-gray-400">JPG, PNG, WEBP hasta 5MB</span>
                                            </div>
                                        </template>
                                        <input type="file" wire:model="newImage" class="hidden" accept="image/*"
                                            @change="const f = $event.target.files[0]; if(f){ previewUrl = URL.createObjectURL(f); previewName = f.name; }">
                                    </label>
                                    <!-- Estado de carga -->
                                    <div wire:loading wire:target="newImage" class="mt-2 flex items-center gap-2 text-xs text-pink-600 font-bold">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Cargando imagen...
                                    </div>
                                    <!-- Nombre del archivo seleccionado -->
                                    <div wire:loading.remove wire:target="newImage" x-show="previewName" class="mt-2 flex items-center gap-1.5 text-xs text-emerald-700 font-bold bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span x-text="previewName"></span>
                                        <span class="text-emerald-600 font-normal">— Presiona "Subir al Portafolio"</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 flex justify-end">
                                <button type="submit" wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-sm font-bold shadow-md transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    Subir al Portafolio
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Grid de fotos -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($portfolioImages as $image)
                            <div class="relative group rounded-2xl overflow-hidden bg-gray-100 shadow-sm border border-gray-200 aspect-square">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($image->image_path) }}" class="object-cover w-full h-full transition duration-300 group-hover:scale-105">
                                <!-- Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-200">
                                    <div class="absolute bottom-0 left-0 right-0 p-3 flex items-end justify-between">
                                        @if($image->title)
                                            <span class="text-white text-xs font-bold truncate mr-2">{{ $image->title }}</span>
                                        @endif
                                        <button wire:click="deleteImage({{ $image->id }})" class="shrink-0 w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-full flex items-center justify-center shadow-md transition ml-auto">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-16 flex flex-col items-center gap-3 text-gray-400">
                                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-sm font-bold">Aún no tienes fotos en tu portafolio</p>
                                <p class="text-xs">Sube tus mejores trabajos para destacar frente a otros proveedores</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- TAB: SERVICIOS -->
                @elseif($activeTab === 'services')
                <div class="space-y-6">
                    <!-- Header premium -->
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center shadow-inner shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-xl font-black">Catálogo de Servicios</h3>
                                <p class="text-emerald-100 text-xs mt-0.5">Define los servicios que ofreces, sus precios y duración para que los clientes puedan elegir.</p>
                            </div>
                        </div>
                    </div>

                    @if(empty($district_id))
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/85 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 text-left">
                                    <h4 class="text-sm font-black text-amber-900">Ubicación requerida en tu Perfil</h4>
                                    <p class="text-xs text-amber-755 mt-1">Para poder agregar servicios a tu catálogo o configurar otros datos, primero debes definir tu **Ubicación (Departamento, Provincia y Distrito)** en la pestaña de <strong>Perfil y Contacto</strong>. Sin esto, no podrás guardar los cambios.</p>
                                    <button type="button" wire:click="switchTab('profile')" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-755 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                        Ir a Perfil y Contacto
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 sm:p-6">
                        <h3 class="sr-only">Catálogo de Servicios</h3>
                        
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
                            <div class="mt-4 flex justify-start gap-2">
                                @if($isEditingService)
                                    <button type="button" wire:click="resetServiceForm" class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 text-sm font-bold">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Cancelar
                                    </button>
                                @endif
                                <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-sm transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    {{ $isEditingService ? 'Actualizar Servicio' : 'Agregar al Catálogo' }}
                                </button>
                            </div>
                        </form>

                        <!-- Lista de Servicios -->
                        <div class="mt-6">
                            @forelse($providerServices as $service)
                                <div class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 hover:border-emerald-100 hover:bg-emerald-50/20 transition-all mb-3 last:mb-0 group">
                                    <!-- Icono servicio -->
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    </div>
                                    <!-- Info -->
                                    <div class="flex-1 min-w-0 text-left">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $service->name }}</p>
                                        @if($service->description)
                                            <p class="text-xs text-gray-500 truncate mt-0.5">{{ $service->description }}</p>
                                        @endif
                                    </div>
                                    <!-- Duración -->
                                    @if($service->duration_minutes)
                                        <div class="flex items-center gap-1 text-xs text-gray-500 font-bold shrink-0">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $service->duration_minutes }} min
                                        </div>
                                    @endif
                                    <!-- Precio -->
                                    <div class="text-sm font-black text-gray-900 shrink-0 bg-gray-100 px-3 py-1 rounded-full">
                                        S/ {{ number_format($service->price, 2) }}
                                    </div>
                                    <!-- Acciones -->
                                    <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition">
                                        <button wire:click="editService({{ $service->id }})" title="Editar"
                                            class="w-8 h-8 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-600 flex items-center justify-center transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button wire:click="deleteService({{ $service->id }})" title="Eliminar"
                                            class="w-8 h-8 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="py-14 flex flex-col items-center gap-3 text-gray-400">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <p class="text-sm font-bold">No tienes servicios en tu catálogo</p>
                                    <p class="text-xs">Agrega tu primer servicio usando el formulario de arriba</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                </div>

                <!-- TAB: CONFIGURACIÓN PAGO -->
                @elseif($activeTab === 'payments_config')
                <div class="space-y-6">
                    <!-- Header premium -->
                    <div class="bg-gradient-to-r from-purple-600 to-violet-700 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center shadow-inner shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-xl font-black">Cobros Yape / Plin</h3>
                                <p class="text-violet-200 text-xs mt-0.5">Configura tus cuentas para que los clientes puedan transferirte al instante.</p>
                            </div>
                        </div>
                    </div>

                    @if(empty($district_id))
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/85 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 text-left">
                                    <h4 class="text-sm font-black text-amber-900">Ubicación requerida en tu Perfil</h4>
                                    <p class="text-xs text-amber-750 mt-1">Para poder configurar tus métodos de cobro o configurar otros datos, primero debes definir tu **Ubicación (Departamento, Provincia y Distrito)** en la pestaña de <strong>Perfil y Contacto</strong>. Sin esto, no podrás guardar los cambios.</p>
                                    <button type="button" wire:click="switchTab('profile')" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-755 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                        Ir a Perfil y Contacto
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                <div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-100">
                    <div class="px-6 py-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-900">Métodos de Cobro Directo</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Configura tus cuentas de Yape y Plin para cobros rápidos.</p>
                            </div>
                        </div>
                        
                        <!-- Guía rápida de ayuda -->
                        <div class="mb-8 bg-gradient-to-r from-primary-50 to-indigo-50/50 p-5 rounded-2xl border border-primary-100/60 flex flex-col md:flex-row gap-4 items-start md:items-center">
                            <div class="p-3 bg-white rounded-xl shadow-xs">
                                <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
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
                                <div class="bg-purple-50/40 p-6 rounded-3xl border border-purple-100/60 space-y-4 hover:shadow-xs transition duration-300" x-data="{ yapePreview: null }">
                                    <div class="flex items-center justify-between pb-3 border-b border-purple-100/40">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center font-black text-sm shadow-sm">Y</span>
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
                                                    <span class="text-[10px] text-white font-bold bg-purple-600 px-3 py-1.5 rounded-full shadow-md">QR Registrado ✓</span>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Preview antes de subir -->
                                            <div class="my-2 mx-auto" x-show="yapePreview" style="display:none">
                                                <div class="relative h-44 w-44 mx-auto rounded-2xl border-2 border-purple-400 bg-white p-2 shadow-md overflow-hidden">
                                                    <img :src="yapePreview" class="h-full w-full object-contain rounded-xl">
                                                    <div class="absolute top-1 right-1 bg-purple-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full">Lista ✓</div>
                                                </div>
                                            </div>
                                            <div x-show="!yapePreview" class="h-44 w-44 mx-auto border-2 border-dashed border-purple-200 bg-white/50 rounded-2xl flex flex-col items-center justify-center gap-2 text-purple-400 p-4">
                                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                                                <span class="text-[10px] font-bold text-center">Sube tu código QR de Yape</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center justify-center mt-3">
                                            <label class="cursor-pointer bg-white py-2 px-4 border border-purple-200 rounded-xl shadow-xs text-xs font-bold text-purple-700 hover:bg-purple-50 transition flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                                <span>Seleccionar imagen QR</span>
                                                <input type="file" wire:model.live="yape_qr" class="hidden" accept="image/*"
                                                    @change="const f=$event.target.files[0]; if(f) yapePreview=URL.createObjectURL(f)">
                                            </label>
                                        </div>
                                        <div wire:loading wire:target="yape_qr" class="flex items-center justify-center gap-2 text-xs text-purple-600 font-bold mt-1">
                                            <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Cargando QR...
                                        </div>
                                        @error('yape_qr') <span class="text-red-500 text-xs text-center block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Plin -->
                                <div class="bg-teal-50/40 p-6 rounded-3xl border border-teal-100/60 space-y-4 hover:shadow-xs transition duration-300" x-data="{ plinPreview: null }">
                                    <div class="flex items-center justify-between pb-3 border-b border-teal-100/40">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-8 h-8 rounded-xl bg-teal-600 text-white flex items-center justify-center font-black text-sm shadow-sm">P</span>
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
                                                    <span class="text-[10px] text-white font-bold bg-teal-600 px-3 py-1.5 rounded-full shadow-md">QR Registrado ✓</span>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Preview antes de subir -->
                                            <div class="my-2 mx-auto" x-show="plinPreview" style="display:none">
                                                <div class="relative h-44 w-44 mx-auto rounded-2xl border-2 border-teal-400 bg-white p-2 shadow-md overflow-hidden">
                                                    <img :src="plinPreview" class="h-full w-full object-contain rounded-xl">
                                                    <div class="absolute top-1 right-1 bg-teal-600 text-white text-[9px] font-black px-2 py-0.5 rounded-full">Lista ✓</div>
                                                </div>
                                            </div>
                                            <div x-show="!plinPreview" class="h-44 w-44 mx-auto border-2 border-dashed border-teal-200 bg-white/50 rounded-2xl flex flex-col items-center justify-center gap-2 text-teal-400 p-4">
                                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 3.5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                                                <span class="text-[10px] font-bold text-center">Sube tu código QR de Plin</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center justify-center mt-3">
                                            <label class="cursor-pointer bg-white py-2 px-4 border border-teal-200 rounded-xl shadow-xs text-xs font-bold text-teal-700 hover:bg-teal-50 transition flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                                <span>Seleccionar imagen QR</span>
                                                <input type="file" wire:model.live="plin_qr" class="hidden" accept="image/*"
                                                    @change="const f=$event.target.files[0]; if(f) plinPreview=URL.createObjectURL(f)">
                                            </label>
                                        </div>
                                        <div wire:loading wire:target="plin_qr" class="flex items-center justify-center gap-2 text-xs text-teal-600 font-bold mt-1">
                                            <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            Cargando QR...

                                        </div>
                                        @error('plin_qr') <span class="text-red-500 text-xs text-center block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-5 border-t border-gray-100">
                                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-200">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Guardar Configuración
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>

                <!-- TAB: ESTADÍSTICAS -->
                @elseif($activeTab === 'stats')
                <div class="space-y-8">
                    <!-- Header premium -->
                    <div class="bg-gradient-to-r from-slate-800 to-gray-900 rounded-2xl p-6 text-white shadow-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center shadow-inner shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div class="text-left">
                                <h3 class="text-xl font-black">Estadísticas</h3>
                                <p class="text-gray-400 text-xs mt-0.5">Resumen de tu rendimiento profesional y métricas de negocio.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Cards Grid Premium -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        <!-- Ingresos Totales -->
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200/80 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Ingresos Totales</p>
                                <p class="text-xl font-black text-gray-900 mt-0.5">S/ {{ number_format($totalEarnings, 2) }}</p>
                            </div>
                        </div>

                        <!-- Ingresos Mes -->
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200/80 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                            <div class="w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Ingresos del Mes</p>
                                <p class="text-xl font-black text-gray-900 mt-0.5">S/ {{ number_format($monthlyEarnings, 2) }}</p>
                            </div>
                        </div>

                        <!-- Citas Completadas -->
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200/80 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Servicios Completados</p>
                                <p class="text-xl font-black text-gray-900 mt-0.5">{{ $completedAppointmentsCount }}</p>
                            </div>
                        </div>

                        <!-- Valoración -->
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200/80 flex items-center gap-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-wider">Calificación Promedio</p>
                                <p class="text-xl font-black text-gray-900 mt-0.5">{{ $averageRating }} <span class="text-sm text-gray-400">/ 5.0</span></p>
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

                @endif

            </div>
        </div>
        </div>
        @elseif($mainSection === 'calendar')
            <!-- CALENDARIO FULL WIDTH -->
            <div class="bg-white shadow-sm rounded-3xl overflow-hidden border border-gray-150 p-6" wire:key="provider-calendar-section">
                <livewire:dashboard.visual-calendar wire:key="provider-visual-calendar" />
            </div>

        @elseif($mainSection === 'reviews')
            <!-- RESEÑAS FULL WIDTH -->
            <div class="bg-white shadow-sm rounded-3xl overflow-hidden border border-gray-150 p-6 space-y-6" wire:key="provider-reviews-section">
                <div class="border-b border-gray-100 pb-4 text-left">
                    <h3 class="text-xl font-black text-gray-900">Reseñas Recibidas</h3>
                    <p class="text-xs text-gray-500 mt-1">Aquí puedes leer las opiniones de tus clientes y responder a sus comentarios de manera pública.</p>
                </div>

                <div class="space-y-6 divide-y divide-gray-100">
                    @forelse($receivedReviews as $review)
                        <div class="pt-6 first:pt-0 space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="shrink-0">
                                    <img class="h-10 w-10 rounded-full bg-gray-200 shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                </div>
                                <div class="flex-1 space-y-1 text-left">
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
                                    <p class="text-sm text-gray-650 font-medium italic">"{{ $review->comment }}"</p>
                                </div>
                            </div>

                            <!-- Formulario / Respuesta -->
                            <div class="ml-14 bg-gray-50/50 p-4 rounded-2xl border border-gray-100 space-y-3 text-left">
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
                        <div class="text-center py-16 text-gray-500 italic">No has recibido reseñas todavía.</div>
                    @endforelse
                </div>
            </div>

        @elseif($mainSection === 'appointments')
            <!-- CITAS FULL WIDTH -->
            <div class="bg-white shadow-sm rounded-3xl overflow-hidden border border-gray-150 p-6 space-y-6" wire:key="provider-appointments-section">
                <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-left">
                        <h3 class="text-xl font-black text-gray-900">Control de Citas</h3>
                        <p class="text-xs text-gray-500 mt-1">Gestiona, filtra y confirma las citas agendadas por tus clientes.</p>
                    </div>
                    <div class="flex gap-1.5 flex-wrap">
                        @foreach(['pending' => 'Pendientes', 'confirmed' => 'Confirmadas', 'completed' => 'Completadas', 'cancelled' => 'Canceladas', 'payment_pending' => 'Pagos Pendientes', 'payment_under_review' => 'Por Revisar', 'all' => 'Todas'] as $status => $label)
                            @php
                                $cQuery = \App\Models\Appointment::where('provider_id', $user->id);
                                if ($status === 'payment_pending') {
                                    $cQuery->whereIn('status', ['confirmed', 'completed'])
                                           ->whereHas('payment', function($qp) {
                                               $qp->whereIn('status', ['pending', 'failed']);
                                           });
                                } elseif ($status === 'payment_under_review') {
                                    $cQuery->whereHas('payment', function($qp) {
                                        $qp->where('status', 'under_review');
                                    });
                                } elseif ($status !== 'all') {
                                    $cQuery->where('status', $status);
                                }
                                $statusCount = $cQuery->count();
                            @endphp
                            <button wire:click="$set('filterStatus', '{{ $status }}')"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition duration-150 cursor-pointer
                                    {{ $filterStatus === $status
                                        ? ($status === 'payment_under_review' ? 'bg-indigo-600 text-white border-indigo-600 shadow-xs' : ($status === 'payment_pending' ? 'bg-amber-600 text-white border-amber-600 shadow-xs' : 'bg-primary-600 text-white border-primary-600 shadow-xs'))
                                        : ($status === 'payment_under_review' && $statusCount > 0
                                            ? 'bg-indigo-55 text-indigo-700 border-indigo-200 hover:bg-indigo-100/70 font-semibold'
                                            : ($status === 'payment_pending' && $statusCount > 0
                                                ? 'bg-amber-55 text-amber-700 border-amber-200 hover:bg-amber-100/70 font-semibold'
                                                : 'bg-white text-gray-650 border-gray-200 hover:bg-gray-50 hover:text-gray-900')) }}">
                                {{ $label }}
                                @if($statusCount > 0)
                                    <span class="ml-1.5 {{ $filterStatus === $status ? 'bg-white/20 text-white' : ($status === 'payment_under_review' ? 'bg-indigo-200 text-indigo-900' : ($status === 'payment_pending' ? 'bg-amber-200 text-amber-900' : 'bg-gray-150 text-gray-700')) }} text-[9px] font-black px-1.5 py-0.5 rounded-full">{{ $statusCount }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Panel de Búsqueda y Filtros de Fecha -->
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-150 flex flex-col md:flex-row md:items-end gap-4">
                    <!-- Búsqueda por texto -->
                    <div class="flex-1 space-y-1 text-left">
                        <label for="searchQuery" class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Buscar por cliente o mascota</label>
                        <div class="relative">
                            <input type="text" id="searchQuery" wire:model.live.debounce.300ms="searchQuery" placeholder="Nombre, email, mascota..." 
                                class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 bg-white">
                            <span class="absolute left-3 top-2.5 text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <!-- Filtro por rango / días -->
                    <div class="w-full md:w-48 space-y-1 text-left shrink-0">
                        <label for="dateFilter" class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Filtrar por fecha</label>
                        <select id="dateFilter" wire:model.live="dateFilter" 
                            class="w-full px-3 py-2 text-xs rounded-xl border border-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 bg-white">
                            <option value="all">Todas las fechas</option>
                            <option value="today">Hoy</option>
                            <option value="tomorrow">Mañana</option>
                            <option value="this_week">Esta semana</option>
                            <option value="custom">Rango personalizado</option>
                        </select>
                    </div>

                    <!-- Campos de fecha personalizados -->
                    @if($dateFilter === 'custom')
                        <div class="w-full md:w-36 space-y-1 text-left shrink-0">
                            <label for="startDate" class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Desde</label>
                            <input type="date" id="startDate" wire:model.live="startDate" 
                                class="w-full px-3 py-1.5 text-xs rounded-xl border border-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        </div>
                        <div class="w-full md:w-36 space-y-1 text-left shrink-0">
                            <label for="endDate" class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Hasta</label>
                            <input type="date" id="endDate" wire:model.live="endDate" 
                                class="w-full px-3 py-1.5 text-xs rounded-xl border border-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        </div>
                    @endif
                </div>

                <!-- Lista de citas -->
                <div class="space-y-4">
                    @forelse($appointmentsList as $apt)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-xs p-5 hover:border-gray-200 transition duration-150">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="flex items-start gap-4 flex-1">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($apt->client->name) }}&background=0ea5e9&color=fff&size=48"
                                         class="w-12 h-12 rounded-2xl shrink-0 object-cover shadow-xs" alt="{{ $apt->client->name }}">
                                    <div class="text-left">
                                        <p class="font-bold text-gray-900 text-base leading-tight">{{ $apt->client->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $apt->client->email }}</p>
                                        @if($apt->pet)
                                            <p class="text-xs text-gray-600 mt-1.5 bg-gray-50 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg border border-gray-100">
                                                <span class="text-primary-600 shrink-0" title="Mascota">
                                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="4.5" cy="10.5" r="2.5"/>
                                                        <circle cx="9" cy="6" r="2.5"/>
                                                        <circle cx="15" cy="6" r="2.5"/>
                                                        <circle cx="19.5" cy="10.5" r="2.5"/>
                                                        <path d="M12 10.5c-2.485 0-4.5 2.015-4.5 4.5 0 2.22 1.455 4.103 3.456 4.757l.006.002.5.5.5-.5c2.001-.654 3.456-2.537 3.456-4.759 0-2.485-2.015-4.5-4.5-4.5z"/>
                                                    </svg>
                                                </span>
                                                <strong>{{ $apt->pet->name }}</strong>
                                                <span class="text-gray-400">({{ $apt->pet->species }})</span>
                                            </p>
                                        @endif
                                        @if($apt->notes)
                                            <p class="text-xs text-gray-500 italic mt-3 bg-gray-50/50 p-2.5 rounded-xl border border-dashed border-gray-100">
                                                "{{ $apt->notes }}"
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="shrink-0 flex flex-col items-end gap-3 text-right">
                                    <div>
                                        <p class="text-sm font-black text-gray-900">{{ $apt->scheduled_at->format('d M Y') }}</p>
                                        <p class="text-xs font-bold text-gray-500 mt-0.5">{{ $apt->scheduled_at->format('H:i') }} hrs</p>
                                    </div>

                                    <div class="flex flex-wrap items-center justify-end gap-1.5">
                                        @php
                                            $statusStyles = [
                                                'pending'   => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'confirmed' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            ];
                                            $statusLabels = [
                                                'pending'   => 'Pendiente',
                                                'confirmed' => 'Confirmada',
                                                'completed' => 'Completada',
                                                'cancelled' => 'Cancelada',
                                            ];
                                        @endphp
                                        <span class="text-[10px] font-black px-2.5 py-0.5 rounded-full border {{ $statusStyles[$apt->status] ?? 'bg-gray-55 text-gray-655' }}">
                                            {{ $statusLabels[$apt->status] ?? $apt->status }}
                                        </span>

                                        @if($apt->payment)
                                            @php
                                                $payStatusStyles = [
                                                    'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'under_review' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                    'completed'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'failed'       => 'bg-rose-50 text-rose-700 border-rose-200',
                                                ];
                                                $payStatusLabels = [
                                                    'pending'      => 'Pago Pendiente',
                                                    'under_review' => 'En Revisión',
                                                    'completed'    => 'Pago Aprobado',
                                                    'failed'       => 'Pago Fallido',
                                                ];
                                            @endphp
                                            <span class="text-[10px] font-black px-2.5 py-0.5 rounded-full border flex items-center gap-1 {{ $payStatusStyles[$apt->payment->status] ?? 'bg-gray-50 text-gray-650' }}">
                                                <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <rect width="20" height="14" x="2" y="5" rx="2" />
                                                    <path d="M2 10h20" />
                                                </svg>
                                                {{ $payStatusLabels[$apt->payment->status] ?? $apt->payment->status }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1.5">
                                        <button wire:click="openAppointmentModal({{ $apt->id }})"
                                            class="px-3.5 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold rounded-xl border border-gray-200 transition flex items-center gap-1 cursor-pointer">
                                            <span>Detalle</span> ➔
                                        </button>
                                        
                                        @if($apt->client->whatsapp)
                                            <a href="https://wa.me/51{{ preg_replace('/\D/','',$apt->client->whatsapp) }}"
                                               target="_blank"
                                               class="w-8 h-8 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition flex items-center justify-center shadow-xs"
                                               title="Contactar por WhatsApp">
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.464L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.625 1.451 5.403.002 9.803-4.386 9.805-9.794.002-2.618-1.01-5.078-2.854-6.924C16.379 2.043 13.93 1.02 11.312 1.02c-5.41 0-9.811 4.386-9.813 9.795-.001 2.052.541 4.054 1.571 5.827L2.099 21.99l5.466-1.433c1.72 1.037 3.475 1.585 4.693 1.587h-.001z"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-4 text-gray-500 font-medium">No hay citas en este estado.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Paginación -->
                @if(method_exists($appointmentsList, 'links'))
                    <div class="mt-6">
                        {{ $appointmentsList->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- MODAL DETALLE DE CITA GLOBAL (AMPLIO Y ENTENDIBLE) -->
        @if($showAppointmentModal && $selectedAppointmentData)
            @php
                $aptModal = $selectedAppointmentData;
                $statusModalStyles = [
                    'pending'   => 'bg-amber-50 text-amber-700 border-amber-200',
                    'confirmed' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                ];
                $statusModalLabels = [
                    'pending'   => 'Pendiente de Aceptación',
                    'confirmed' => 'Confirmada',
                    'completed' => 'Completada',
                    'cancelled' => 'Cancelada',
                ];
            @endphp
            <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-apt" role="dialog" aria-modal="true" wire:key="provider-appointment-detail-modal">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeAppointmentModal"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="relative z-10 inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 lg:max-w-3xl sm:max-w-xl w-full">
                        <div class="bg-gradient-to-r from-indigo-900 to-slate-900 px-6 py-5 text-white flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-white/10 shadow-inner text-lg">
                                    🤝
                                </span>
                                <div class="text-left">
                                    <h3 class="text-base font-black text-white" id="modal-title-apt">Detalle de la Cita</h3>
                                    <p class="text-[10px] text-indigo-200 mt-0.5">Programada para el {{ $aptModal->scheduled_at->translatedFormat('l, d \d\e F Y \a \l\a\s H:i') }} hrs</p>
                                </div>
                            </div>
                            <button type="button" wire:click="closeAppointmentModal" class="text-white/70 hover:text-white transition focus:outline-none">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="bg-white p-6 grid grid-cols-1 md:grid-cols-2 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-150">
                            <div class="space-y-5 text-left pr-0 md:pr-6">
                                <div>
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Información del Cliente</h4>
                                    <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($aptModal->client->name) }}&background=6366f1&color=fff&size=40" class="w-10 h-10 rounded-xl" />
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $aptModal->client->name }}</p>
                                            <p class="text-[11px] text-gray-500 truncate max-w-[200px]">{{ $aptModal->client->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($aptModal->pet)
                                    <div>
                                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Mascota</h4>
                                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                            <div class="flex justify-between items-center text-xs">
                                                <span class="font-semibold text-gray-500">Nombre:</span>
                                                <span class="font-bold text-gray-900 flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-primary-600 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="4.5" cy="10.5" r="2.5"/>
                                                        <circle cx="9" cy="6" r="2.5"/>
                                                        <circle cx="15" cy="6" r="2.5"/>
                                                        <circle cx="19.5" cy="10.5" r="2.5"/>
                                                        <path d="M12 10.5c-2.485 0-4.5 2.015-4.5 4.5 0 2.22 1.455 4.103 3.456 4.757l.006.002.5.5.5-.5c2.001-.654 3.456-2.537 3.456-4.759 0-2.485-2.015-4.5-4.5-4.5z"/>
                                                    </svg>
                                                    {{ $aptModal->pet->name }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between text-xs">
                                                <span class="font-semibold text-gray-500">Especie:</span>
                                                <span class="font-bold text-gray-900 capitalize">{{ $aptModal->pet->species }}</span>
                                            </div>
                                            @if($aptModal->pet->breed)
                                                <div class="flex justify-between text-xs">
                                                    <span class="font-semibold text-gray-500">Raza:</span>
                                                    <span class="font-bold text-gray-900">{{ $aptModal->pet->breed }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($aptModal->notes)
                                    <div>
                                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1.5">Notas Adicionales</h4>
                                        <p class="text-xs text-gray-600 bg-amber-50/50 p-3 rounded-2xl border border-dashed border-amber-100 leading-relaxed">
                                            "{{ $aptModal->notes }}"
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-5 text-left pt-5 md:pt-0 pl-0 md:pl-6">
                                <div>
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Detalle del Servicio</h4>
                                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-2">
                                        <div class="flex justify-between text-xs font-bold border-b border-gray-200/50 pb-2">
                                            <span>Servicios Solicitados</span>
                                            <span>Subtotal</span>
                                        </div>
                                        @forelse($aptModal->services as $service)
                                            <div class="flex justify-between text-xs text-gray-700">
                                                <span class="font-medium">{{ $service->name }}</span>
                                                <span class="font-bold">S/ {{ number_format($service->pivot->price ?? $service->price, 2) }}</span>
                                            </div>
                                        @empty
                                            <div class="text-xs text-gray-500 italic">Servicio general del perfil</div>
                                        @endforelse
                                        <div class="flex justify-between text-sm font-black text-gray-950 border-t border-gray-200/50 pt-2">
                                            <span>Total</span>
                                            <span>S/ {{ number_format($aptModal->services->sum(fn($s) => $s->pivot->price ?? $s->price), 2) ?: number_format($aptModal->price ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Estado y Pago</h4>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full border {{ $statusModalStyles[$aptModal->status] ?? 'bg-gray-55 text-gray-650' }}">
                                            {{ $statusModalLabels[$aptModal->status] ?? $aptModal->status }}
                                        </span>
                                        @if($aptModal->payment)
                                            @php
                                                $payModalStyles = [
                                                    'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'under_review' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                    'completed'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'failed'       => 'bg-rose-50 text-rose-700 border-rose-200',
                                                ];
                                                $payModalLabels = [
                                                    'pending'      => 'Pago Pendiente',
                                                    'under_review' => 'Pago En Revisión',
                                                    'completed'    => 'Pago Aprobado',
                                                    'failed'       => 'Pago Fallido',
                                                ];
                                            @endphp
                                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full border flex items-center gap-1 {{ $payModalStyles[$aptModal->payment->status] ?? 'bg-gray-55 text-gray-650' }}">
                                                <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <rect width="20" height="14" x="2" y="5" rx="2" />
                                                    <path d="M2 10h20" />
                                                </svg>
                                                {{ $payModalLabels[$aptModal->payment->status] ?? $aptModal->payment->status }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($aptModal->payment && $aptModal->payment->status === 'under_review')
                                        <div class="p-3 bg-indigo-50/50 rounded-2xl border border-indigo-100 space-y-2">
                                            <p class="text-[10px] font-black text-indigo-900 uppercase tracking-wider">Verificación de Comprobante</p>
                                            <div class="text-[11px] text-gray-600 space-y-1">
                                                @if($aptModal->payment->transaction_reference)
                                                    <p>Código Operación: <strong class="text-gray-900">{{ $aptModal->payment->transaction_reference }}</strong></p>
                                                @endif
                                                <p>Medio de Pago: <strong class="text-gray-950 uppercase">{{ $aptModal->payment->payment_method }}</strong></p>
                                                @if($aptModal->payment->receipt_photo_path)
                                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($aptModal->payment->receipt_photo_path) }}" target="_blank" class="inline-flex items-center text-xs font-black text-indigo-600 hover:text-indigo-800 underline mt-1">
                                                        🔎 Ver imagen del comprobante
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif($aptModal->payment && $aptModal->payment->status === 'completed')
                                        <div class="text-xs text-emerald-700 font-semibold bg-emerald-50/50 border border-emerald-100 p-3 rounded-2xl">
                                            <span>✓ Cobro aprobado por S/ {{ number_format($aptModal->payment->amount, 2) }} vía {{ strtoupper($aptModal->payment->payment_method) }}.</span>
                                            @if($aptModal->payment->transaction_reference)
                                                <div class="text-[10px] text-gray-500 font-normal mt-0.5">Operación Ref: {{ $aptModal->payment->transaction_reference }}</div>
                                            @endif
                                        </div>
                                    @endif

                                    @if($aptModal->status === 'confirmed')
                                        <!-- Campos de Edición de Cobro (Cita Confirmada) -->
                                        <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-2xl space-y-4">
                                            <h5 class="text-xs font-black text-gray-700 uppercase tracking-wider text-left">Desglose de Cobros y Conceptos</h5>
                                            
                                            <!-- Listado de cargos actuales -->
                                            <div class="space-y-2 max-h-[160px] overflow-y-auto pr-1">
                                                @forelse($extraCharges as $index => $charge)
                                                    <div class="flex items-center justify-between p-2 bg-white rounded-xl border border-gray-150 text-xs shadow-xs">
                                                        <div class="flex-1 text-left font-semibold text-gray-700 truncate pr-2">
                                                            {{ $charge['concept'] }}
                                                        </div>
                                                        <div class="font-extrabold text-gray-900 pr-3">
                                                            S/ {{ number_format($charge['amount'], 2) }}
                                                        </div>
                                                        <button type="button" wire:click="removeExtraCharge({{ $index }})" 
                                                            class="text-red-500 hover:text-red-700 font-bold px-2 py-1 transition cursor-pointer text-xs">
                                                            ✕
                                                        </button>
                                                    </div>
                                                @empty
                                                    <div class="text-[11px] text-gray-450 italic text-center py-4 bg-white rounded-xl border border-dashed border-gray-200">No hay cargos registrados en este desglose.</div>
                                                @endforelse
                                            </div>

                                            <!-- Formulario para agregar un nuevo cargo -->
                                            <div class="bg-white p-3 rounded-xl border border-gray-150 space-y-3">
                                                <div class="grid grid-cols-3 gap-2">
                                                    <div class="col-span-2 text-left">
                                                        <label for="newChargeConcept" class="block text-[9px] font-black text-gray-400 uppercase tracking-wider mb-1">Concepto / Servicio</label>
                                                        <input type="text" id="newChargeConcept" wire:model="newChargeConcept" placeholder="Ej: Vacuna / Examen / Baño" 
                                                            class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 bg-white">
                                                        @error('newChargeConcept') <span class="text-red-500 text-[9px] font-bold mt-0.5 block text-left">{{ $message }}</span> @enderror
                                                    </div>
                                                    <div class="text-left">
                                                        <label for="newChargeAmount" class="block text-[9px] font-black text-gray-400 uppercase tracking-wider mb-1">Monto (S/)</label>
                                                        <input type="number" step="0.01" min="0" id="newChargeAmount" wire:model="newChargeAmount" placeholder="0.00" 
                                                            class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-gray-200 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 bg-white font-bold text-gray-900">
                                                        @error('newChargeAmount') <span class="text-red-500 text-[9px] font-bold mt-0.5 block text-left">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="flex justify-end">
                                                    <button type="button" wire:click="addExtraCharge" 
                                                        class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[10px] font-black rounded-lg border border-indigo-150 transition cursor-pointer flex items-center gap-1">
                                                        ➕ Agregar Cargo
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Total Calculado -->
                                            <div class="flex justify-between items-center pt-2.5 border-t border-gray-200 font-black text-xs text-gray-800 text-left">
                                                <span>Total Final a Cobrar:</span>
                                                <span class="text-sm text-primary-600 font-extrabold bg-primary-50 px-3 py-1 rounded-lg border border-primary-100">
                                                    S/ {{ number_format($editPaymentAmount, 2) }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-[10px] text-gray-400 leading-normal text-left">
                                                * Nota: Al presionar "Completar Cita" se registrará este desglose y se notificará al cliente para proceder con el pago.
                                            </p>
                                        </div>
                                    @elseif($aptModal->status === 'completed' && $aptModal->payment && $aptModal->payment->description)
                                        <!-- Detalle de Cobro Registrado (Cita Completada) -->
                                        <div class="mt-4 p-4 bg-emerald-50/50 border border-emerald-100 rounded-2xl space-y-2 text-left">
                                            <h5 class="text-xs font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-emerald-600 fill-none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <rect width="20" height="14" x="2" y="5" rx="2" />
                                                    <path d="M2 10h20" />
                                                </svg>
                                                Detalle del Cobro Registrado
                                            </h5>

                                            @php
                                                $decodedCharges = json_decode($aptModal->payment->description, true);
                                            @endphp

                                            @if(is_array($decodedCharges))
                                                <div class="space-y-1.5 my-2">
                                                    @foreach($decodedCharges as $charge)
                                                        <div class="flex justify-between text-xs text-gray-700 bg-white p-2.5 rounded-xl border border-emerald-100/30">
                                                            <span class="font-medium">{{ $charge['concept'] }}</span>
                                                            <span class="font-extrabold text-gray-900">S/ {{ number_format($charge['amount'], 2) }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-650 bg-white p-2.5 rounded-lg border border-emerald-100/50 whitespace-pre-line leading-relaxed">
                                                    {{ $aptModal->payment->description }}
                                                </div>
                                            @endif

                                            <div class="flex justify-between items-center border-t border-emerald-250/50 pt-2.5 font-black text-xs text-gray-800">
                                                <span>Monto Total Cobrado:</span>
                                                <span class="text-emerald-700 font-extrabold bg-white px-2.5 py-1 rounded-lg border border-emerald-100">
                                                    S/ {{ number_format($aptModal->payment->amount, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-between items-center gap-3">
                            <div class="flex gap-2 w-full sm:w-auto">
                                @if($aptModal->client->whatsapp)
                                    <a href="https://wa.me/51{{ preg_replace('/\D/','',$aptModal->client->whatsapp) }}"
                                       target="_blank"
                                       class="w-full sm:w-auto inline-flex justify-center items-center gap-1.5 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition shadow-xs">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.464L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.625 1.451 5.403.002 9.803-4.386 9.805-9.794.002-2.618-1.01-5.078-2.854-6.924C16.379 2.043 13.93 1.02 11.312 1.02c-5.41 0-9.811 4.386-9.813 9.795-.001 2.052.541 4.054 1.571 5.827L2.099 21.99l5.466-1.433c1.72 1.037 3.475 1.585 4.693 1.587h-.001z"/></svg>
                                        WhatsApp
                                    </a>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                @if($aptModal->status === 'pending')
                                    <button type="button" wire:click="confirmAppointment({{ $aptModal->id }})"
                                        class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-md cursor-pointer flex items-center justify-center">
                                        Confirmar Cita
                                    </button>
                                    <button type="button" wire:click="$set('confirmingCancel', {{ $aptModal->id }})"
                                        class="px-4 py-2.5 bg-white border border-red-200 hover:bg-red-50 text-red-650 text-red-650 text-red-600 text-sm font-bold rounded-xl transition cursor-pointer flex items-center justify-center">
                                        Rechazar Cita
                                    </button>
                                @elseif($aptModal->status === 'confirmed')
                                    <button type="button" wire:click="completeAppointment({{ $aptModal->id }})"
                                        class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition shadow-md cursor-pointer flex items-center justify-center">
                                        Completar Cita
                                    </button>
                                    <button type="button" wire:click="$set('confirmingCancel', {{ $aptModal->id }})"
                                        class="px-4 py-2.5 bg-white border border-red-200 hover:bg-red-50 text-red-650 text-red-650 text-red-650 text-red-600 text-sm font-bold rounded-xl transition cursor-pointer flex items-center justify-center">
                                        Cancelar Cita
                                    </button>
                                @endif

                                @if($aptModal->payment && $aptModal->payment->status === 'under_review')
                                    <button type="button" wire:click="approveAppointmentPayment({{ $aptModal->id }})"
                                        class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition shadow-md cursor-pointer flex items-center justify-center">
                                        Aprobar Pago
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if($confirmingCancel === $aptModal->id)
                            <div class="bg-red-50 border-t border-red-200 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="text-left">
                                    <p class="text-sm font-bold text-red-900">¿Estás seguro de que deseas rechazar/cancelar esta cita?</p>
                                    <p class="text-xs text-red-700 mt-0.5">El cliente recibirá una notificación por correo.</p>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button type="button" wire:click="cancelAppointment({{ $aptModal->id }})"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition shadow-xs cursor-pointer">
                                        Confirmar Cancelación
                                    </button>
                                    <button type="button" wire:click="$set('confirmingCancel', null)"
                                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg transition hover:bg-gray-50 cursor-pointer">
                                        Atrás
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
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
