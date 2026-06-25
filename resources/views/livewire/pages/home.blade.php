@php
    $srcFile = 'C:/Users/japaz/.gemini/antigravity-ide/brain/02cc8654-615e-4a8b-a7aa-88ff23a52c36/hero_dog_1781377895901.png';
    $destFile = public_path('hero-dog.png');
    if (file_exists($srcFile) && !file_exists($destFile)) {
        @copy($srcFile, $destFile);
    }
@endphp

@push('meta')
    <title>TodoPeludos | Servicios y Cuidado de Mascotas en Perú</title>
    <meta name="description" content="Busca y reserva veterinarios colegiados, paseadores certificados, cuidadores y hospedajes de mascotas cerca de ti en Lima y todo el Perú.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="TodoPeludos | Servicios y Cuidado de Mascotas en Perú">
    <meta property="og:description" content="Busca y reserva veterinarios colegiados, paseadores certificados, cuidadores y hospedajes de mascotas cerca de ti en Lima y todo el Perú.">
    <meta property="og:image" content="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="TodoPeludos | Servicios y Cuidado de Mascotas en Perú">
    <meta property="twitter:description" content="Busca y reserva veterinarios colegiados, paseadores certificados, cuidadores y hospedajes de mascotas cerca de ti en Lima y todo el Perú.">
    <meta property="twitter:image" content="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80">

    <!-- Schema.org WebSite JSON-LD -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "WebSite",
        "name": "TodoPeludos",
        "url": "{{ url('/') }}",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ url('/search') }}?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>

    <style>
        /* Bulletproof Grid Layout for Hero Section */
        .hero-grid {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 2rem !important;
            align-items: center !important;
            width: 100% !important;
        }
        
        .hero-left {
            grid-column: span 1 / span 1 !important;
            width: 100% !important;
        }
        
        .hero-right {
            grid-column: span 1 / span 1 !important;
            width: 100% !important;
        }
        
        @media (min-width: 768px) {
            .hero-grid {
                grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
            }
            .hero-left {
                grid-column: span 6 / span 6 !important;
            }
            .hero-right {
                grid-column: span 6 / span 6 !important;
            }
        }
        
        /* Category Card Premium Hover States */
        .category-card {
            background: #ffffff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 1.5rem !important;
            padding: 2rem !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative !important;
            overflow: hidden !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            height: 100% !important;
            text-decoration: none !important;
        }
        
        .category-card .icon-container {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 3rem !important;
            height: 3rem !important;
            border-radius: 1rem !important;
            margin-bottom: 1.25rem !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            border: 1px solid transparent !important;
            z-index: 10 !important;
            position: relative !important;
        }
        
        .category-card-blob {
            position: absolute !important;
            right: -2rem !important;
            bottom: -2rem !important;
            width: 7rem !important;
            height: 7rem !important;
            border-radius: 50% !important;
            filter: blur(24px) !important;
            opacity: 0 !important;
            transition: opacity 0.4s ease, transform 0.4s ease !important;
            pointer-events: none !important;
            z-index: 1 !important;
        }
        
        .category-card:hover .category-card-blob {
            opacity: 0.15 !important;
            transform: scale(1.1) !important;
        }
        
        .category-card:hover {
            text-decoration: none !important;
        }
        
        .category-card:hover .icon-container {
            transform: scale(1.1) !important;
        }

        /* Custom Category Card Hovers & Colors */
        /* Teal */
        .category-card-teal {
            --card-color: #0d9488;
        }
        .category-card-teal .icon-container {
            background-color: #f0fdfa !important;
            color: #0d9488 !important;
            border-color: #ccfbf1 !important;
        }
        .category-card-teal:hover {
            border-color: #0d9488 !important;
            transform: translateY(-6px) !important;
            box-shadow: 0 20px 25px -5px rgba(13, 148, 136, 0.12), 0 8px 10px -6px rgba(13, 148, 136, 0.06) !important;
        }
        .category-card-teal:hover .icon-container {
            background-color: #0d9488 !important;
            color: #ffffff !important;
            border-color: #0d9488 !important;
        }
        .category-card-teal:hover h3 {
            color: #0d9488 !important;
        }
        
        /* Orange */
        .category-card-orange {
            --card-color: #ea580c;
        }
        .category-card-orange .icon-container {
            background-color: #fff7ed !important;
            color: #ea580c !important;
            border-color: #ffedd5 !important;
        }
        .category-card-orange:hover {
            border-color: #ea580c !important;
            transform: translateY(-6px) !important;
            box-shadow: 0 20px 25px -5px rgba(234, 88, 12, 0.12), 0 8px 10px -6px rgba(234, 88, 12, 0.06) !important;
        }
        .category-card-orange:hover .icon-container {
            background-color: #ea580c !important;
            color: #ffffff !important;
            border-color: #ea580c !important;
        }
        .category-card-orange:hover h3 {
            color: #ea580c !important;
        }
        
        /* Pink */
        .category-card-pink {
            --card-color: #db2777;
        }
        .category-card-pink .icon-container {
            background-color: #fdf2f8 !important;
            color: #db2777 !important;
            border-color: #fce7f3 !important;
        }
        .category-card-pink:hover {
            border-color: #db2777 !important;
            transform: translateY(-6px) !important;
            box-shadow: 0 20px 25px -5px rgba(219, 39, 119, 0.12), 0 8px 10px -6px rgba(219, 39, 119, 0.06) !important;
        }
        .category-card-pink:hover .icon-container {
            background-color: #db2777 !important;
            color: #ffffff !important;
            border-color: #db2777 !important;
        }
        .category-card-pink:hover h3 {
            color: #db2777 !important;
        }
        
        /* Indigo */
        .category-card-indigo {
            --card-color: #4f46e5;
        }
        .category-card-indigo .icon-container {
            background-color: #eef2ff !important;
            color: #4f46e5 !important;
            border-color: #e0e7ff !important;
        }
        .category-card-indigo:hover {
            border-color: #4f46e5 !important;
            transform: translateY(-6px) !important;
            box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.12), 0 8px 10px -6px rgba(79, 70, 229, 0.06) !important;
        }
        .category-card-indigo:hover .icon-container {
            background-color: #4f46e5 !important;
            color: #ffffff !important;
            border-color: #4f46e5 !important;
        }
        .category-card-indigo:hover h3 {
            color: #4f46e5 !important;
        }
        
        /* Yellow */
        .category-card-yellow {
            --card-color: #d97706;
        }
        .category-card-yellow .icon-container {
            background-color: #fffbeb !important;
            color: #d97706 !important;
            border-color: #fef3c7 !important;
        }
        .category-card-yellow:hover {
            border-color: #d97706 !important;
            transform: translateY(-6px) !important;
            box-shadow: 0 20px 25px -5px rgba(217, 119, 6, 0.12), 0 8px 10px -6px rgba(217, 119, 6, 0.06) !important;
        }
        .category-card-yellow:hover .icon-container {
            background-color: #d97706 !important;
            color: #ffffff !important;
            border-color: #d97706 !important;
        }
        .category-card-yellow:hover h3 {
            color: #d97706 !important;
        }
        
        /* Green */
        .category-card-green {
            --card-color: #059669;
        }
        .category-card-green .icon-container {
            background-color: #f0fdf4 !important;
            color: #059669 !important;
            border-color: #dcfce7 !important;
        }
        .category-card-green:hover {
            border-color: #059669 !important;
            transform: translateY(-6px) !important;
            box-shadow: 0 20px 25px -5px rgba(5, 150, 105, 0.12), 0 8px 10px -6px rgba(5, 150, 105, 0.06) !important;
        }
        .category-card-green:hover .icon-container {
            background-color: #059669 !important;
            color: #ffffff !important;
            border-color: #059669 !important;
        }
        .category-card-green:hover h3 {
            color: #059669 !important;
        }

        /* Provider Card Premium Hover State */
        .provider-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        .provider-card:hover {
            transform: translateY(-4px) !important;
            border-color: rgba(2, 132, 199, 0.2) !important;
            box-shadow: 0 20px 25px -5px rgba(2, 132, 199, 0.05), 0 10px 10px -5px rgba(2, 132, 199, 0.02) !important;
        }
    </style>
