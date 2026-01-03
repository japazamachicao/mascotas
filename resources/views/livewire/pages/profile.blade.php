<div class="min-h-screen bg-gray-50 pb-12">
    <!-- Header / Cover con Gradiente y Foto -->
    <div class="relative bg-gray-800 pb-16"> <!-- Reducido más el espacio -->
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-900 h-full w-full object-cover"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col items-center">
             <!-- Foto de Perfil Grande -->
             <div class="relative">
                <div class="h-28 w-28 rounded-full ring-4 ring-white bg-white overflow-hidden shadow-lg p-1">
                    @if ($user->profile_photo_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) }}" class="h-full w-full object-cover rounded-full" alt="{{ $user->name }}">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=0ea5e9&color=fff&size=128" class="h-full w-full object-cover rounded-full" alt="{{ $user->name }}">
                    @endif
                </div>
                @if($profile->is_verified ?? false)
                    <div class="absolute bottom-1 right-1 bg-green-500 text-white rounded-full p-1.5 border-2 border-white shadow-sm" title="Perfil Verificado">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                @endif
             </div>
             
             <div class="text-center mt-3">
                 <h1 class="text-2xl font-extrabold text-white tracking-tight sm:text-3xl shadow-sm">{{ $user->name }}</h1>
                 <p class="text-lg text-primary-100 font-medium">
                    @if($user->hasRole('veterinarian')) Veterinario
                    @elseif($user->hasRole('walker')) Paseador
                    @elseif($user->hasRole('groomer')) Estilista
                    @elseif($user->hasRole('hotel')) Hotel Canino
                    @elseif($user->hasRole('shelter')) Albergue
                    @elseif($user->hasRole('trainer')) Adiestrador
                    @elseif($user->hasRole('pet_sitter')) Cuidador
                    @elseif($user->hasRole('pet_taxi')) Transporte
                    @elseif($user->hasRole('pet_photographer')) Fotógrafo
                    @endif
                 </p>

                 <!-- Rating Promedio (Nuevo) -->
                 @if($this->totalReviews > 0)
                    <div class="flex items-center justify-center mt-1 text-yellow-400">
                        <span class="text-white text-sm font-bold mr-1">{{ $this->averageRating }}</span>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <span class="text-gray-300 text-xs ml-1">({{ $this->totalReviews }} reseñas)</span>
                    </div>
                 @endif
             </div>
             
             <!-- Badges removidos y movidos a la sección de detalles -->
             <div class="mt-2 flex flex-wrap justify-center gap-2">
                 @if(($profile->district))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white border border-white border-opacity-30 backdrop-blur-sm">
                        📍 {{ $profile->district->name }}, {{ $profile->district->province->name ?? '' }}
                    </span>
                 @endif
             </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <main class="-mt-16 relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> <!-- Ajustado -mt-12 a -mt-16 para subirlo un poco más -->
        
        <!-- Mensaje Global de Éxito -->
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 rounded-md bg-green-50 p-4 border border-green-200 shadow-sm relative">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button @click="show = false" type="button" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                <span class="sr-only">Cerrar</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Columna Izquierda (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Navegación de Pestañas -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <nav class="flex divide-x divide-gray-100">
                        <button wire:click="$set('activeTab', 'about')" class="flex-1 py-4 px-1 text-center text-sm font-medium hover:bg-gray-50 transition {{ $activeTab === 'about' ? 'text-primary-600 border-b-2 border-primary-500 bg-primary-50' : 'text-gray-500' }}">
                            Sobre Mí
                        </button>
                        <button wire:click="$set('activeTab', 'portfolio')" class="flex-1 py-4 px-1 text-center text-sm font-medium hover:bg-gray-50 transition {{ $activeTab === 'portfolio' ? 'text-primary-600 border-b-2 border-primary-500 bg-primary-50' : 'text-gray-500' }}">
                            Portafolio
                        </button>
                        <button wire:click="$set('activeTab', 'reviews')" class="flex-1 py-4 px-1 text-center text-sm font-medium hover:bg-gray-50 transition {{ $activeTab === 'reviews' ? 'text-primary-600 border-b-2 border-primary-500 bg-primary-50' : 'text-gray-500' }}">
                            Reseñas <span class="ml-1 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $this->totalReviews }}</span>
                        </button>
                    </nav>

                    <div class="p-6">
                        <!-- PESTAÑA: SOBRE MÍ -->
                        @if($activeTab === 'about')
                            <div class="prose prose-sm sm:prose lg:prose-lg text-gray-600 max-w-none">
                                <h3 class="text-gray-900 font-bold mb-4">Biografía y Experiencia</h3>
                                <p class="whitespace-pre-line">{{ $profile->bio ?? $profile->experience ?? 'Sin descripción disponible.' }}</p>
                                
                                @if($profile->address)
                                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
                                        <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-2">Ubicación / Dirección</h4>
                                        <p class="text-gray-600 text-sm flex items-start">
                                            <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            {{ $profile->address }}
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Detalles específicos por rol -->
                                <!-- Detalles específicos por rol -->
                                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Detalles específicos por rol -->
                                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                     <!-- Veterinario -->
                                     @if($user->hasRole('veterinarian'))
                                        <div class="border rounded-lg p-4 bg-emerald-50 border-emerald-100 col-span-full">
                                            <h4 class="text-xs text-emerald-800 uppercase font-bold mb-3">Servicios y Credenciales</h4>
                                            <div class="flex flex-wrap gap-3 items-center">
                                                <div class="border-r border-emerald-200 pr-4 mr-2">
                                                    <span class="block text-[10px] text-emerald-600 uppercase tracking-wider">CMVP</span>
                                                    <span class="font-bold text-gray-900 text-lg">{{ $profile->license_number }}</span>
                                                </div>
                                                <div class="flex flex-wrap gap-2">
                                                    @if($profile->emergency_24h ?? false)
                                                        <span class="bg-white text-red-600 border border-red-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center shadow-sm">
                                                            🚨 Emergencias 24h
                                                        </span>
                                                    @endif
                                                    @if($profile->allows_home_visits ?? false)
                                                        <span class="bg-white text-emerald-600 border border-emerald-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center shadow-sm">
                                                            🏠 A Domicilio
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                     @endif

                                     <!-- Groomer / Estilista -->
                                     @if($user->hasRole('groomer'))
                                        <div class="border rounded-lg p-4 bg-pink-50 border-pink-100 col-span-full">
                                            <h4 class="text-xs text-pink-800 uppercase font-bold mb-3">Detalles del Servicio</h4>
                                            <div class="flex flex-wrap gap-2">
                                                @if($profile->allows_home_visits ?? false)
                                                    <span class="bg-white text-pink-600 border border-pink-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center shadow-sm">
                                                        ✂️ Servicio a Domicilio
                                                    </span>
                                                @else
                                                    <span class="bg-white text-gray-600 border border-gray-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center shadow-sm">
                                                        🏢 Atención en Local
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                     @endif

                                     <!-- Paseador -->
                                     @if($user->hasRole('walker'))
                                        <div class="border rounded-lg p-4 bg-yellow-50 border-yellow-100 col-span-full">
                                            <h4 class="text-xs text-yellow-800 uppercase font-bold mb-3">Experiencia</h4>
                                            <p class="text-sm text-gray-700">{{ $profile->experience ?? 'Experiencia no especificada.' }}</p>
                                        </div>
                                     @endif

                                     <!-- Hotel -->
                                     @if($user->hasRole('hotel'))
                                        <div class="border rounded-lg p-4 bg-indigo-50 border-indigo-100 col-span-full">
                                            <h4 class="text-xs text-indigo-800 uppercase font-bold mb-3">Detalles del Hospedaje</h4>
                                            <div class="grid grid-cols-2 gap-4 mb-4">
                                                <div class="bg-white/60 p-2 rounded border border-indigo-100/50">
                                                    <span class="block text-[10px] text-gray-500 uppercase tracking-wider">Check-in</span>
                                                    <span class="font-bold text-indigo-900 text-lg">{{ $profile->check_in_time ? \Carbon\Carbon::parse($profile->check_in_time)->format('H:i') : '--' }}</span>
                                                </div>
                                                <div class="bg-white/60 p-2 rounded border border-indigo-100/50">
                                                    <span class="block text-[10px] text-gray-500 uppercase tracking-wider">Check-out</span>
                                                    <span class="font-bold text-indigo-900 text-lg">{{ $profile->check_out_time ? \Carbon\Carbon::parse($profile->check_out_time)->format('H:i') : '--' }}</span>
                                                </div>
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                @if($profile->cage_free)
                                                    <span class="bg-white text-indigo-600 border border-indigo-200 text-xs px-3 py-1.5 rounded-full font-bold shadow-sm">🚫 Sin Jaulas</span>
                                                @endif
                                                @if($profile->has_transport)
                                                    <span class="bg-white text-indigo-600 border border-indigo-200 text-xs px-3 py-1.5 rounded-full font-bold shadow-sm">🚙 Movilidad Incluida</span>
                                                @endif
                                                @if($profile->emergency_24h ?? false)
                                                    <span class="bg-white text-red-600 border border-red-200 text-xs px-3 py-1.5 rounded-full font-bold shadow-sm">🚨 Atención 24h</span>
                                                @endif
                                            </div>
                                        </div>
                                     @endif

                                     <!-- Adiestrador -->
                                     @if($user->hasRole('trainer'))
                                        <div class="border rounded-lg p-4 bg-purple-50 border-purple-100 col-span-full">
                                            <h4 class="text-xs text-purple-800 uppercase font-bold mb-3">Enfoque de Adiestramiento</h4>
                                            <div class="flex flex-col sm:flex-row gap-4">
                                                <div class="flex-1">
                                                    <span class="block text-[10px] text-gray-500 uppercase">Metodología</span>
                                                    <span class="font-medium text-gray-900 font-bold">{{ $profile->methodology ?? 'No especificada' }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    @if($profile->allows_home_visits ?? false)
                                                        <span class="bg-white text-purple-600 border border-purple-200 text-xs px-3 py-1.5 rounded-full font-bold shadow-sm">
                                                            🏠 Clases a Domicilio
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                     @endif

                                     <!-- Cuidador -->
                                     @if($user->hasRole('pet_sitter'))
                                        <div class="border rounded-lg p-4 bg-orange-50 border-orange-100 col-span-full">
                                            <h4 class="text-xs text-orange-800 uppercase font-bold mb-3">Sobre el Cuidado</h4>
                                            <div class="flex flex-wrap gap-3">
                                                 <span class="bg-white text-orange-600 border border-orange-200 text-xs px-3 py-1.5 rounded-md font-bold shadow-sm">
                                                    🏠 Tipo de Vivienda: {{ $profile->housing_type }}
                                                 </span>
                                                 @if($profile->has_yard)
                                                    <span class="bg-white text-green-600 border border-green-200 text-xs px-3 py-1.5 rounded-md font-bold shadow-sm">
                                                        🌳 Tiene Patio
                                                    </span>
                                                 @endif
                                                 @if($profile->allows_home_visits ?? false)
                                                    <span class="bg-white text-teal-600 border border-teal-200 text-xs px-3 py-1.5 rounded-md font-bold shadow-sm">
                                                        🚗 Va a tu casa
                                                    </span>
                                                 @endif
                                            </div>
                                        </div>
                                     @endif
                                     
                                     <!-- Pet Taxi -->
                                     @if($user->hasRole('pet_taxi'))
                                        <div class="border rounded-lg p-4 bg-gray-50 border-gray-200 col-span-full">
                                            <h4 class="text-xs text-gray-700 uppercase font-bold mb-3">Vehículo y Comodidades</h4>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                                                <div>
                                                    <span class="block text-[10px] text-gray-500 uppercase">Vehículo</span>
                                                    <span class="font-bold text-gray-900 text-lg">{{ $profile->vehicle_type ?? 'Estándar' }}</span>
                                                </div>
                                                <div class="flex flex-wrap gap-2">
                                                    @if($profile->has_ac)
                                                        <span class="bg-white text-sky-600 border border-sky-200 text-xs px-3 py-1.5 rounded-full font-bold shadow-sm">❄️ Aire Acondicionado</span>
                                                    @endif
                                                    @if($profile->provides_crate)
                                                        <span class="bg-white text-amber-600 border border-amber-200 text-xs px-3 py-1.5 rounded-full font-bold shadow-sm">📦 Transportadora</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                     @endif

                                     <!-- Fotógrafo -->
                                     @if($user->hasRole('pet_photographer'))
                                        <div class="border rounded-lg p-4 bg-cyan-50 border-cyan-100 col-span-full">
                                            <h4 class="text-xs text-cyan-800 uppercase font-bold mb-3">Estilo y Equipo</h4>
                                            <div class="flex flex-wrap gap-3">
                                                 <span class="bg-white text-cyan-600 border border-cyan-200 text-xs px-3 py-1.5 rounded-md font-bold shadow-sm">
                                                    📸 Especialidad: {{ $profile->specialty ?? 'General' }}
                                                 </span>
                                                 @if($profile->has_studio ?? false)
                                                    <span class="bg-white text-cyan-600 border border-cyan-200 text-xs px-3 py-1.5 rounded-md font-bold shadow-sm">
                                                        💡 Estudio Propio
                                                    </span>
                                                 @else
                                                     <span class="bg-white text-cyan-600 border border-cyan-200 text-xs px-3 py-1.5 rounded-md font-bold shadow-sm">
                                                        🏞️ Exteriores / A Domicilio
                                                     </span>
                                                 @endif
                                            </div>
                                        </div>
                                     @endif

                                     <!-- Albergue -->
                                     @if($user->hasRole('shelter') && $profile->donation_info)
                                        <div class="col-span-full bg-blue-50 border border-blue-100 rounded-lg p-4">
                                            <span class="block text-xs text-blue-500 uppercase font-bold mb-2">Información de Donaciones</span>
                                            <p class="text-sm text-blue-900 whitespace-pre-line">{{ $profile->donation_info }}</p>
                                        </div>
                                     @endif
                                </div>
                                </div>
                            </div>
                        
                        <!-- PESTAÑA: PORTAFOLIO -->
                        @elseif($activeTab === 'portfolio')
                            @if($user->portfolio->count() > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    @foreach($user->portfolio as $img)
                                        <div class="group relative rounded-lg overflow-hidden border border-gray-200 aspect-w-10 aspect-h-7 shadow-sm hover:shadow-md transition cursor-pointer" wire:click="openImage('{{ $img->image_path }}')">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($img->image_path) }}" class="object-cover w-full h-full transform group-hover:scale-105 transition duration-500">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition duration-300 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                </svg>
                                            </div>
                                            @if($img->title)
                                                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                                                    <p class="text-white text-xs p-3 font-medium">{{ $img->title }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="mt-2">Este proveedor aún no ha subido fotos a su portafolio.</p>
                                </div>
                            @endif

                        <!-- PESTAÑA: RESEÑAS -->
                        @elseif($activeTab === 'reviews')
                            <div class="space-y-8">
                                <!-- Formulario de Reseña (Movido Arriba) -->
                                @auth
                                    @if(auth()->id() !== $user->id)
                                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100 mb-8">
                                            <h4 class="text-sm font-bold text-gray-900 mb-4">Escribe una reseña</h4>
                                            <form wire:submit.prevent="saveReview">
                                                <div class="mb-4">
                                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tu Calificación</label>
                                                    <div class="flex space-x-2">
                                                        @foreach([1, 2, 3, 4, 5] as $start)
                                                            <button type="button" wire:click="$set('rating', {{ $start }})" class="focus:outline-none transition transform hover:scale-110">
                                                                <svg class="w-8 h-8 {{ $rating >= $start ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tu Comentario</label>
                                                    <textarea wire:model="comment" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Cuéntanos tu experiencia..."></textarea>
                                                    @error('comment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="flex justify-end">
                                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none">
                                                        Publicar Reseña
                                                    </button>
                                                </div>
                                            </form>
                                            @if ($errors->has('review'))
                                                <div class="mt-3 text-red-600 text-sm font-medium">{{ $errors->first('review') }}</div>
                                            @endif
                                        </div>
                                    @endif
                                @endauth
                                
                                @guest
                                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-100 mb-8 text-center">
                                        <p class="text-blue-800 text-sm mb-3">¿Has contratado a este proveedor? Inicia sesión para compartir tu experiencia.</p>
                                        <a href="{{ route('login') }}" class="inline-block bg-white text-blue-600 border border-blue-200 hover:bg-blue-50 font-semibold py-2 px-4 rounded shadow-sm text-sm transition">
                                            Iniciar Sesión para Opinar
                                        </a>
                                    </div>
                                @endguest

                                <!-- Controles de Filtros -->
                                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <span class="text-xs font-semibold text-gray-500 uppercase">Ordenar por:</span>
                                    <select wire:model.live="sortBy" class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                        <option value="newest">Más Recientes</option>
                                        <option value="oldest">Más Antiguas</option>
                                        <option value="highest">Mejor Puntuación</option>
                                        <option value="lowest">Peor Puntuación</option>
                                    </select>
                                </div>

                                <!-- Lista de Reseñas -->
                                <div class="space-y-6">
                                    @forelse($reviews as $review)
                                        <div class="flex space-x-4 border-b border-gray-100 pb-6 last:border-0">
                                            <div class="shrink-0">
                                                <img class="h-10 w-10 rounded-full bg-gray-200" src="https://ui-avatars.com/api/?name={{ $review->user->name }}&color=7F9CF5&background=EBF4FF" alt="">
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h5 class="text-sm font-bold text-gray-900">{{ $review->user->name }}</h5>
                                                    <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="flex items-center mt-1 mb-2">
                                                    @for($i=1; $i<=5; $i++)
                                                        <svg class="w-4 h-4 {{ $review->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-500 text-center italic py-2">Aún no hay reseñas para este perfil.</p>
                                    @endforelse
                                </div>

                                <!-- Paginación con Estilos Explícitos -->
                                <div class="mt-8"> <!-- Margen superior aumentado -->
                                    <style>
                                        /* Contenedor principal de la paginación */
                                        nav[role="navigation"] {
                                            display: flex;
                                            justify-content: flex-end; /* A la derecha */
                                            width: 100%;
                                            align-items: center;
                                        }
                                        
                                        /* Forzar visualización flex para el contenedor interno de Laravel */
                                        nav[role="navigation"] > div:nth-child(2) {
                                            display: flex !important;
                                            width: 100%;
                                            justify-content: flex-end;
                                        }

                                        /* Bloque de texto "Showing results..." */
                                        nav[role="navigation"] div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:first-child p {
                                            margin-bottom: 0 !important;
                                            margin-right: 1.5rem; /* Separación con los números */
                                            color: #6B7280 !important; /* gray-500 */
                                            font-size: 0.875rem !important;
                                            display: inline-block !important;
                                            white-space: nowrap; /* Evitar saltos de línea */
                                        }

                                        /* Bloque de Botones (Números y Flechas) */
                                        /* Quitamos el shadow y el border-radius del contenedor para que no se vea como un "rectángulo" */
                                        /* Laravel usa 'isolate inline-flex -space-x-px rounded-md shadow-sm' en el span contenedor */
                                        nav[role="navigation"] span.isolate,
                                        nav[role="navigation"] span.shadow-sm {
                                            box-shadow: none !important;
                                            border-radius: 0 !important;
                                            display: inline-flex !important;
                                            gap: 0.25rem !important; /* Separar los botones un poco */
                                        }

                                        /* Asegurarnos de matar cualquier borde o ring en el contenedor */
                                        nav[role="navigation"] span.isolate {
                                            border: none !important;
                                            box-shadow: none !important;
                                            --tw-ring-color: transparent !important;
                                            --tw-ring-offset-width: 0px !important;
                                            --tw-ring-offset-color: transparent !important;
                                            --tw-ring-width: 0px !important;
                                        }

                                        /* Estilos Base para TODOS los links/spans de paginación */
                                        nav[role="navigation"] span.relative.inline-flex,
                                        nav[role="navigation"] a.relative.inline-flex,
                                        nav[role="navigation"] button.relative.inline-flex {
                                            display: inline-flex !important;
                                            align-items: center !important;
                                            padding: 0.5rem 1rem !important; /* px-4 py-2 */
                                            border: 1px solid #E5E7EB !important; /* border-gray-200 más suave */
                                            background-color: #FFFFFF !important; /* bg-white por defecto */
                                            color: #374151 !important; /* text-gray-700 */
                                            font-size: 0.875rem !important; /* text-sm */
                                            font-weight: 500 !important;
                                            line-height: 1.25rem !important;
                                            border-radius: 0.375rem !important; /* Redondear cada botón individualmente */
                                            margin-left: 0 !important; /* Reset margin */
                                            
                                            /* Matar los focus rings de los botones individuales también por si acaso */
                                            --tw-ring-color: transparent !important; 
                                        }
                                        
                                        nav[role="navigation"] span.relative.inline-flex:first-child,
                                        nav[role="navigation"] a.relative.inline-flex:first-child {
                                             border-top-left-radius: 0.375rem;
                                             border-bottom-left-radius: 0.375rem;
                                        }
                                        nav[role="navigation"] span.relative.inline-flex:last-child,
                                        nav[role="navigation"] a.relative.inline-flex:last-child {
                                             border-top-right-radius: 0.375rem;
                                             border-bottom-right-radius: 0.375rem;
                                        }

                                        /* SOBRESCRIBIR ESTADO ACTIVO (Importante) */
                                        /* Span que actúa como activo (Laravel lo envuelve en un span con aria-current) */
                                        nav[role="navigation"] span[aria-current="page"] > span.relative.inline-flex {
                                            background-color: #2563eb !important; /* AZUL */
                                            color: #FFFFFF !important; /* BLANCO */
                                            border-color: #2563eb !important;
                                            z-index: 10;
                                        }

                                        /* Hover para los inactivos */
                                        nav[role="navigation"] a.relative.inline-flex:hover {
                                            background-color: #F3F4F6 !important; /* gray-50 */
                                            color: #111827 !important; /* gray-900 */
                                        }

                                        /* Ajustes para SVGs (Flechas) */
                                        nav[role="navigation"] svg {
                                            width: 1.25rem !important;
                                            height: 1.25rem !important;
                                        }
                                    </style>
                                    {{ $reviews->links() }}
                                </div>
                            </div>
                        @else
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna Derecha (1/3) - Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Tarjeta de Acción / Reserva -->
                <div class="bg-white rounded-xl shadow-lg border border-primary-100 p-6 sticky top-6">
                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-500 mb-1">Precio Referencial</p>
                        <p class="text-3xl font-extrabold text-gray-900">
                            @if(isset($profile->hourly_rate)) S/ {{ $profile->hourly_rate }} <span class="text-sm font-normal text-gray-500">/hora</span>
                            @else <span class="text-xl">Consultar</span>
                            @endif
                        </p>
                    </div>

                    <button wire:click="$set('showBookingModal', true)" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition">
                        Reservar Cita
                    </button>
                    
                    <button class="w-full mt-3 flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                        Contactar
                    </button>

                    <div class="mt-6 border-t pt-6">
                        <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Horarios de Atención
                        </h4>
                        <div class="space-y-2 text-sm">
                            @php $days = ['monday'=>'Lun', 'tuesday'=>'Mar', 'wednesday'=>'Mié', 'thursday'=>'Jue', 'friday'=>'Vie', 'saturday'=>'Sáb', 'sunday'=>'Dom']; @endphp
                            @if($profile->availability)
                                @foreach($days as $key => $label)
                                    @if(isset($profile->availability[$key]) && ($profile->availability[$key]['active'] ?? false))
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-600">{{ $label }}</span>
                                            <span class="text-gray-900">{{ $profile->availability[$key]['start'] ?? '' }} - {{ $profile->availability[$key]['end'] ?? '' }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-gray-400 italic text-xs">No especificado</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Info Redes -->
                @if($profile->facebook_url || $profile->instagram_url || $profile->website_url)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Redes Sociales</h4>
                        <div class="flex space-x-4">
                            @if($profile->facebook_url)
                                <a href="{{ $profile->facebook_url }}" target="_blank" class="text-gray-400 hover:text-blue-600"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                            @endif
                            @if($profile->instagram_url)
                                <a href="{{ $profile->instagram_url }}" target="_blank" class="text-gray-400 hover:text-pink-600"><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </main>

    <!-- Modal de Imagen Portafolio -->
    @if($selectedImage)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 transition-opacity p-4" x-data @keydown.window.escape="$wire.closeImage()">
            <div class="relative max-w-5xl max-h-screen">
                <button wire:click="closeImage" class="absolute -top-12 right-0 text-white hover:text-gray-300 focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <img src="{{ \Illuminate\Support\Facades\Storage::url($selectedImage) }}" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl object-contain">
            </div>
        </div>
    @endif

    <!-- Modal de Reserva -->
    @if($showBookingModal)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showBookingModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Reservar Servicio</h3>
                                <div class="mt-2 text-sm text-gray-500">
                                    <p class="mb-4">Ingresa la fecha y hora preferida. El proveedor confirmará la disponibilidad.</p>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Fecha</label>
                                            <input type="date" wire:model="appointmentDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                            @error('appointmentDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Hora</label>
                                            <input type="time" wire:model="appointmentTime" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                            @error('appointmentTime') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Notas / Detalles</label>
                                            <textarea wire:model="appointmentNotes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Raza, tamaño, necesidades especiales..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="bookAppointment" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Enviar Solicitud
                        </button>
                        <button type="button" wire:click="$set('showBookingModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
