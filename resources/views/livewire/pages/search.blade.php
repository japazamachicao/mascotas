<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header del Buscador (Diseño Elevado) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-10 p-6 md:p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-primary-50 opacity-50 blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Explora Profesionales</h1>
                <p class="text-gray-500 mb-8 max-w-2xl">Encuentra veterinarios y paseadores certificados cerca de ti con reseñas verificadas.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                    <!-- Barra de Búsqueda (Texto) -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Buscar Profesional</label>
                        <div class="relative group">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nombre del veterinario o paseador..." class="w-full pl-11 py-2.5 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all duration-300 text-sm shadow-sm hover:border-gray-300">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tipo de Servicio -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Servicio</label>
                        <select wire:model.live="serviceType" class="w-full py-2.5 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all text-sm shadow-sm cursor-pointer hover:border-gray-300">
                            <option value="veterinarian">Veterinarios</option>
                            <option value="walker">Paseadores</option>
                            <option value="trainer">Adiestradores</option>
                            <option value="pet_sitter">Cuidadores</option>
                            <option value="groomer">Estilistas / Baño</option>
                            <option value="pet_photographer">Fotógrafos</option>
                            <option value="pet_taxi">Pet Taxi</option>
                            <option value="pet_hotel">Hospedaje</option>
                        </select>
                    </div>

                    <!-- Departamento -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Departamento</label>
                        <select wire:model.live="department_id" class="w-full py-2.5 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all text-sm shadow-sm cursor-pointer hover:border-gray-300">
                            <option value="">Todos</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Provincia -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Provincia</label>
                        <select wire:model.live="province_id" class="w-full py-2.5 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all text-sm shadow-sm cursor-pointer hover:border-gray-300" {{ empty($provinces) ? 'disabled' : '' }}>
                            <option value="">Todas</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Distrito -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Distrito</label>
                        <select wire:model.live="district_id" class="w-full py-2.5 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all text-sm shadow-sm cursor-pointer hover:border-gray-300" {{ empty($districts) ? 'disabled' : '' }}>
                            <option value="">Todos</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Filtros Específicos (Dynamic) -->
                @if(in_array($serviceType, ['veterinarian', 'trainer', 'groomer', 'pet_sitter', 'pet_hotel', 'pet_taxi']))
                    <div class="mt-6 flex flex-wrap gap-4">
                        @if(in_array($serviceType, ['veterinarian', 'trainer', 'groomer', 'pet_sitter']))
                            <label class="inline-flex items-center cursor-pointer bg-white border border-gray-200 rounded-lg px-3 py-2 shadow-sm hover:bg-gray-50 transition">
                                <input type="checkbox" wire:model.live="filterHomeVisits" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 mr-2">
                                <span class="text-sm font-medium text-gray-700">A Domicilio</span>
                            </label>
                        @endif

                        @if($serviceType === 'pet_hotel')
                            <label class="inline-flex items-center cursor-pointer bg-white border border-gray-200 rounded-lg px-3 py-2 shadow-sm hover:bg-gray-50 transition">
                                <input type="checkbox" wire:model.live="filterCageFree" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 mr-2">
                                <span class="text-sm font-medium text-gray-700">Sin Jaulas (Cage Free)</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer bg-white border border-gray-200 rounded-lg px-3 py-2 shadow-sm hover:bg-gray-50 transition">
                                <input type="checkbox" wire:model.live="filterHasTransport" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 mr-2">
                                <span class="text-sm font-medium text-gray-700">Movilidad Incluida</span>
                            </label>
                        @endif

                        @if($serviceType === 'pet_sitter')
                            <label class="inline-flex items-center cursor-pointer bg-white border border-gray-200 rounded-lg px-3 py-2 shadow-sm hover:bg-gray-50 transition">
                                <input type="checkbox" wire:model.live="filterHasYard" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 mr-2">
                                <span class="text-sm font-medium text-gray-700">Tiene Patio / Jardín</span>
                            </label>
                        @endif

                        @if($serviceType === 'pet_taxi')
                            <label class="inline-flex items-center cursor-pointer bg-white border border-gray-200 rounded-lg px-3 py-2 shadow-sm hover:bg-gray-50 transition">
                                <input type="checkbox" wire:model.live="filterHasAc" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 mr-2">
                                <span class="text-sm font-medium text-gray-700">Aire Acondicionado</span>
                            </label>
                        @endif
                    </div>
                @endif

                <!-- Filtros secundarios / Ordenamiento -->
                <div class="mt-6 flex justify-between items-center border-t border-gray-100 pt-5">
                    <p class="text-sm text-gray-500 font-medium">Mostrando <span class="text-gray-900 font-bold">{{ $results->total() }}</span> resultados</p>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ordenar:</span>
                        <select wire:model.live="sortBy" class="py-1.5 pl-3 pr-8 border-gray-200 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500 bg-white shadow-sm cursor-pointer hover:border-gray-300">
                            <option value="best_rated">Mejores Calificados ⭐️</option>
                            <option value="newest">Nuevos Ingresos</option>
                            <option value="name_asc">A - Z</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultados Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($results as $result)
                <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-primary-900/5 transition-all duration-300 transform hover:-translate-y-1 relative flex flex-col h-full">
                    
                    <!-- Cover / Header de Tarjeta (Opcional: podrías poner un banner de color) -->
                    <div class="h-20 bg-gradient-to-r from-gray-50 to-gray-100 opacity-50 absolute w-full top-0 left-0 z-0"></div>

                    <div class="p-6 relative z-10 flex-1 flex flex-col">
                        <div class="flex justify-between items-start">
                            <div class="flex">
                                <div class="relative shrink-0">
                                    <!-- Foto con Borde y Sombra -->
                                    <div class="rounded-full p-1 bg-white shadow-sm">
                                        @if($result->user->profile_photo_path)
                                            <img class="h-16 w-16 rounded-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::url($result->user->profile_photo_path) }}" alt="{{ $result->user->name }}">
                                        @else
                                            <img class="h-16 w-16 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $result->user->name }}&background=0ea5e9&color=fff&size=128" alt="{{ $result->user->name }}">
                                        @endif
                                    </div>
                                    
                                    <!-- Badge 24h Flotante -->
                                    @if(rand(0,1)) 
                                        <div class="absolute -bottom-1 -right-1 bg-green-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm flex items-center gap-0.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                            24h
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 pt-1">
                                    <h3 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-primary-600 transition-colors">{{ $result->user->name }}</h3>
                                    <div class="flex items-center mt-1 text-sm text-gray-500 font-medium">
                                        @php
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
                                        @endphp
                                        {{ $serviceLabels[$serviceType] ?? 'Profesional' }}
                                    </div>
                                    
                                    <!-- Rating Stars -->
                                    <div class="flex items-center mt-1.5">
                                        @php
                                            $rating = $result->user->reviewsReceived()->avg('rating') ?? 0;
                                            $ratingCount = $result->user->reviewsReceived()->count();
                                        @endphp
                                        <div class="flex text-yellow-400 gap-0.5">
                                            @for($i=1; $i<=5; $i++)
                                                <svg class="w-3.5 h-3.5 {{ $i <= round($rating) ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1-81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                        <span class="ml-1.5 text-xs text-gray-400 font-semibold mt-0.5">({{ $ratingCount }} reseñas)</span>
                                    </div>
                                </div>
                            </div>

                            
                            <!-- Heart Button (Absolute or Relative) -->
                            <button wire:click.stop="toggleFavorite({{ $result->user->id }})" class="group/heart p-2 rounded-full hover:bg-gray-50 focus:outline-none transition-all ml-2" title="Guardar/Quitar de Favoritos">
                                @if(in_array($result->user->id, $favoriteIds))
                                    <svg class="w-6 h-6 text-red-500 fill-current transform scale-110 transition-transform" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                @else
                                    <svg class="w-6 h-6 text-gray-300 group-hover/heart:text-red-400 group-hover/heart:fill-red-50 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                @endif
                            </button>
                        </div>

                        <!-- Info Detallada -->
                        <div class="mt-5 space-y-3 flex-1">
                            <!-- Ubicación -->
                            <div class="flex items-start text-sm text-gray-600 bg-gray-50/50 p-2 rounded-lg border border-gray-100/50">
                                <svg class="h-4 w-4 mr-2 text-primary-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-medium text-gray-700">{{ $result->district->name ?? 'Distrito' }}</span>
                                <span class="text-gray-400 mx-1">•</span>
                                <span class="text-gray-500">{{ $result->district->province->name ?? '' }}</span>
                            </div>
                            
                            <!-- Badges y Atributos Específicos -->
                            @if(in_array($serviceType, ['veterinarian', 'trainer', 'groomer']))
                                <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed pl-1">{{ $result->bio ?? 'Sin presentación.' }}</p>
                                <div class="flex flex-wrap gap-2 pt-1">
                                    @if(isset($result->allows_home_visits) && $result->allows_home_visits)
                                        <span class="badge-feature bg-emerald-50 text-emerald-700 border-emerald-100 text-xs px-2 py-1 rounded-md font-bold border flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                            A Domicilio
                                        </span>
                                    @endif
                                    @if(isset($result->license_number))
                                        <span class="badge-feature bg-blue-50 text-blue-700 border-blue-100 text-xs px-2 py-1 rounded-md font-bold border">
                                            CMPVP: {{ $result->license_number }}
                                        </span>
                                    @endif
                                    @if(isset($result->methodology))
                                        <span class="badge-feature bg-purple-50 text-purple-700 border-purple-100 text-xs px-2 py-1 rounded-md font-bold border">
                                            {{ $result->methodology }}
                                        </span>
                                    @endif
                                </div>

                            @elseif($serviceType === 'pet_hotel')
                                <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-100">
                                    <div class="text-center p-1 border-r border-gray-200">
                                        <div class="text-gray-400 font-bold text-[10px] uppercase">Check-in</div>
                                        <div class="font-bold text-gray-800">{{ $result->check_in_time ? \Carbon\Carbon::parse($result->check_in_time)->format('H:i') : '--' }}</div>
                                    </div>
                                    <div class="text-center p-1">
                                        <div class="text-gray-400 font-bold text-[10px] uppercase">Check-out</div>
                                        <div class="font-bold text-gray-800">{{ $result->check_out_time ? \Carbon\Carbon::parse($result->check_out_time)->format('H:i') : '--' }}</div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2 pt-1">
                                    @if($result->cage_free)
                                        <span class="badge-feature bg-indigo-50 text-indigo-700 border-indigo-100 text-[10px] px-2 py-1 rounded-full font-bold border">🚫 Sin Jaulas</span>
                                    @endif
                                    @if($result->has_transport)
                                        <span class="badge-feature bg-blue-50 text-blue-700 border-blue-100 text-[10px] px-2 py-1 rounded-full font-bold border">🚙 Movilidad</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-1 pl-1 mt-1">{{ $result->bio }}</p>

                            @elseif($serviceType === 'pet_sitter')
                                @if(isset($result->hourly_rate))
                                     <div class="flex items-center justify-between bg-primary-50 px-3 py-2 rounded-lg border border-primary-100 mb-2">
                                        <span class="text-xs font-bold text-primary-700 uppercase">Tarifa</span>
                                        <span class="text-lg font-black text-primary-900">S/ {{ number_format($result->hourly_rate ?? 0, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <span class="badge-feature bg-orange-50 text-orange-700 border-orange-100 text-[10px] px-2 py-1 rounded-full font-bold border">🏠 {{ $result->housing_type }}</span>
                                    @if($result->has_yard)
                                        <span class="badge-feature bg-green-50 text-green-700 border-green-100 text-[10px] px-2 py-1 rounded-full font-bold border">🌳 Patio</span>
                                    @endif
                                    @if($result->allows_home_visits)
                                         <span class="badge-feature bg-teal-50 text-teal-700 border-teal-100 text-[10px] px-2 py-1 rounded-full font-bold border">🚗 Va a casa</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 line-clamp-2 pl-1">{{ $result->bio }}</p>

                            @elseif($serviceType === 'pet_taxi')
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <span class="badge-feature bg-gray-100 text-gray-700 border-gray-200 text-[10px] px-2 py-1 rounded-full font-bold border">🚙 {{ $result->vehicle_type }}</span>
                                    @if($result->has_ac)
                                        <span class="badge-feature bg-sky-50 text-sky-700 border-sky-100 text-[10px] px-2 py-1 rounded-full font-bold border">❄️ A/C</span>
                                    @endif
                                    @if($result->provides_crate)
                                        <span class="badge-feature bg-amber-50 text-amber-700 border-amber-100 text-[10px] px-2 py-1 rounded-full font-bold border">📦 Transportadora</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 line-clamp-2 pl-1">{{ $result->bio }}</p>
                            
                            @else
                                <!-- Fallback General (Walkers, Photographers, Shelter) -->
                                @if(isset($result->hourly_rate))
                                    <div class="flex items-center justify-between bg-primary-50 px-3 py-2 rounded-lg border border-primary-100 mb-2">
                                        <span class="text-xs font-bold text-primary-700 uppercase">Tarifa por hora</span>
                                        <span class="text-lg font-black text-primary-900">S/ {{ number_format($result->hourly_rate, 2) }}</span>
                                    </div>
                                @endif
                                <p class="text-sm text-gray-500 line-clamp-2 pl-1">{{ $result->bio ?? ($result->experience ?? ($result->description ?? 'Sin presentación.')) }}</p>
                            @endif
                            <!-- Para Hotel / Shelter podemos agregar lógica específica después si es necesario -->
                        </div>

                        <!-- Action Footer -->
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center gap-3">
                            <a href="{{ route('profile.show', $result->user->id) }}" class="flex-1 text-center bg-blue-600 text-white py-2.5 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg text-sm flex items-center justify-center group-hover:scale-[1.02]">
                                Ver Perfil
                                <svg class="w-4 h-4 ml-2 text-blue-200 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl shadow-sm p-16 text-center border border-gray-100 flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                            <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No encontramos resultados</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-8">Parece que no hay profesionales que coincidan con estos filtros. Intenta ampliar tu búsqueda.</p>
                        <button wire:click="resetFilters" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
                            Limpiar todos los filtros
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $results->links() }}
        </div>
    </div>
</div>
