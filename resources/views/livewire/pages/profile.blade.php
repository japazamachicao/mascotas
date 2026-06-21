@push('meta')
    @php
        $roleName = [
            'veterinarian' => 'Veterinario',
            'walker' => 'Paseador de Perros',
            'trainer' => 'Adiestrador Canino',
            'pet_sitter' => 'Cuidador de Mascotas',
            'groomer' => 'Estilista de Mascotas',
            'pet_photographer' => 'Fotógrafo de Mascotas',
            'pet_taxi' => 'Pet Taxi y Transporte',
            'pet_hotel' => 'Hospedaje de Mascotas',
        ][$user->roles->first()->name ?? ''] ?? 'Profesional de Mascotas';
        
        $profileDesc = $profile->bio ?? $profile->experience ?? $profile->description ?? 'Profesional verificado en TodoPeludos.com';
        $profileImage = $user->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0ea5e9&color=fff&size=128';
        $location = ($profile->district) ? $profile->district->name . ', ' . $profile->district->province->name : 'Perú';
        
        $schemaType = ($user->hasRole('veterinarian')) ? 'VeterinaryCare' : 'LocalBusiness';
        
        $price = $profile->price_from ?? $profile->hourly_rate ?? 0;
        $priceRange = $price > 0 ? "Desde S/ " . number_format($price, 2) : "A convenir";
    @endphp
    <title>{{ $user->name }} | {{ $roleName }} en {{ $location }} | TodoPeludos.com</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($profileDesc), 155) }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ $user->name }} | {{ $roleName }} en {{ $location }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($profileDesc), 155) }}">
    <meta property="og:image" content="{{ $profileImage }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="{{ $user->name }} | {{ $roleName }} en {{ $location }}">
    <meta property="twitter:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($profileDesc), 155) }}">
    <meta property="twitter:image" content="{{ $profileImage }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "{{ $schemaType }}",
        "name": "{{ $user->name }}",
        "image": "{{ $profileImage }}",
        "description": "{{ str_replace('"', '\\"', strip_tags($profileDesc)) }}",
        @if($profile->whatsapp_number)
        "telephone": "{{ $profile->whatsapp_number }}",
        @endif
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "{{ $profile->district->name ?? 'Perú' }}",
            "addressRegion": "{{ $profile->district->province->name ?? '' }}",
            "addressCountry": "PE"
        },
        "priceRange": "{{ $priceRange }}"
        @if($this->totalReviews > 0)
        ,"aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "{{ $this->averageRating }}",
            "reviewCount": "{{ $this->totalReviews }}"
        }
        @endif
    }
    </script>