@endpush

<div class="relative bg-white overflow-hidden font-sans">
    
    <!-- Hero Section con Estructura de Dos Columnas y Fondo Suave -->
    <div class="relative bg-slate-50/40 pt-16 pb-28 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Accent circles in background -->
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-96 h-96 rounded-full bg-primary-100/30 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-96 h-96 rounded-full bg-indigo-100/20 blur-3xl pointer-events-none"></div>

        <div class="hero-grid max-w-7xl mx-auto items-center relative">
            
            <!-- Columna Izquierda: Mensajes y Buscador -->
            <div class="hero-left sm:text-center md:max-w-2xl md:mx-auto md:text-left space-y-6">
                
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl leading-tight">
                    Cuidamos a tu engreído <br>
                    <span class="text-primary-600">como tú lo harías</span>
                </h1>
                
                <p class="text-lg text-gray-500 max-w-xl">
                    Encuentra veterinarios colegiados, paseadores certificados y hospedajes con amor familiar cerca de tu zona.
                </p>

                <!-- Buscador Formato Píldora Premium -->
                <div class="bg-white rounded-full shadow-lg border border-gray-100 p-2 max-w-xl transform transition hover:shadow-xl">
                    <form action="{{ route('search') }}" method="GET" class="flex items-center justify-between">
                        <div class="flex-grow relative flex items-center pl-4">
                            <svg class="h-5 w-5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="q" style="border: none !important; background: transparent !important; padding: 0 !important; box-shadow: none !important; outline: none !important;" class="w-full focus:outline-none focus:ring-0 text-gray-800 placeholder-gray-400 text-sm sm:text-base ml-2" placeholder="¿Qué buscas? Ej: Veterinario en Miraflores...">
                        </div>
                        <button type="submit" class="bg-primary-600 text-white rounded-full px-6 sm:px-8 py-3 font-bold text-sm sm:text-base hover:bg-primary-700 focus:outline-none transition-colors shadow-md shrink-0">
                            Buscar
                        </button>
                    </form>
                </div>
                
                <!-- Proof points -->
                <div class="pt-4 flex flex-wrap gap-4 text-xs font-bold text-gray-500 justify-center md:justify-start">
                    <span class="flex items-center gap-1">
                        <span class="text-yellow-400 text-base"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg></span> {{ $avgRating }}/5 Calificación de Clientes
                    </span>
                    <span class="text-gray-300">|</span>
                    <span class="flex items-center gap-1">
                        <span class="text-green-500 text-base"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></span> Proveedores {{ $verifiedPercentage }}% Verificados
                    </span>
                </div>
            </div>

            <!-- Columna Derecha: Imagen Ilustrativa con Efecto Premium -->
            <div class="hero-right mt-12 md:mt-0 relative">
                <div class="relative mx-auto w-full max-w-md lg:max-w-lg">
                    <!-- Background blobs -->
                    <div class="absolute top-4 left-4 w-72 h-72 bg-yellow-200/50 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
                    <div class="absolute -bottom-8 -right-8 w-72 h-72 bg-primary-200/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>

                    <!-- Main Image Container -->
                    <div class="relative bg-white rounded-3xl p-3 shadow-2xl border border-gray-100 rotate-1 max-w-md mx-auto">
                        @php
                            $localImage = 'hero-dog.png';
                            $imageSrc = file_exists(public_path($localImage)) 
                                ? asset($localImage) 
                                : 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
                        @endphp
                        <img class="w-full h-80 md:h-[420px] lg:h-[450px] object-cover rounded-2xl" src="{{ $imageSrc }}" alt="Mascotas y Cuidado">
                        
                        <!-- Floating Card Badge -->
                        <div class="absolute -bottom-5 -left-5 bg-white p-4 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-3">
                            <span class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-2xl bg-green-50 text-green-600 border border-green-100 shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </span>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Servicio Seguro</p>
                                <p class="text-sm font-extrabold text-gray-800">Cuidado Certificado</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>    <!-- Categorías de Servicios Estandarizadas -->
    <br><br><br><br>
    <div class="pt-28 pb-24 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-primary-600 font-extrabold tracking-wide uppercase text-sm">Categorías</span>
                <h2 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Todo lo que tu mascota necesita
                </h2>
                <p class="mt-4 text-base text-gray-500 max-w-2xl mx-auto">
                    Explora nuestros servicios especializados para darle la mejor calidad de vida a tu peludo.
                </p>
            </div>

            @php
                $services = [
                    [
                        'type' => 'veterinarian',
                        'label' => 'Veterinarios',
                        'desc' => 'Consultas presenciales o a domicilio con especialistas calificados.',
                        'color' => 'teal',
                        'icon_html' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.5v5m-2.5-2.5h5" /></svg>'
                    ],
                    [
                        'type' => 'walker',
                        'label' => 'Paseadores',
                        'desc' => 'Paseos seguros con profesionales certificados cerca de tu zona.',
                        'color' => 'orange',
                        'icon_html' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>'
                    ],
                    [
                        'type' => 'groomer',
                        'label' => 'Estilistas / Baño',
                        'desc' => 'Baño, corte y estética para que tu mascota siempre luzca perfecta.',
                        'color' => 'pink',
                        'icon_html' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="6" cy="6" r="3" /><circle cx="6" cy="18" r="3" /><path stroke-linecap="round" stroke-linejoin="round" d="M20 4L8.5 11M20 20L8.5 13M19 12h2" /></svg>'
                    ],
                    [
                        'type' => 'hotel',
                        'label' => 'Hospedajes',
                        'desc' => 'Hospedaje con amor familiar cuando tú no puedes estar.',
                        'color' => 'indigo',
                        'icon_html' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>'
                    ],
                    [
                        'type' => 'trainer',
                        'label' => 'Adiestradores',
                        'desc' => 'Educación canina con métodos positivos. A domicilio o en su establecimiento.',
                        'color' => 'yellow',
                        'icon_html' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>'
                    ],
                    [
                        'type' => 'pet_sitter',
                        'label' => 'Cuidadores',
                        'desc' => 'Cuidan a tu mascota en su propio hogar con toda la atención que merece.',
                        'color' => 'green',
                        'icon_html' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>'
                    ],
                ];

                $colorMaps = [
                    'teal' => ['text' => 'text-teal-600'],
                    'orange' => ['text' => 'text-orange-600'],
                    'pink' => ['text' => 'text-pink-600'],
                    'indigo' => ['text' => 'text-indigo-600'],
                    'yellow' => ['text' => 'text-amber-600'],
                    'green' => ['text' => 'text-emerald-600'],
                ];
            @endphp

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($services as $s)
                @php
                    $cmap = $colorMaps[$s['color']] ?? ['text' => 'text-primary-600'];
                @endphp
                <a href="{{ route('search', ['serviceType' => $s['type']]) }}" class="category-card category-card-{{ $s['color'] }} group">
                    <!-- Glow background blob -->
                    <div class="category-card-blob"></div>
                    
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <span class="icon-container">
                                {!! $s['icon_html'] !!}
                            </span>
                            <h3 class="text-lg font-extrabold text-gray-900 mb-2 transition-colors">{{ $s['label'] }}</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">{{ $s['desc'] }}</p>
                        </div>
                        <div class="mt-4 text-xs font-bold {{ $cmap['text'] }} group-hover:underline flex items-center gap-1.5">
                            Explorar proveedores 
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Botón Ver Todo Sólido 
            <div class="mt-12 text-center">
                <a href="{{ route('search') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-primary-600 hover:bg-primary-700 transition shadow-md">
                    Explorar todos los proveedores
                    <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </a>
            </div>
            -->
        </div>
    </div>

    <br><br>
    <!-- SECCIÓN: Proveedores Destacados -->
    <div class="pt-28 pb-24 bg-slate-50/40 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-primary-600 font-extrabold tracking-wide uppercase text-sm">Destacados</span>
                <h2 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Nuestros mejores cuidadores
                </h2>
                <p class="mt-4 text-base text-gray-500 max-w-2xl mx-auto">
                    Reserva con profesionales calificados y valorados por la comunidad de TodoPeludos.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($featuredProviders as $provider)
                    @php
                        $pData = $this->getProviderData($provider);
                    @endphp
                    <div class="provider-card bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between relative group">
                        
                        <!-- Verified badge top corner -->
                        <div class="absolute top-4 right-4 bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-full font-bold flex items-center border border-blue-100 shadow-sm z-10">
                            ✓ Verificado
                        </div>

                        <div>
                            <!-- Header with avatar & name -->
                            <div class="flex items-center gap-4 mb-4">
                                <img class="w-14 h-14 rounded-full object-cover border-2 border-gray-100 shadow-sm" src="{{ $pData['photo'] }}" alt="{{ $pData['name'] }}">
                                <div>
                                    <h3 class="font-extrabold text-gray-900 text-lg leading-snug">{{ $pData['name'] }}</h3>
                                    <p class="text-xs text-primary-600 font-bold uppercase tracking-wider mt-0.5">{{ $pData['title'] }}</p>
                                </div>
                            </div>

                            <!-- Rating -->
                            <div class="flex items-center gap-2 mb-3 bg-gray-50 py-1.5 px-3 rounded-xl border border-gray-100 w-fit">
                                <div class="flex text-yellow-400">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-4 h-4 {{ $pData['rating'] >= $i ? 'fill-current' : 'text-gray-200 stroke-current' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs font-extrabold text-gray-800">{{ $pData['rating'] }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">({{ $pData['reviews_count'] }} reseñas)</span>
                            </div>

                            <!-- Bio snippet -->
                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed mb-4">
                                {{ $provider->bio ?? 'Proveedor verificado listo para brindar la mejor atención a tu mascota.' }}
                            </p>
                        </div>

                        <!-- Footer details: location & price -->
                        <div class="border-t border-gray-50 pt-4 mt-2">
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-4 font-semibold">
                                <span class="flex items-center gap-1">
                                    📍 {{ $pData['district'] }}
                                </span>
                                @if($pData['price'])
                                    <span class="text-gray-900 font-bold bg-green-50 text-green-700 px-2 py-1 rounded-lg border border-green-100">
                                        Desde S/ {{ number_format($pData['price'], 2) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Action button solid -->
                            <a href="{{ $provider->profileUrl() }}" class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                Ver Perfil y Reservar
                            </a>
                        </div>

                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-400 italic">
                        No se encontraron proveedores registrados. Ejecuta los seeders.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- SECCIÓN: ¿Cómo funciona? -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-primary-600 font-extrabold tracking-wide uppercase text-sm">Simplicidad</span>
                <h2 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    ¿Cómo funciona TodoPeludos?
                </h2>
                <p class="mt-4 text-base text-gray-500 max-w-2xl mx-auto">
                    Consigue al cuidador ideal en solo 3 simples pasos rápidos.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                
                <!-- Paso 1 -->
                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 text-center space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-100 text-primary-700 font-extrabold text-xl flex items-center justify-center mx-auto shadow-sm">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Busca el Servicio</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Filtra según el tipo de servicio que requieres, tu distrito en Lima, y consulta reseñas reales de otros dueños.
                    </p>
                </div>

                <!-- Paso 2 -->
                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 text-center space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-100 text-primary-700 font-extrabold text-xl flex items-center justify-center mx-auto shadow-sm">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Agenda tu Cita</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Selecciona el día, hora y detalla las necesidades de tu mascota. El proveedor confirmará la disponibilidad.
                    </p>
                </div>

                <!-- Paso 3 -->
                <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 text-center space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-100 text-primary-700 font-extrabold text-xl flex items-center justify-center mx-auto shadow-sm">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Contacto Directo</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Accede al chat directo por WhatsApp con el profesional para coordinar los detalles finales con total seguridad.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- CTA Tarjeta Premium Centrada y Expandida -->
<div class="bg-white py-16 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-primary-600 via-primary-600 to-indigo-700 rounded-3xl shadow-xl overflow-hidden">
            <div class="max-w-4xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    <span class="block">¿Ofreces servicios para mascotas?</span>
                    <span class="block text-primary-100 text-2xl mt-2 font-medium">Únete a la mayor red de profesionales del Perú.</span>
                </h2>
                <p class="mt-4 text-base leading-relaxed text-primary-100 max-w-2xl mx-auto">
                    Llega a miles de dueños que buscan paseos, hospedajes o consultas veterinarias. Regístrate gratis hoy mismo y haz crecer tu agenda.
                </p>
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 sm:px-12 py-4 border border-transparent text-base sm:text-lg font-black rounded-2xl text-primary-700 bg-white hover:bg-primary-50 hover:text-primary-800 transition shadow-xl transform hover:scale-105 duration-200 text-center max-w-full">
                        Registrarme como Proveedor  
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
