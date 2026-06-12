<div class="relative bg-white overflow-hidden font-sans">
    
    <!-- Hero Section con Estructura de Dos Columnas y Fondo Suave -->
    <div class="relative bg-gray-50/50 pt-16 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Accent circles in background -->
        <div class="absolute top-0 right-0 -mt-16 -mr-16 w-96 h-96 rounded-full bg-primary-100/30 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-96 h-96 rounded-full bg-indigo-100/20 blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto lg:grid lg:grid-cols-12 lg:gap-8 items-center relative">
            
            <!-- Columna Izquierda: Mensajes y Buscador -->
            <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left space-y-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-primary-100 text-primary-700 tracking-wide uppercase">
                    🐾 Tu Hogar Seguro Para Mascotas
                </span>
                
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
                            <input type="text" name="q" class="w-full border-none focus:outline-none focus:ring-0 text-gray-800 placeholder-gray-400 text-sm sm:text-base ml-2 bg-transparent" placeholder="¿Qué buscas? Ej: Veterinario en Miraflores...">
                        </div>
                        <button type="submit" class="bg-primary-600 text-white rounded-full px-6 sm:px-8 py-3 font-bold text-sm sm:text-base hover:bg-primary-700 focus:outline-none transition-colors shadow-md shrink-0">
                            Buscar
                        </button>
                    </form>
                </div>
                
                <!-- Proof points -->
                <div class="pt-4 flex flex-wrap gap-4 text-xs font-bold text-gray-500 justify-center lg:justify-start">
                    <span class="flex items-center gap-1">
                        <span class="text-yellow-400 text-base">★</span> 4.9/5 Calificación de Clientes
                    </span>
                    <span class="text-gray-300">|</span>
                    <span class="flex items-center gap-1">
                        <span class="text-green-500 text-base">✓</span> Proveedores 100% Verificados
                    </span>
                </div>
            </div>

            <!-- Columna Derecha: Imagen Ilustrativa con Efecto Premium -->
            <div class="mt-12 lg:mt-0 lg:col-span-6 relative">
                <div class="relative mx-auto w-full max-w-md lg:max-w-lg">
                    <!-- Background blobs -->
                    <div class="absolute top-4 left-4 w-72 h-72 bg-yellow-200/50 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
                    <div class="absolute -bottom-8 -right-8 w-72 h-72 bg-primary-200/40 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>

                    <!-- Main Image Container -->
                    <div class="relative bg-white rounded-3xl p-3 shadow-2xl border border-gray-100 rotate-1 max-w-md mx-auto">
                        <img class="w-full h-80 object-cover rounded-2xl" src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Mascotas y Cuidado">
                        
                        <!-- Floating Card Badge -->
                        <div class="absolute -bottom-5 -left-5 bg-white p-4 rounded-2xl shadow-xl border border-gray-100 flex items-center gap-3">
                            <span class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-xl bg-green-50 text-green-600 font-bold text-lg">
                                🐶
                            </span>
                            <div>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Servicio Seguro</p>
                                <p class="text-sm font-extrabold text-gray-800">Cuidado Certificado</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Categorías de Servicios Estandarizadas -->
    <div class="py-16 bg-white">
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
                    ['type' => 'veterinarian', 'label' => 'Veterinarios 🩺',    'desc' => 'Consultas presenciales o a domicilio con especialistas calificados.',        'color' => 'teal',   'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
                    ['type' => 'walker',       'label' => 'Paseadores 🚶',       'desc' => 'Paseos seguros con profesionales certificados cerca de tu zona.',             'color' => 'orange', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                    ['type' => 'groomer',      'label' => 'Estilistas ✂️',        'desc' => 'Baño, corte y estética para que tu mascota siempre luzca perfecta.',          'color' => 'pink',   'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                    ['type' => 'hotel',        'label' => 'Hoteles 🏠',           'desc' => 'Hospedaje con amor familiar cuando tú no puedes estar.',                       'color' => 'indigo', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['type' => 'trainer',      'label' => 'Adiestradores 🦴',     'desc' => 'Educación canina con métodos positivos. A domicilio o en su establecimiento.', 'color' => 'yellow', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                    ['type' => 'pet_sitter',   'label' => 'Cuidadores 🛋️',        'desc' => 'Cuidan a tu mascota en su propio hogar con toda la atención que merece.',      'color' => 'green',  'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                ];
            @endphp

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($services as $s)
                <a href="{{ route('search', ['serviceType' => $s['type']]) }}" class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 p-7 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <span class="inline-flex items-center justify-center p-3 bg-gray-50 text-gray-700 rounded-xl mb-5 border border-gray-100 shadow-sm group-hover:bg-primary-600 group-hover:text-white group-hover:border-primary-600 transition-all duration-300">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}" />
                                </svg>
                            </span>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">{{ $s['label'] }}</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">{{ $s['desc'] }}</p>
                        </div>
                        <div class="mt-4 text-xs font-bold text-primary-600 group-hover:underline flex items-center gap-1">
                            Explorar proveedores <span>➔</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <!-- Botón Ver Todo Sólido -->
            <div class="mt-12 text-center">
                <a href="{{ route('search') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-primary-600 hover:bg-primary-700 transition shadow-md">
                    Explorar todos los proveedores
                    <svg class="ml-2 -mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- SECCIÓN: Proveedores Destacados -->
    <div class="py-16 bg-gray-50 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-primary-600 font-extrabold tracking-wide uppercase text-sm">Destacados</span>
                <h2 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Nuestros mejores cuidadores
                </h2>
                <p class="mt-4 text-base text-gray-500 max-w-2xl mx-auto">
                    Reserva con profesionales calificados y valorados por la comunidad de TodoPeludos.com.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($featuredProviders as $provider)
                    @php
                        $pData = $this->getProviderData($provider);
                    @endphp
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative group">
                        
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
                            <a href="{{ route('profile.show', $provider->id) }}" class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
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

    <!-- Bloque de Innovación IA Gemini -->
    <div class="bg-gray-50 py-16 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-center">
                
                <!-- Columna Izquierda: Mensajes -->
                <div class="lg:col-span-7 space-y-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 uppercase">
                        🤖 Tecnología IA Gemini
                    </span>
                    <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                        Salud inteligente para tu mascota
                    </h2>
                    <p class="text-gray-500 text-base leading-relaxed">
                        Nuestra plataforma integra modelos de visión y lenguaje de **Google Gemini** para ofrecerte pre-diagnósticos de salud rápidos y recomendaciones a tu medida.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3 bg-white p-4 rounded-xl border border-gray-200/60 shadow-sm">
                            <span class="text-indigo-600 text-xl font-bold">✓</span>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Análisis de Imágenes</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Detección temprana de anomalías en heces, piel y orina.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 bg-white p-4 rounded-xl border border-gray-200/60 shadow-sm">
                            <span class="text-indigo-600 text-xl font-bold">✓</span>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Planes de Cuidado</h4>
                                <p class="text-xs text-gray-400 mt-0.5">Algoritmos inteligentes que trazan dietas y entrenamientos.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Registro Sólido -->
                    <div class="pt-2">
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md">
                            Registrarme para Probar IA Gratis
                        </a>
                    </div>
                </div>

                <!-- Columna Derecha: Ilustración IA -->
                <div class="mt-12 lg:mt-0 lg:col-span-5 relative">
                    <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-100 max-w-sm mx-auto text-center space-y-6">
                        <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 font-extrabold text-2xl flex items-center justify-center mx-auto shadow-inner">
                            🧠
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Gemini Care Assistant</h3>
                            <p class="text-xs text-gray-400 mt-1">Inteligencia Artificial de Google a tu servicio</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-left text-xs font-semibold text-indigo-800 leading-relaxed border border-indigo-50">
                            "Basado en la foto cargada, tu perro muestra un pelaje saludable y no hay signos de anomalías dérmicas. Mantén la dieta recomendada."
                        </div>
                        <div class="text-[10px] text-gray-300 font-bold uppercase">
                            TodoPeludos.com AI Module
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- CTA Final con Color Sólido -->
    <div class="bg-primary-600">
        <div class="max-w-4xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">¿Ofreces servicios para mascotas?</span>
                <span class="block text-primary-100 text-2xl mt-2 font-medium">Únete a la mayor red de profesionales del Perú.</span>
            </h2>
            <p class="mt-4 text-base leading-relaxed text-primary-100 max-w-2xl mx-auto">
                Llega a miles de dueños que buscan paseos, hospedajes o consultas veterinarias. Regístrate gratis hoy mismo y haz crecer tu agenda.
            </p>
            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3.5 border border-transparent text-sm font-bold rounded-xl text-primary-700 bg-white hover:bg-primary-50 transition shadow-lg transform hover:scale-105">
                    Registrarme como Proveedor
                </a>
            </div>
        </div>
    </div>

</div>
