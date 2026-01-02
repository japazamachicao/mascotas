<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Panel Profesional - @if($user->hasRole('veterinarian')) Veterinario @elseif($user->hasRole('walker')) Paseador @else Estilista @endif
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Gestiona tu disponibilidad, portafolio y perfil público.
            </p>
        </div>

    

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
                            <a href="{{ route('profile.show', $user->id) }}" target="_blank" class="text-sm font-medium text-primary-600 hover:text-primary-500 flex items-center justify-center mt-1">
                                Ver Perfil Público 
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
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

                        <button @click="activeTab = 'portfolio'" 
                            :class="activeTab === 'portfolio' ? 'bg-primary-50 text-primary-700 border-primary-500' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="group w-full flex items-center px-3 py-2 text-sm font-medium border-l-4">
                            <svg :class="activeTab === 'portfolio' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500'" class="shrink-0 -ml-1 mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Mi Portafolio
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
                    </div>
                @endif

                <!-- TAB: PERFIL -->
                <div x-show="activeTab === 'profile'" class="bg-white shadow rounded-lg overflow-hidden">
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
                                </div>

                                {{-- Bio / Experiencia --}}
                                <div class="sm:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">
                                        {{ $user->hasRole('veterinarian') ? 'Biografía y Especialidades' : 'Experiencia y Servicios' }}
                                    </label>
                                    <div class="mt-1">
                                        <textarea wire:model="bio" rows="4" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">Esta es tu carta de presentación. Sé descriptivo y amable.</p>
                                </div>

                                {{-- Campos Específicos --}}
                                @if($user->hasRole('veterinarian'))
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
                                @elseif($user->hasRole('walker'))
                                    <div class="sm:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Tarifa por Hora (S/)</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">S/</span>
                                            </div>
                                            <input type="number" wire:model="hourly_rate" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                @elseif($user->hasRole('groomer'))
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
                                @elseif($user->hasRole('hotel'))
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
                                @elseif($user->hasRole('shelter'))
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
                                @elseif($user->hasRole('trainer'))
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
                                @elseif($user->hasRole('pet_sitter'))
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
                                @elseif($user->hasRole('pet_taxi'))
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
                                @elseif($user->hasRole('pet_photographer'))
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
                            @if($user->hasRole('veterinarian'))
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

            </div>
        </div>
    </div>
</div>
