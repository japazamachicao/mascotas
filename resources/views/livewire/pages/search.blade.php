@push('meta')
    @php
        $serviceNamePlural = [
            'veterinarian' => 'Veterinarios',
            'walker' => 'Paseadores de Perros',
            'trainer' => 'Adiestradores Caninos',
            'pet_sitter' => 'Cuidadores de Mascotas',
            'groomer' => 'Estilistas y Baño de Mascotas',
            'pet_photographer' => 'Fotógrafos de Mascotas',
            'pet_taxi' => 'Pet Taxi y Transporte',
            'pet_hotel' => 'Hospedaje de Mascotas',
        ][$serviceType] ?? 'Proveedores de Servicios para Mascotas';
        
        $locationName = 'Perú';
        if (!empty($district_id)) {
            $selDist = \App\Models\District::find($district_id);
            if ($selDist) {
                $locationName = $selDist->name;
            }
        } elseif (!empty($province_id)) {
            $selProv = \App\Models\Province::find($province_id);
            if ($selProv) {
                $locationName = $selProv->name;
            }
        } elseif (!empty($department_id)) {
            $selDept = \App\Models\Department::find($department_id);
            if ($selDept) {
                $locationName = $selDept->name;
            }
        }
    @endphp
    <title>Encuentra {{ $serviceNamePlural }} en {{ $locationName }} | TodoPeludos</title>
    <meta name="description" content="Busca y compara entre los mejores {{ strtolower($serviceNamePlural) }} en {{ $locationName }}. Consulta precios, lee opiniones de clientes y reserva de forma segura.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="Encuentra {{ $serviceNamePlural }} en {{ $locationName }} | TodoPeludos">
    <meta property="og:description" content="Busca y compara entre los mejores {{ strtolower($serviceNamePlural) }} en {{ $locationName }}. Consulta precios, lee opiniones de clientes y reserva de forma segura.">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Encuentra {{ $serviceNamePlural }} en {{ $locationName }} | TodoPeludos">
    <meta property="twitter:description" content="Busca y compara entre los mejores {{ strtolower($serviceNamePlural) }} en {{ $locationName }}. Consulta precios, lee opiniones de clientes y reserva de forma segura.">
    
    <style>
        .provider-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .provider-card:hover {
            transform: translateY(-4px) !important;
            border-color: rgba(2, 132, 199, 0.2) !important;
            box-shadow: 0 20px 25px -5px rgba(2, 132, 199, 0.1), 0 10px 10px -5px rgba(2, 132, 199, 0.04) !important;
        }
    </style>
