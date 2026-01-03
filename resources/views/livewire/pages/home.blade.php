<div class="relative bg-gray-50">
    
    <!-- Hero Section con Buscador -->
    <div class="relative bg-white overflow-hidden">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Mascota feliz">
            <div class="absolute inset-0 bg-gray-900 bg-opacity-60"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl text-center shadow-sm">
                Cuidamos a tu engreído como tú 
                <span class="block text-primary-400">lo harías</span>
            </h1>
            <p class="mt-6 text-xl text-gray-100 max-w-3xl mx-auto text-center font-medium shadow-sm">
                Conecta con veterinarios, paseadores y hoteles cerca de ti. La comunidad más confiable de amantes de mascotas en Perú.
            </p>

            <!-- Buscador Central -->
            <div class="mt-10 max-w-xl mx-auto bg-white rounded-2xl shadow-2xl p-2 transform transition-all hover:scale-[1.01]">
                <form action="{{ route('search') }}" method="GET" class="flex items-center">
                    <div class="flex-grow relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="q" class="block w-full pl-10 pr-3 py-4 border-none rounded-l-xl focus:ring-0 text-gray-900 placeholder-gray-500 sm:text-lg" placeholder="¿Qué buscas? Ej: Veterinario en Miraflores...">
                    </div>
                    <button type="submit" class="bg-primary-600 rounded-xl text-white px-8 py-4 font-bold text-lg hover:bg-primary-700 focus:outline-none transition shadow-md flex items-center">
                        Buscar
                    </button>
                </form>
            </div>
            
            <!-- Quick Links -->
            <div class="mt-6 flex justify-center space-x-4 text-sm font-medium text-white opacity-90">
                <span>Buscas populares:</span>
                <a href="{{ route('search', ['role' => 'veterinarian']) }}" class="hover:text-primary-300 underline underline-offset-2">Veterinarios</a>
                <a href="{{ route('search', ['role' => 'walker']) }}" class="hover:text-primary-300 underline underline-offset-2">Paseadores</a>
                <a href="{{ route('search', ['role' => 'hotel']) }}" class="hover:text-primary-300 underline underline-offset-2">Hoteles</a>
            </div>
        </div>
    </div>

    <!-- Servicios Destacados -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-primary-600 font-semibold tracking-wide uppercase text-sm">Nuestros Servicios</span>
                <h2 class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Todo lo que tu mascota necesita en un solo lugar
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Card Veterinarios -->
                <a href="{{ route('search', ['role' => 'veterinarian']) }}" class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-teal-50 rounded-full opacity-50 z-0"></div>
                    <div class="relative z-10">
                        <span class="inline-flex items-center justify-center p-3 bg-teal-100 text-teal-600 rounded-xl mb-6 shadow-sm group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-teal-600 transition-colors">Veterinarios</h3>
                        <p class="text-gray-500 leading-relaxed">
                            Especialistas calificados cerca de ti. Agenda consultas presenciales o a domicilio con total confianza.
                        </p>
                    </div>
                </a>

                <!-- Card Paseadores -->
                <a href="{{ route('search', ['role' => 'walker']) }}" class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-orange-50 rounded-full opacity-50 z-0"></div>
                    <div class="relative z-10">
                        <span class="inline-flex items-center justify-center p-3 bg-orange-100 text-orange-600 rounded-xl mb-6 shadow-sm group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors">Paseadores</h3>
                        <p class="text-gray-500 leading-relaxed">
                            Paseos seguros y divertidos. Tracking GPS en tiempo real para que sepas siempre dónde está tu amigo.
                        </p>
                    </div>
                </a>

                <!-- Card Hoteles -->
                <a href="{{ route('search', ['role' => 'hotel']) }}" class="group relative bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 z-0"></div>
                    <div class="relative z-10">
                        <span class="inline-flex items-center justify-center p-3 bg-indigo-100 text-indigo-600 rounded-xl mb-6 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </span>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors">Hoteles y Hospedaje</h3>
                        <p class="text-gray-500 leading-relaxed">
                            Un segundo hogar cuando tú no estás. Hopsedajes libre de jaulas y con amor de familia.
                        </p>
                    </div>
                </a>
            </div>

            <!-- Botón Ver Todo -->
            <div class="mt-12 text-center">
                <a href="{{ route('search') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 transition">
                    Ver todos los servicios
                    <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Sección de Confianza -->
    <div class="bg-gray-50 py-16 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">¿Por qué elegir Mascotas<span class="text-primary-600">.pe</span>?</h2>
            </div>
            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-3">
                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100 text-green-600 mx-auto mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Perfiles Verificados</h3>
                    <p class="mt-2 text-gray-500">Revisamos la identidad y antecedentes de cada proveedor para tu tranquilidad.</p>
                </div>
                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 text-purple-600 mx-auto mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Comunidad Activa</h3>
                    <p class="mt-2 text-gray-500">Lee reseñas reales de otros dueños de mascotas y comparte tu experiencia.</p>
                </div>
                <div class="text-center">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600 mx-auto mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Reserva Fácil</h3>
                    <p class="mt-2 text-gray-500">Contacta y reserva servicios en segundos desde nuestra plataforma.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Final -->
    <div class="bg-primary-700">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">¿Ofreces servicios para mascotas?</span>
                <span class="block text-primary-200 text-2xl mt-2 font-medium">Únete a nuestra red de profesionales.</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-primary-100">
                Llega a miles de dueños de mascotas que buscan tus servicios. Regístrate gratis y empieza a crecer.
            </p>
            <a href="{{ route('register') }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary-600 bg-white hover:bg-primary-50 sm:w-auto transform hover:scale-105 transition shadow-lg">
                Registrarme como Proveedor
            </a>
        </div>
    </div>
</div>