@endpush

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
                    <div class="absolute bottom-1 right-1 bg-emerald-500 text-white rounded-full p-1.5 border-2 border-white shadow-sm flex items-center justify-center" title="Perfil Verificado">
                        <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 24 24">
                            <path d="M23 12l-2.44-2.79.34-3.69-3.61-.82-1.89-3.2L12 2.96 8.6 1.5 6.71 4.7l-3.61.81.34 3.68L1 12l2.44 2.79-.34 3.69 3.61.82 1.89 3.2L12 21.04l3.4 1.46 1.89-3.2 3.61-.82-.34-3.68L23 12zm-13 5l-4-4 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                @endif
             </div>
             
             <div class="text-center mt-3">
                 <h1 class="text-2xl font-extrabold text-white tracking-tight sm:text-3xl shadow-sm">{{ $user->name }}</h1>
                 <div class="flex items-center justify-center flex-wrap gap-2">
                    @php
                        $roleLabels = [
                            'veterinarian' => '🩺 Veterinario',
                            'walker' => '🐕 Paseador',
                            'groomer' => '✂️ Estilista',
                            'hotel' => '🏨 Hotel Canino',
                            'shelter' => '🏠 Albergue',
                            'trainer' => '🎓 Adiestrador',
                            'pet_sitter' => '🐾 Cuidador',
                            'pet_taxi' => '🚗 Transporte',
                            'pet_photographer' => '📸 Fotógrafo',
                        ];
                    @endphp
                    @foreach($allProfiles as $roleName => $roleProfile)
                        <button wire:click="switchProfileRole('{{ $roleName }}')"
                            class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full transition-all duration-200 border backdrop-blur-sm
                            {{ $selectedRole === $roleName 
                                ? 'bg-white text-primary-800 border-white shadow-md' 
                                : 'bg-white/15 text-white border-white/10 hover:bg-white/30' }}">
                            {{ $roleLabels[$roleName] ?? 'Profesional' }}
                        </button>
                    @endforeach
                 </div>

                 @if(!empty($providerLevel))
                     <div class="mt-2.5 flex items-center justify-center">
                         <x-level-badge level="{{ $providerLevel['name'] }}" size="sm" class="bg-white/20 text-white border-white/10 backdrop-blur-xs" />
                     </div>
                 @endif

                 <!-- Rating Promedio (Nuevo) -->
                 @if($this->totalReviews > 0)
                    <div class="flex items-center justify-center mt-1 text-yellow-400">
                        <span class="text-white text-sm font-bold mr-1">{{ $this->averageRating }}</span>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <span class="text-gray-300 text-xs ml-1">({{ $this->totalReviews }} reseñas)</span>
                    </div>
                 @endif
                 <!-- Redes Sociales (Movido al Header) -->
                 <div class="mt-4 flex items-center justify-center space-x-4">
                     @if($profile->website_url)
                         <a href="{{ $profile->website_url }}" target="_blank" class="group relative p-2 bg-white/10 rounded-full hover:bg-white/20 transition-all duration-300" title="Sitio Web">
                             <svg class="w-5 h-5 text-gray-200 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                         </a>
                     @endif
                     
                     @if($profile->facebook_url)
                         <a href="{{ $profile->facebook_url }}" target="_blank" class="group relative p-2 bg-white/10 rounded-full hover:bg-[#1877F2]/20 transition-all duration-300" title="Facebook">
                             <svg class="w-5 h-5 text-gray-200 group-hover:text-[#1877F2] fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                         </a>
                     @endif
                     
                     @if($profile->instagram_url)
                         <a href="{{ $profile->instagram_url }}" target="_blank" class="group relative p-2 bg-white/10 rounded-full hover:bg-[#E1306C]/20 transition-all duration-300" title="Instagram">
                             <svg class="w-5 h-5 text-gray-200 group-hover:text-[#E1306C] fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                         </a>
                     @endif

                     @if($profile->tiktok_url ?? false)
                         <a href="{{ $profile->tiktok_url }}" target="_blank" class="group relative p-2 bg-white/10 rounded-full hover:bg-black/20 transition-all duration-300" title="TikTok">
                             <svg class="w-5 h-5 text-gray-200 group-hover:text-white fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                         </a>
                     @endif
                 </div>
             </div>
             <div class="mt-2 flex flex-wrap justify-center gap-2">
                 @if(($profile->district))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white border border-white/30 backdrop-blur-sm">
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
            <div x-data="{ show: true }" x-show="show" class="mb-6 rounded-xl bg-green-50 p-4 border border-green-200 shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 text-green-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-green-800">{{ session('message') }}</p>
                        @if($waLink)
                            <a href="{{ $waLink }}" target="_blank"
                               class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-bold rounded-lg transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Confirmar por WhatsApp
                            </a>
                        @endif
                    </div>
                    <button @click="show = false" class="text-green-400 hover:text-green-600">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
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
                        <button wire:click="$set('activeTab', 'services')" class="flex-1 py-4 px-1 text-center text-sm font-medium hover:bg-gray-50 transition {{ $activeTab === 'services' ? 'text-primary-600 border-b-2 border-primary-500 bg-primary-50' : 'text-gray-500' }}">
                            Servicios <span class="ml-1 bg-gray-100 text-gray-600 py-0.5 px-2 rounded-full text-xs">{{ $providerServices->count() }}</span>
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
                                        <p class="text-gray-600 text-sm flex items-start mb-3">
                                            <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            {{ $profile->address }}
                                        </p>
                                        @if($profile->latitude && $profile->longitude)
                                            <div id="profile-map" class="h-64 w-full rounded-xl border border-gray-200 mt-2 z-10" 
                                                 x-data="{ initMap() { this.$nextTick(() => { window.initLeafletMapForProfile({{ $profile->latitude }}, {{ $profile->longitude }}); }); } }" 
                                                 x-init="initMap()" 
                                                 wire:ignore></div>
                                        @endif
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
                                                         <span class="bg-white text-rose-600 border border-rose-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-sm">
                                                             <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                                 <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                             </svg>
                                                             Emergencias 24h
                                                         </span>
                                                     @endif
                                                     @if($profile->allows_home_visits ?? false)
                                                         <span class="bg-white text-emerald-600 border border-emerald-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-sm">
                                                             <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                                 <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                             </svg>
                                                             A Domicilio
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
                                                     <span class="bg-white text-pink-600 border border-pink-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-sm">
                                                         <svg class="w-4 h-4 text-pink-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                             <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                                         </svg>
                                                         Servicio a Domicilio
                                                     </span>
                                                 @else
                                                     <span class="bg-white text-gray-600 border border-gray-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-sm">
                                                         <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                             <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                                         </svg>
                                                         Atención en Local
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
                                                     <span class="bg-white text-indigo-600 border border-indigo-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-sm">
                                                         <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                             <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                                         </svg>
                                                         Sin Jaulas
                                                     </span>
                                                 @endif
                                                 @if($profile->has_transport)
                                                     <span class="bg-white text-indigo-600 border border-indigo-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-sm">
                                                         <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                             <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125a1.125 1.125 0 001.125-1.125V9.75M3.75 14.25h16.5M3.75 14.25V9.75m16.5 4.5V9.75m0 0a3.75 3.75 0 00-3.75-3.75H8.25m9.75 3.75H3.75M8.25 6h7.5M8.25 6L6.75 9.75m9-3.75l1.5 9.75" />
                                                         </svg>
                                                         Movilidad Incluida
                                                     </span>
                                                 @endif
                                                 @if($profile->emergency_24h ?? false)
                                                     <span class="bg-white text-red-600 border border-red-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-sm">
                                                         <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                         </svg>
                                                         Atención 24h
                                                     </span>
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
                                                 <span class="bg-white text-orange-600 border border-orange-200 text-xs px-3 py-1.5 rounded-md font-bold flex items-center gap-1.5 shadow-sm">
                                                      <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                                      </svg>
                                                      Tipo de Vivienda: {{ $profile->housing_type }}
                                                 </span>
                                                 @if($profile->has_yard)
                                                    <span class="bg-white text-green-600 border border-green-200 text-xs px-3 py-1.5 rounded-md font-bold flex items-center gap-1.5 shadow-sm">
                                                         <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L12 20m0-17c4.97 0 9 4.03 9 9 0 2.12-.74 4.07-1.97 5.61L12 20" />
                                                         </svg>
                                                         Tiene Patio
                                                    </span>
                                                 @endif
                                                 @if($profile->allows_home_visits ?? false)
                                                    <span class="bg-white text-teal-600 border border-teal-200 text-xs px-3 py-1.5 rounded-md font-bold flex items-center gap-1.5 shadow-sm">
                                                         <svg class="w-4 h-4 text-teal-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                             <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125a1.125 1.125 0 001.125-1.125V9.75" />
                                                         </svg>
                                                         Va a tu casa
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
                                                         <span class="bg-white text-sky-600 border border-sky-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-sm">
                                                             <svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                                 <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18m-3-6l-3 3m0 0l-3-3m3 3V3m0 12l-3-3m0 0l-3 3m3-3v6M6 9l3 3m0 0l-3 3m3-3H3m12 0l-3 3m0 0l-3-3m3-3h6" />
                                                             </svg>
                                                             Aire Acondicionado
                                                         </span>
                                                     @endif
                                                     @if($profile->provides_crate)
                                                         <span class="bg-white text-amber-600 border border-amber-200 text-xs px-3 py-1.5 rounded-full font-bold flex items-center gap-1.5 shadow-sm">
                                                             <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                                 <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25" />
                                                             </svg>
                                                             Transportadora
                                                         </span>
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
                                                    <span class="bg-white text-cyan-600 border border-cyan-200 text-xs px-3 py-1.5 rounded-md font-bold flex items-center gap-1.5 shadow-sm">
                                                        <svg class="w-4 h-4 text-cyan-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                                                        </svg>
                                                        Especialidad: {{ $profile->specialty ?? 'General' }}
                                                    </span>
                                                    @if($profile->has_studio ?? false)
                                                       <span class="bg-white text-cyan-600 border border-cyan-200 text-xs px-3 py-1.5 rounded-md font-bold flex items-center gap-1.5 shadow-sm">
                                                           <svg class="w-4 h-4 text-cyan-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                               <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z" />
                                                           </svg>
                                                           Estudio Propio
                                                       </span>
                                                    @else
                                                        <span class="bg-white text-cyan-600 border border-cyan-200 text-xs px-3 py-1.5 rounded-md font-bold flex items-center gap-1.5 shadow-sm">
                                                            <svg class="w-4 h-4 text-cyan-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z" />
                                                            </svg>
                                                            Exteriores / A Domicilio
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
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300 flex items-center justify-center">
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
                                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-md mb-8">
                                            <div class="mb-5">
                                                <h4 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                                    <svg class="h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Escribe una reseña
                                                </h4>
                                                <p class="text-xs text-gray-400 mt-1">Comparte tu experiencia para ayudar a otros dueños de mascotas.</p>
                                            </div>
                                            
                                            <form wire:submit.prevent="saveReview" class="space-y-5">
                                                <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                    <div>
                                                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Tu Calificación</label>
                                                        <div class="flex items-center space-x-1">
                                                            @foreach([1, 2, 3, 4, 5] as $start)
                                                                <button type="button" wire:click="$set('rating', {{ $start }})" class="focus:outline-none transition-transform duration-150 active:scale-95 hover:scale-110">
                                                                    <svg class="w-8 h-8 {{ $rating >= $start ? 'text-yellow-400 filter drop-shadow-sm' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                    </svg>
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="sm:text-right">
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold bg-white border border-gray-100 shadow-sm text-primary-600 transition-all duration-300">
                                                            @switch($rating)
                                                                @case(1) 😠 Pésimo @break
                                                                @case(2) ☹️ Malo @break
                                                                @case(3) 😐 Regular @break
                                                                @case(4) 🙂 Muy bueno @break
                                                                @case(5) 🤩 ¡Excelente! @break
                                                            @endswitch
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="space-y-1">
                                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Tu Comentario</label>
                                                    <textarea wire:model="comment" rows="3" class="w-full border border-gray-200 rounded-xl shadow-inner bg-gray-50/30 focus:bg-white focus:ring-4 focus:ring-primary-100 focus:border-primary-500 sm:text-sm p-4 transition-all duration-300 placeholder-gray-400" placeholder="Cuéntanos sobre la atención, puntualidad y cuidado de tu mascota..."></textarea>
                                                    @error('comment') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                                                </div>

                                                <div class="flex justify-end pt-2">
                                                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent shadow-md text-sm font-bold rounded-xl text-white bg-primary-600 hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-500/20 transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none transition-all duration-200">
                                                        Publicar Mi Reseña
                                                    </button>
                                                </div>
                                            </form>
                                            @if ($errors->has('review'))
                                                <div class="mt-3 text-red-600 text-sm font-medium bg-red-50 p-3 rounded-lg border border-red-100">{{ $errors->first('review') }}</div>
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

                                                @if($review->provider_response)
                                                    <div class="mt-3 p-3 bg-gray-50 rounded-xl border border-gray-100 relative">
                                                        <div class="absolute top-0 left-6 -mt-1.5 w-3 h-3 bg-gray-50 border-t border-l border-gray-100 transform rotate-45"></div>
                                                        <div class="flex items-start gap-2.5">
                                                            <div class="shrink-0">
                                                                <img class="h-6 w-6 rounded-full border bg-white object-cover" src="{{ $user->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0ea5e9&color=fff' }}" alt="">
                                                            </div>
                                                            <div>
                                                                <div class="flex items-center gap-2">
                                                                    <h6 class="text-xs font-bold text-gray-900">Respuesta de {{ $user->name }}</h6>
                                                                    <span class="text-[9px] text-gray-400 font-semibold">{{ \Carbon\Carbon::parse($review->replied_at)->diffForHumans() }}</span>
                                                                </div>
                                                                <p class="text-xs text-gray-600 mt-0.5 font-medium">{{ $review->provider_response }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
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
                        @elseif($activeTab === 'services')
                            @if($providerServices->count() > 0)
                                <div class="space-y-4">
                                    <h3 class="text-gray-900 font-bold mb-4">Catálogo de Servicios</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($providerServices as $service)
                                            <div class="p-4 rounded-xl border border-gray-100 bg-white shadow-sm flex flex-col justify-between hover:shadow-md transition">
                                                <div>
                                                    <h4 class="font-bold text-gray-900 text-base">{{ $service->name }}</h4>
                                                    @if($service->description)
                                                        <p class="text-sm text-gray-500 mt-1">{{ $service->description }}</p>
                                                    @endif
                                                </div>
                                                <div class="mt-4 flex items-center justify-between border-t pt-3">
                                                    @if($service->duration_minutes)
                                                        <span class="text-xs text-gray-400 flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            {{ $service->duration_minutes }} min
                                                        </span>
                                                    @else
                                                        <span></span>
                                                    @endif
                                                    <span class="text-lg font-extrabold text-primary-600">S/ {{ number_format($service->price, 2) }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                    <p class="mt-2">Este proveedor aún no ha registrado servicios en su catálogo.</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Columna Derecha (1/3) - Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Tarjeta de Acción / Reserva -->
                <div class="bg-white rounded-xl shadow-lg border border-primary-100 p-6 sticky top-6">
                    <div class="text-center mb-6">
                        <p class="text-sm text-gray-500 mb-1">Precio</p>
                        <p class="text-3xl font-extrabold text-gray-900">
                            @if(!empty($profile->price_from) && $profile->price_from > 0)
                                <span class="text-base font-normal text-gray-500">desde </span>S/ {{ number_format($profile->price_from, 0) }}
                            @elseif(isset($profile->hourly_rate) && $profile->hourly_rate > 0)
                                S/ {{ number_format($profile->hourly_rate, 0) }} <span class="text-base font-normal text-gray-500">/hora</span>
                            @else
                                <span class="text-xl text-gray-400">Consultar</span>
                            @endif
                        </p>
                    </div>

                    @auth
                        <button wire:click="openBookingModal" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition">
                            Reservar Cita
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition">
                            Iniciar sesión para reservar
                        </a>
                    @endauth

                    <button wire:click="openContactModal" class="w-full mt-3 flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                        Contactar
                    </button>

                    @auth
                    <button wire:click="toggleFavorite" class="w-full mt-3 flex justify-center items-center py-3 px-4 border rounded-lg shadow-sm text-sm font-bold focus:outline-none transition {{ $isFavorite ? 'border-red-200 bg-red-50 text-red-600 hover:bg-red-100' : 'border-gray-200 text-gray-500 hover:bg-gray-50' }}">
                        @if($isFavorite)
                            <svg class="w-5 h-5 mr-2 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            Guardado en Favoritos
                        @else
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            Guardar como Favorito
                        @endif
                    </button>
                    @endauth

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


            </div>

        </div>
    </main>

    <!-- Modal de Contacto -->
    @if($showContactModal)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true" wire:click="closeContactModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="relative z-10 inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-sm sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Contacto Directo</h3>
                                <div class="mt-4 space-y-4">
                                    @if($profile->whatsapp_number ?? false)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $profile->whatsapp_number) }}?text=Hola,%20vi%20tu%20perfil%20en%20TodoPeludos.com" target="_blank" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition">
                                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.463 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                            Chatear por WhatsApp
                                        </a>
                                    @else
                                        <p class="text-sm text-gray-500 text-center italic mb-2">Este usuario no ha registrado WhatsApp.</p>
                                    @endif
                                    <a href="{{ route('dashboard.messages', ['contactId' => $user->id]) }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 transition">
                                        💬 Chat Interno
                                    </a>
                                    <a href="mailto:{{ $user->email }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $user->email }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="closeContactModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($selectedImage)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 transition-opacity p-4" x-data @keydown.window.escape="$wire.closeImage()">
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
                <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true" wire:click="$set('showBookingModal', false)"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
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
                                            <label for="selectedPetId" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Mascota</label>
                                            @if($this->pets->count() > 0)
                                                <select id="selectedPetId" wire:model="selectedPetId" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                                    <option value="">Selecciona tu mascota</option>
                                                    @foreach($this->pets as $pet)
                                                        <option value="{{ $pet->id }}">{{ $pet->name }} ({{ $pet->species }})</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <div class="mt-1 text-xs text-red-600 bg-red-50 p-2.5 rounded border border-red-200">
                                                    No tienes mascotas registradas.
                                                    <a href="{{ route('dashboard.pet.create') }}" class="font-bold underline hover:text-red-800">Registrar mascota aquí</a> primero para poder agendar una cita.
                                                </div>
                                            @endif
                                            @error('selectedPetId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="appointmentDate" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Fecha</label>
                                            <input id="appointmentDate" type="date" wire:model="appointmentDate" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                            @error('appointmentDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="appointmentTime" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Hora</label>
                                            <input id="appointmentTime" type="time" wire:model="appointmentTime" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                            @error('appointmentTime') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="appointmentNotes" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Notas / Detalles</label>
                                            <textarea id="appointmentNotes" wire:model="appointmentNotes" rows="2" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Raza, tamaño, necesidades especiales..."></textarea>
                                        </div>

                                        @if($providerServices && $providerServices->count() > 0)
                                            <div class="border-t pt-4">
                                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Servicios a contratar</label>
                                                <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-md p-3 bg-gray-50/50">
                                                    @foreach($providerServices as $service)
                                                        <label class="flex items-start gap-3 p-2 rounded-lg hover:bg-white hover:shadow-sm transition cursor-pointer border border-transparent hover:border-gray-100">
                                                            <input type="checkbox" wire:model.live="selectedServices" value="{{ $service->id }}" class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                                            <div class="flex-1">
                                                                <div class="flex justify-between font-semibold text-gray-900 text-sm">
                                                                    <span>{{ $service->name }}</span>
                                                                    <span>S/ {{ number_format($service->price, 2) }}</span>
                                                                </div>
                                                                @if($service->description)
                                                                    <p class="text-xs text-gray-500">{{ $service->description }}</p>
                                                                @endif
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @error('selectedServices') <span class="text-red-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                                            </div>
                                        @endif

                                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                                            <span class="text-sm font-bold text-gray-700 uppercase">Monto Total Estimado:</span>
                                            <span class="text-xl font-extrabold text-primary-600">S/ {{ number_format($totalPrice, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        @if($this->pets->count() > 0)
                            <button type="button" wire:click="bookAppointment" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Enviar Solicitud
                            </button>
                        @else
                            <button type="button" disabled class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-400 text-base font-medium text-white cursor-not-allowed sm:ml-3 sm:w-auto sm:text-sm">
                                Enviar Solicitud
                            </button>
                        @endif
                        <button type="button" wire:click="$set('showBookingModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    window.initLeafletMapForProfile = function(lat, lng) {
        const container = L.DomUtil.get('profile-map');
        if (container != null) {
            container._leaflet_id = null;
        }

        const map = L.map('profile-map').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let providerIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        L.marker([lat, lng], {icon: providerIcon}).addTo(map);
    }

    window.addEventListener('profile-role-changed', event => {
        const data = event.detail;
        if (data.latitude && data.longitude) {
            window.initLeafletMapForProfile(data.latitude, data.longitude);
        }
    });
</script>
@endpush