@endpush

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header del Buscador (Diseño Elevado) -->
        <div class="bg-white rounded-2xl shadow-md shadow-gray-100/50 border border-gray-200/60 mb-10 p-6 md:p-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-primary-50/50 opacity-40 blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Explora Profesionales</h1>
                <p class="text-gray-500 mb-8 max-w-2xl text-sm sm:text-base">Encuentra veterinarios y paseadores certificados cerca de ti con reseñas verificadas.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                    <!-- Barra de Búsqueda (Texto) -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Buscar Profesional</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nombre del veterinario o paseador..." class="w-full pl-11 py-2.5 border-gray-200 rounded-xl bg-white focus:bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all duration-350 text-sm shadow-sm hover:border-gray-300">
                        </div>
                    </div>

                    <!-- Tipo de Servicio -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Servicio</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <select wire:model.live="serviceType" class="w-full pl-11 pr-10 py-2.5 border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 appearance-none transition-all text-sm shadow-sm cursor-pointer hover:border-gray-300">
                                <option value="veterinarian">Veterinarios</option>
                                <option value="walker">Paseadores</option>
                                <option value="trainer">Adiestradores</option>
                                <option value="pet_sitter">Cuidadores</option>
                                <option value="groomer">Estilistas / Baño</option>
                                <option value="pet_photographer">Fotógrafos</option>
                                <option value="pet_taxi">Pet Taxi</option>
                                <option value="pet_hotel">Hospedaje</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Departamento -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Departamento</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <select wire:model.live="department_id" class="w-full pl-11 pr-10 py-2.5 border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 appearance-none transition-all text-sm shadow-sm cursor-pointer hover:border-gray-300">
                                <option value="">Todos</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Provincia -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Provincia</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <select wire:model.live="province_id" class="w-full pl-11 pr-10 py-2.5 border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 appearance-none transition-all text-sm shadow-sm cursor-pointer hover:border-gray-300 disabled:bg-gray-100/80 disabled:text-gray-400 disabled:cursor-not-allowed" {{ empty($provinces) ? 'disabled' : '' }}>
                                <option value="">Todas</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Distrito -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Distrito</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <select wire:model.live="district_id" class="w-full pl-11 pr-10 py-2.5 border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 appearance-none transition-all text-sm shadow-sm cursor-pointer hover:border-gray-300 disabled:bg-gray-100/80 disabled:text-gray-400 disabled:cursor-not-allowed" {{ empty($districts) ? 'disabled' : '' }}>
                                <option value="">Todos</option>
                                @foreach($districts as $dist)
                                    <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtros Adicionales (Row Premium de Checkboxes) -->
                <div class="mt-6 bg-gray-50/80 rounded-2xl p-5 border border-gray-100/80">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="p-1.5 bg-primary-50 rounded-lg text-primary-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </span>
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Filtros de Servicio</span>
                    </div>
                    <div class="flex flex-wrap gap-3.5">
                        <!-- Filtro: Verificado -->
                        <label class="relative inline-flex items-center cursor-pointer select-none px-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50/80 transition-all duration-200 focus-within:ring-2 focus-within:ring-primary-100 overflow-hidden group">
                            <input type="checkbox" wire:model.live="filterVerified" class="sr-only peer">
                            <!-- Dynamic background/border overlay -->
                            <div class="absolute inset-0 border border-transparent peer-checked:border-primary-300 peer-checked:bg-primary-50/20 transition-all duration-200 pointer-events-none rounded-xl"></div>
                            
                            <!-- Custom toggle indicator -->
                            <div class="relative z-10 w-9 h-5 bg-gray-200 rounded-full transition-colors duration-200 peer-checked:bg-primary-600 flex items-center px-[2px]">
                                <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 transform peer-checked:translate-x-4 shadow-sm"></div>
                            </div>
                            <!-- Text label -->
                            <span class="relative z-10 ml-3 text-sm font-semibold text-gray-700 peer-checked:text-primary-800 transition-colors duration-200 flex items-center gap-2">
                                <svg class="w-4.5 h-4.5 text-primary-500 fill-current shrink-0" viewBox="0 0 24 24">
                                    <path d="M23 12l-2.44-2.79.34-3.69-3.61-.82-1.89-3.2L12 2.96 8.6 1.5 6.71 4.7l-3.61.81.34 3.68L1 12l2.44 2.79-.34 3.69 3.61.82 1.89 3.2L12 21.04l3.4 1.46 1.89-3.2 3.61-.82-.34-3.68L23 12zm-13 5l-4-4 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                Verificados
                            </span>
                        </label>

                        <!-- Filtro: A Domicilio -->
                        @if(in_array($serviceType, ['veterinarian', 'trainer', 'groomer', 'pet_sitter']))
                            <label class="relative inline-flex items-center cursor-pointer select-none px-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50/80 transition-all duration-200 focus-within:ring-2 focus-within:ring-emerald-100 overflow-hidden group">
                                <input type="checkbox" wire:model.live="filterHomeVisits" class="sr-only peer">
                                <!-- Dynamic background/border overlay -->
                                <div class="absolute inset-0 border border-transparent peer-checked:border-emerald-300 peer-checked:bg-emerald-50/20 transition-all duration-200 pointer-events-none rounded-xl"></div>
                                
                                <!-- Custom toggle indicator -->
                                <div class="relative z-10 w-9 h-5 bg-gray-200 rounded-full transition-colors duration-200 peer-checked:bg-emerald-600 flex items-center px-[2px]">
                                    <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 transform peer-checked:translate-x-4 shadow-sm"></div>
                                </div>
                                <!-- Text label -->
                                <span class="relative z-10 ml-3 text-sm font-semibold text-gray-700 peer-checked:text-emerald-800 transition-colors duration-200 flex items-center gap-2">
                                    <svg class="w-4.5 h-4.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    A Domicilio
                                </span>
                            </label>
                        @endif

                        <!-- Filtro: Emergencias 24h -->
                        @if($serviceType === 'veterinarian')
                            <label class="relative inline-flex items-center cursor-pointer select-none px-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50/80 transition-all duration-200 focus-within:ring-2 focus-within:ring-rose-100 overflow-hidden group">
                                <input type="checkbox" wire:model.live="filter24h" class="sr-only peer">
                                <!-- Dynamic background/border overlay -->
                                <div class="absolute inset-0 border border-transparent peer-checked:border-rose-300 peer-checked:bg-rose-50/20 transition-all duration-200 pointer-events-none rounded-xl"></div>
                                
                                <!-- Custom toggle indicator -->
                                <div class="relative z-10 w-9 h-5 bg-gray-200 rounded-full transition-colors duration-200 peer-checked:bg-rose-600 flex items-center px-[2px]">
                                    <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 transform peer-checked:translate-x-4 shadow-sm"></div>
                                </div>
                                <!-- Text label -->
                                <span class="relative z-10 ml-3 text-sm font-semibold text-gray-700 peer-checked:text-rose-800 transition-colors duration-200 flex items-center gap-2">
                                    <svg class="w-4.5 h-4.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Emergencias 24h
                                </span>
                            </label>
                        @endif

                        <!-- Filtros Específicos Dinámicos -->
                        @if($serviceType === 'pet_hotel')
                            <label class="relative inline-flex items-center cursor-pointer select-none px-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50/80 transition-all duration-200 focus-within:ring-2 focus-within:ring-indigo-100 overflow-hidden group">
                                <input type="checkbox" wire:model.live="filterCageFree" class="sr-only peer">
                                <!-- Dynamic background/border overlay -->
                                <div class="absolute inset-0 border border-transparent peer-checked:border-indigo-300 peer-checked:bg-indigo-50/20 transition-all duration-200 pointer-events-none rounded-xl"></div>
                                
                                <!-- Custom toggle indicator -->
                                <div class="relative z-10 w-9 h-5 bg-gray-200 rounded-full transition-colors duration-200 peer-checked:bg-indigo-600 flex items-center px-[2px]">
                                    <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 transform peer-checked:translate-x-4 shadow-sm"></div>
                                </div>
                                <!-- Text label -->
                                <span class="relative z-10 ml-3 text-sm font-semibold text-gray-700 peer-checked:text-indigo-800 transition-colors duration-200 flex items-center gap-2">
                                    <svg class="w-4.5 h-4.5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                    </svg>
                                    Sin Jaulas
                                </span>
                            </label>
                            
                            <label class="relative inline-flex items-center cursor-pointer select-none px-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50/80 transition-all duration-200 focus-within:ring-2 focus-within:ring-blue-100 overflow-hidden group">
                                <input type="checkbox" wire:model.live="filterHasTransport" class="sr-only peer">
                                <!-- Dynamic background/border overlay -->
                                <div class="absolute inset-0 border border-transparent peer-checked:border-blue-300 peer-checked:bg-blue-50/20 transition-all duration-200 pointer-events-none rounded-xl"></div>
                                
                                <!-- Custom toggle indicator -->
                                <div class="relative z-10 w-9 h-5 bg-gray-200 rounded-full transition-colors duration-200 peer-checked:bg-blue-600 flex items-center px-[2px]">
                                    <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 transform peer-checked:translate-x-4 shadow-sm"></div>
                                </div>
                                <!-- Text label -->
                                <span class="relative z-10 ml-3 text-sm font-semibold text-gray-700 peer-checked:text-blue-800 transition-colors duration-200 flex items-center gap-2">
                                    <svg class="w-4.5 h-4.5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125a1.125 1.125 0 001.125-1.125V9.75M3.75 14.25h16.5M3.75 14.25V9.75m16.5 4.5V9.75m0 0a3.75 3.75 0 00-3.75-3.75H8.25m9.75 3.75H3.75M8.25 6h7.5M8.25 6L6.75 9.75m9-3.75l1.5 9.75" />
                                    </svg>
                                    Movilidad Incluida
                                </span>
                            </label>
                        @endif

                        @if($serviceType === 'pet_sitter')
                            <label class="relative inline-flex items-center cursor-pointer select-none px-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50/80 transition-all duration-200 focus-within:ring-2 focus-within:ring-emerald-100 overflow-hidden group">
                                <input type="checkbox" wire:model.live="filterHasYard" class="sr-only peer">
                                <!-- Dynamic background/border overlay -->
                                <div class="absolute inset-0 border border-transparent peer-checked:border-emerald-300 peer-checked:bg-emerald-50/20 transition-all duration-200 pointer-events-none rounded-xl"></div>
                                
                                <!-- Custom toggle indicator -->
                                <div class="relative z-10 w-9 h-5 bg-gray-200 rounded-full transition-colors duration-200 peer-checked:bg-emerald-600 flex items-center px-[2px]">
                                    <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 transform peer-checked:translate-x-4 shadow-sm"></div>
                                </div>
                                <!-- Text label -->
                                <span class="relative z-10 ml-3 text-sm font-semibold text-gray-700 peer-checked:text-emerald-800 transition-colors duration-200 flex items-center gap-2">
                                    <svg class="w-4.5 h-4.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L12 20m0-17c4.97 0 9 4.03 9 9 0 2.12-.74 4.07-1.97 5.61L12 20" />
                                    </svg>
                                    Tiene Patio / Jardín
                                </span>
                            </label>
                        @endif

                        @if($serviceType === 'pet_taxi')
                            <label class="relative inline-flex items-center cursor-pointer select-none px-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50/80 transition-all duration-200 focus-within:ring-2 focus-within:ring-sky-100 overflow-hidden group">
                                <input type="checkbox" wire:model.live="filterHasAc" class="sr-only peer">
                                <!-- Dynamic background/border overlay -->
                                <div class="absolute inset-0 border border-transparent peer-checked:border-sky-300 peer-checked:bg-sky-50/20 transition-all duration-200 pointer-events-none rounded-xl"></div>
                                
                                <!-- Custom toggle indicator -->
                                <div class="relative z-10 w-9 h-5 bg-gray-200 rounded-full transition-colors duration-200 peer-checked:bg-sky-600 flex items-center px-[2px]">
                                    <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 transform peer-checked:translate-x-4 shadow-sm"></div>
                                </div>
                                <!-- Text label -->
                                <span class="relative z-10 ml-3 text-sm font-semibold text-gray-700 peer-checked:text-sky-800 transition-colors duration-200 flex items-center gap-2">
                                    <svg class="w-4.5 h-4.5 text-sky-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18m-3-6l-3 3m0 0l-3-3m3 3V3m0 12l-3-3m0 0l-3 3m3-3v6M6 9l3 3m0 0l-3 3m3-3H3m12 0l-3 3m0 0l-3-3m3-3h6" />
                                    </svg>
                                    Aire Acondicionado
                                </span>
                            </label>
                        @endif
                    </div>
                </div>

                <!-- Filtros secundarios / Ordenamiento -->
                <div class="mt-6 flex flex-col sm:flex-row justify-between sm:items-center gap-4 border-t border-gray-100 pt-5">
                    <p class="text-sm text-gray-500 font-medium order-2 sm:order-1 text-center sm:text-left">
                        Mostrando <span class="text-gray-900 font-bold">{{ $results->total() }}</span> resultados
                    </p>
                    <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto order-1 sm:order-2">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider shrink-0">Ordenar por:</span>
                        <div class="relative group min-w-[180px] w-full sm:w-auto">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                </svg>
                            </div>
                            <select wire:model.live="sortBy" class="w-full pl-9 pr-9 py-2 border-gray-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 appearance-none shadow-sm cursor-pointer hover:border-gray-300 transition-all">
                                <option value="best_rated">Mejores Calificados</option>
                                <option value="newest">Nuevos Ingresos</option>
                                <option value="name_asc">Nombre (A - Z)</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-3.5 w-3.5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        
                        <button wire:click="$toggle('showMap')" class="flex items-center gap-2 px-4 py-2 border rounded-xl text-sm font-bold transition shadow-sm shrink-0 {{ $showMap ? 'bg-primary-600 border-primary-600 text-white hover:bg-primary-700' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-4 h-4 shrink-0 {{ $showMap ? 'text-white' : 'text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75L3 9v11.25l6-2.25m0-12l6 2.25m-6-2.25V20m6-12l6-2.25v11.25l-6 2.25m0-11.25V20m0 0l-6-2.25" />
                            </svg>
                            {{ $showMap ? 'Ocultar Mapa' : 'Ver Mapa' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if($showMap)
            <div class="mb-8 bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm p-4 relative" 
                 x-data="{ 
                     initMap() { 
                         this.$nextTick(() => { 
                             window.initLeafletSearchMap(this, @js($mapMarkers)); 
                         }); 
                     } 
                 }" 
                 x-init="initMap()" 
                 x-on:markers-updated.window="updateMarkers($event.detail.markers)"
                 wire:ignore>
                <div id="search-map" class="h-[450px] w-full rounded-xl z-10"></div>
            </div>
        @endif

        <!-- Resultados Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($results as $result)
                <div class="provider-card group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-primary-900/5 transition-all duration-300 transform hover:-translate-y-1 relative flex flex-col h-full">
                    
                    <!-- Cover / Header de Tarjeta (Opcional: podrías poner un banner de color) -->
                    <div class="h-20 bg-gray-50/70 border-b border-gray-100/50 absolute w-full top-0 left-0 z-0"></div>

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
                                    @php
                                        $lvl = $result->user->getProfileLevel($result);
                                    @endphp
                                    <h3 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-primary-600 transition-colors flex items-center gap-1.5 flex-wrap">
                                        <span>{{ $result->user->name }}</span>
                                        @if($result->is_verified)
                                            <span class="inline-flex items-center text-emerald-500" title="Proveedor Verificado">
                                                <svg class="w-4.5 h-4.5 fill-current inline-block" viewBox="0 0 24 24">
                                                    <path d="M23 12l-2.44-2.79.34-3.69-3.61-.82-1.89-3.2L12 2.96 8.6 1.5 6.71 4.7l-3.61.81.34 3.68L1 12l2.44 2.79-.34 3.69 3.61.82 1.89 3.2L12 21.04l3.4 1.46 1.89-3.2 3.61-.82-.34-3.68L23 12zm-13 5l-4-4 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                </svg>
                                            </span>
                                        @endif
                                        @if(!empty($lvl))
                                            <x-level-badge level="{{ $lvl['name'] }}" size="xs" />
                                        @endif
                                    </h3>
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
                                                <svg class="w-3.5 h-3.5 {{ $i <= round($rating) ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
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
                            
                            <!-- Precio base -->
                            @if(!empty($result->price_from) && $result->price_from > 0)
                                <div class="flex items-center justify-between bg-primary-50 px-3 py-2 rounded-lg border border-primary-100">
                                    <span class="text-xs font-bold text-primary-700 uppercase tracking-wide">Desde</span>
                                    <span class="text-lg font-black text-primary-900">S/ {{ number_format($result->price_from, 0) }}</span>
                                </div>
                            @elseif(isset($result->hourly_rate) && $result->hourly_rate > 0)
                                <div class="flex items-center justify-between bg-primary-50 px-3 py-2 rounded-lg border border-primary-100">
                                    <span class="text-xs font-bold text-primary-700 uppercase tracking-wide">Por hora</span>
                                    <span class="text-lg font-black text-primary-900">S/ {{ number_format($result->hourly_rate, 0) }}</span>
                                </div>
                            @endif

                            <!-- Badges y Atributos Específicos -->
                            @if(in_array($serviceType, ['veterinarian', 'trainer', 'groomer']))
                                <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed pl-1">{{ $result->bio ?? 'Sin presentación.' }}</p>
                                <div class="flex flex-wrap gap-2 pt-1">
                                    @if(isset($result->allows_home_visits) && $result->allows_home_visits)
                                        <span class="badge-feature bg-emerald-50/70 text-emerald-700 border-emerald-100 text-[10px] px-2 py-1 rounded-full font-bold border flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
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
                                        <span class="badge-feature bg-indigo-50/70 text-indigo-700 border-indigo-100 text-[10px] px-2 py-1 rounded-full font-bold border flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                            </svg>
                                            Sin Jaulas
                                        </span>
                                    @endif
                                    @if($result->has_transport)
                                        <span class="badge-feature bg-blue-50/70 text-blue-700 border-blue-100 text-[10px] px-2 py-1 rounded-full font-bold border flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125a1.125 1.125 0 001.125-1.125V9.75M3.75 14.25h16.5M3.75 14.25V9.75m16.5 4.5V9.75m0 0a3.75 3.75 0 00-3.75-3.75H8.25m9.75 3.75H3.75M8.25 6h7.5M8.25 6L6.75 9.75m9-3.75l1.5 9.75" />
                                            </svg>
                                            Movilidad
                                        </span>
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
                                    <span class="badge-feature bg-orange-50/70 text-orange-700 border-orange-100 text-[10px] px-2 py-1 rounded-full font-bold border flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                        </svg>
                                        {{ $result->housing_type }}
                                    </span>
                                    @if($result->has_yard)
                                        <span class="badge-feature bg-green-50/70 text-green-700 border-green-100 text-[10px] px-2 py-1 rounded-full font-bold border flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L12 20m0-17c4.97 0 9 4.03 9 9 0 2.12-.74 4.07-1.97 5.61L12 20" />
                                            </svg>
                                            Patio
                                        </span>
                                    @endif
                                    @if($result->allows_home_visits)
                                         <span class="badge-feature bg-teal-50/70 text-teal-700 border-teal-100 text-[10px] px-2 py-1 rounded-full font-bold border flex items-center gap-1">
                                             <svg class="w-3.5 h-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                 <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125a1.125 1.125 0 001.125-1.125V9.75" />
                                             </svg>
                                             Va a casa
                                         </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 line-clamp-2 pl-1">{{ $result->bio }}</p>

                            @elseif($serviceType === 'pet_taxi')
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <span class="badge-feature bg-gray-50 text-gray-700 border-gray-200 text-[10px] px-2 py-1 rounded-full font-bold border flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125a1.125 1.125 0 001.125-1.125V9.75M3.75 14.25h16.5M3.75 14.25V9.75m16.5 4.5V9.75m0 0a3.75 3.75 0 00-3.75-3.75H8.25m9.75 3.75H3.75M8.25 6h7.5M8.25 6L6.75 9.75m9-3.75l1.5 9.75" />
                                        </svg>
                                        {{ $result->vehicle_type }}
                                    </span>
                                    @if($result->has_ac)
                                        <span class="badge-feature bg-sky-50/70 text-sky-700 border-sky-100 text-[10px] px-2 py-1 rounded-full font-bold border flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18m-3-6l-3 3m0 0l-3-3m3 3V3m0 12l-3-3m0 0l-3 3m3-3v6M6 9l3 3m0 0l-3 3m3-3H3m12 0l-3 3m0 0l-3-3m3-3h6" />
                                            </svg>
                                            A/C
                                        </span>
                                    @endif
                                    @if($result->provides_crate)
                                        <span class="badge-feature bg-amber-50/70 text-amber-700 border-amber-100 text-[10px] px-2 py-1 rounded-full font-bold border flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25" />
                                            </svg>
                                            Transportadora
                                        </span>
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
                            <a href="{{ route('profile.show', ['id' => $result->user->id, 'role' => $serviceType]) }}" class="flex-1 text-center bg-primary-600 text-white py-2.5 rounded-xl font-bold hover:bg-primary-700 transition-all shadow-md hover:shadow-lg text-sm flex items-center justify-center group-hover:scale-[1.02]">
                                Ver Perfil
                                <svg class="w-4 h-4 ml-2 text-primary-200 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

@push('scripts')
<script>
    window.initLeafletSearchMap = function(alpineComponent, initialMarkers) {
        const container = L.DomUtil.get('search-map');
        if (container != null) {
            container._leaflet_id = null;
        }

        // Default to Lima center
        alpineComponent.map = L.map('search-map').setView([-12.046374, -77.042793], 12);
        alpineComponent.markersGroup = L.layerGroup().addTo(alpineComponent.map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(alpineComponent.map);

        // Marker updater function
        alpineComponent.updateMarkers = function(markers) {
            alpineComponent.markersGroup.clearLayers();
            
            if (!markers || markers.length === 0) return;

            let bounds = [];
            let providerIcon = L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            markers.forEach(markerData => {
                let marker = L.marker([markerData.lat, markerData.lng], {icon: providerIcon});
                
                // Rich popup content with premium styling
                let popupContent = `
                    <div class="p-2 min-w-[200px] font-sans">
                        <div class="flex items-center gap-3">
                            <img src="${markerData.image}" class="w-10 h-10 rounded-full object-cover border shadow-sm">
                            <div>
                                <h4 class="font-black text-gray-900 text-xs">${markerData.name} ${markerData.level_badge ? ' ' + markerData.level_badge : ''}</h4>
                                <span class="text-[10px] text-gray-400 font-bold uppercase leading-none">${markerData.service}</span>
                            </div>
                        </div>
                        <div class="mt-2.5 flex items-center justify-between border-t border-gray-100 pt-2">
                            <span class="text-[10px] font-bold text-primary-600 bg-primary-50 px-1.5 py-0.5 rounded">${markerData.price}</span>
                            <div class="flex items-center text-yellow-400 gap-0.5 text-xs">
                                ⭐️ <span class="text-gray-700 font-bold">${markerData.rating}</span>
                                <span class="text-gray-400 text-[9px]">(${markerData.reviews_count})</span>
                            </div>
                        </div>
                        <a href="${markerData.url}" class="block text-center bg-primary-600 hover:bg-primary-700 !text-white text-[10px] font-bold py-1.5 rounded-lg mt-3 transition shadow-sm" style="color: white !important;">Ver Perfil</a>
                    </div>
                `;

                marker.bindPopup(popupContent);
                alpineComponent.markersGroup.addLayer(marker);
                bounds.push([markerData.lat, markerData.lng]);
            });

            // Adjust map view to fit markers
            if (bounds.length > 0) {
                alpineComponent.map.fitBounds(bounds, {padding: [50, 50]});
            }
        };

        // Initialize with starting markers
        alpineComponent.updateMarkers(initialMarkers);
    }
</script>
@endpush
