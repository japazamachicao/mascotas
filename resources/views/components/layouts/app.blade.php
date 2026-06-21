<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0ea5e9">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <title>{{ $title ?? config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
    
    <!-- Leaflet.js for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    @livewireStyles
    @stack('meta')

    <!-- Custom CSS Overrides for Premium Navigation & Spacing Resets -->
    <style>
        /* Pills Navigation Style */
        .nav-link {
            color: #4b5563 !important; /* text-gray-600 */
            font-size: 0.825rem !important; /* text-xs */
            font-weight: 600 !important; /* font-semibold */
            padding: 0.375rem 0.625rem !important; /* py-1.5 px-2.5 */
            border-radius: 0.5rem !important; /* rounded-lg */
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            display: inline-flex !important;
            align-items: center !important;
            border-bottom: none !important;
            margin-bottom: 0 !important;
            text-decoration: none !important;
            white-space: nowrap !important;
        }
        
        @media (min-width: 1280px) {
            .nav-link {
                font-size: 0.875rem !important; /* text-sm */
                padding: 0.45rem 0.875rem !important; /* py-2 px-3.5 */
            }
        }

        .nav-link:hover {
            color: #0284c7 !important; /* primary-600 */
            background-color: #f0f9ff !important; /* primary-50 */
        }

        .nav-link.active {
            color: #0284c7 !important; /* primary-600 */
            background-color: #e0f2fe !important; /* primary-100 */
        }
        
        /* Desktop vs Mobile Menu Controls */
        @media (min-width: 768px) {
            .desktop-nav-links {
                display: flex !important;
            }
            .desktop-auth-container {
                display: flex !important;
            }
            .mobile-menu-btn {
                display: none !important;
            }
        }
        
        @media (max-width: 767px) {
            .desktop-nav-links {
                display: none !important;
            }
            .desktop-auth-container {
                display: none !important;
            }
            .mobile-menu-btn {
                display: flex !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">
    
    <!-- Navbar -->
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm backdrop-blur-md bg-white/90">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Left: Logo -->
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" onclick="window.location.href='/'">
                    <div class="bg-primary-600 p-2 rounded-xl">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            <path d="M5 14a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            <path d="M19 14a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            <path d="M12 21a5 5 0 0 0 5-5H7a5 5 0 0 0 5 5Z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight">
                        <span class="text-primary-600">TodoPeludos</span>
                    </span>
                </div>

                <!-- Center: Desktop Nav Links Distributed -->
                <div class="desktop-nav-links hidden md:flex flex-1 justify-center px-4">
                    <div class="flex items-center space-x-1 lg:space-x-1.5 xl:space-x-3">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a>
                        <a href="{{ route('search') }}" class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}">Servicios</a>

                        @auth
                            @if(Auth::user()->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']))
                                <!-- Links para Proveedores -->
                                <a href="{{ route('dashboard.provider') }}" class="nav-link {{ request()->routeIs('dashboard.provider') ? 'active' : '' }}">
                                    <svg class="h-4 w-4 mr-1.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    Agenda
                                </a>
                                <a href="{{ route('dashboard.messages') }}" class="nav-link {{ request()->routeIs('dashboard.messages') ? 'active' : '' }}">
                                    <svg class="h-4 w-4 mr-1.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                    Mensajes
                                </a>
                                <a href="#" class="nav-link text-gray-400 cursor-not-allowed opacity-50" title="Próximamente">
                                    <svg class="h-4 w-4 mr-1.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Finanzas
                                </a>
                            @else
                                <!-- Links para Dueños (Clientes) -->
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    Mascotas
                                </a>
                                <a href="{{ route('dashboard.messages') }}" class="nav-link {{ request()->routeIs('dashboard.messages') ? 'active' : '' }}">
                                    Mensajes
                                </a>
                                <a href="{{ route('dashboard.appointments') }}" class="nav-link {{ request()->routeIs('dashboard.appointments') ? 'active' : '' }}">
                                    Citas
                                </a>
                                <a href="{{ route('dashboard.addresses') }}" class="nav-link {{ request()->routeIs('dashboard.addresses') ? 'active' : '' }}">
                                    Direcciones
                                </a>
                                <a href="{{ route('dashboard.favorites') }}" class="nav-link {{ request()->routeIs('dashboard.favorites') ? 'active' : '' }}">
                                    Favoritos
                                </a>
                            @endif
                        @else
                            <!-- Links para Visitantes -->
                            <a href="{{ route('register') }}" class="nav-link text-primary-600 font-extrabold hover:text-primary-700 bg-primary-50">
                                ¿Eres Profesional? Únete
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right Side: User Menu & Mobile Button -->
                <div class="flex items-center space-x-4">
                    @auth
                        <livewire:dashboard.notification-bell />
                    @endauth
                    
                    <!-- Desktop Auth -->
                    <div class="hidden md:flex md:items-center md:ml-6 desktop-auth-container">
                        @auth
                            <div class="relative ml-3" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open" type="button" class="group flex items-center gap-2 rounded-full bg-white p-1 pr-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition hover:bg-gray-50 border border-transparent hover:border-gray-200" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        <img class="h-8 w-8 rounded-full object-cover border border-gray-200" src="{{ Auth::user()->profile_photo_path ? \Illuminate\Support\Facades\Storage::url(Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0ea5e9&color=fff' }}" alt="">
                                        <span class="hidden md:block font-medium text-gray-700 group-hover:text-gray-900">{{ Auth::user()->name }}</span>
                                        <svg class="hidden md:block h-4 w-4 text-gray-400 group-hover:text-gray-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <div x-show="open" 
                                     @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none" 
                                     style="display: none;">
                                    
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-xs text-gray-400">Conectado como</p>
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    </div>

                                    @if(Auth::user()->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']))
                                        <a href="{{ route('dashboard.provider') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Mi Panel</a>
                                        <a href="{{ route('profile.show', Auth::id()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Ver Mi Perfil Público</a>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Mis Mascotas</a>
                                        <a href="{{ route('dashboard.appointments') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Mis Citas</a>
                                        <a href="{{ route('dashboard.addresses') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Mis Direcciones</a>
                                        <a href="{{ route('dashboard.favorites') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Mis Favoritos</a>
                                        <a href="{{ route('dashboard.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Mi Perfil</a>
                                    @endif

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Cerrar Sesión</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">Ingresar</a>
                            <a href="{{ route('register') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow-sm hover:shadow-md">Registrarme</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="-mr-2 flex items-center md:hidden mobile-menu-btn">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Abrir menú</span>
                            <!-- Icono menú -->
                            <svg x-show="!mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <!-- Icono cerrar -->
                            <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Alpine) -->
        <div class="md:hidden" id="mobile-menu" x-show="mobileMenuOpen" x-collapse style="display: none;">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="bg-primary-50 border-primary-500 text-primary-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Inicio</a>
                <a href="{{ route('search') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Buscar Servicios</a>
                
                @auth
                    @if(Auth::user()->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']))
                        <a href="{{ route('dashboard.provider') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Mi Agenda</a>
                        <a href="{{ route('dashboard.messages') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Mensajes</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Mis Mascotas</a>
                        <a href="{{ route('dashboard.messages') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Mensajes</a>
                        <a href="{{ route('dashboard.appointments') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Mis Citas</a>
                        <a href="{{ route('dashboard.addresses') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Mis Direcciones</a>
                        <a href="{{ route('dashboard.favorites') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Favoritos</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="border-transparent text-primary-600 hover:bg-gray-50 hover:border-primary-300 hover:text-primary-800 block pl-3 pr-4 py-2 border-l-4 text-base font-bold">¿Eres Profesional? Únete</a>
                @endauth
            </div>
            
            <div class="pt-4 pb-4 border-t border-gray-200">
                @auth
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_path ? \Illuminate\Support\Facades\Storage::url(Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0ea5e9&color=fff' }}" alt="">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        @if(Auth::user()->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']))
                             <a href="{{ route('dashboard.provider') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Mi Panel</a>
                             <a href="{{ route('profile.show', Auth::id()) }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Mi Perfil Público</a>
                        @else
                             <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Mis Mascotas</a>
                             <a href="{{ route('dashboard.profile') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">Mi Perfil</a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50">Cerrar Sesión</button>
                        </form>
                    </div>
                @else
                    <div class="mt-3 space-y-1 px-4">
                        <a href="{{ route('login') }}" class="block text-center w-full px-4 py-2 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Ingresar
                        </a>
                        <a href="{{ route('register') }}" class="block text-center w-full mt-3 px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                            Registrarme
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="bg-primary-600 p-2 rounded-xl">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                <path d="M5 14a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                <path d="M19 14a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                                <path d="M12 21a5 5 0 0 0 5-5H7a5 5 0 0 0 5 5Z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight">
                            <span class="text-primary-600">TodoPeludos</span>
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 max-w-sm">
                        La plataforma líder en el cuidado de mascotas en el Perú. Conectando a dueños con los mejores veterinarios, paseadores y cuidadores.
                    </p>
                </div>
                <!-- Col 2: Legal -->
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Legal</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-900 transition">Términos de Servicio</a>
                        </li>
                        <li>
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-900 transition">Política de Privacidad</a>
                        </li>
                        <li>
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-900 transition">Política de Cookies</a>
                        </li>
                    </ul>
                </div>
                <!-- Col 3: Soporte y Social -->
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Contacto y Ayuda</h3>
                    <ul class="mt-4 space-y-4">
                        <li>
                            <a href="#" class="text-sm text-gray-500 hover:text-gray-900 transition">Centro de Ayuda</a>
                        </li>
                        <li>
                            <a href="mailto:soporte@todopeludos.com" class="text-sm text-gray-500 hover:text-gray-900 transition">soporte@todopeludos.com</a>
                        </li>
                        <li class="flex space-x-4">
                            <a href="https://facebook.com/todopeludos" target="_blank" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Facebook</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                            </a>
                            <a href="https://instagram.com/todopeludos" target="_blank" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Instagram</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465C9.673 2.013 10.03 2 12.315 2zm-2.008 3.326c-2.378 0-4.305 1.927-4.305 4.305s1.927 4.305 4.305 4.305 4.305-1.927 4.305-4.305-1.927-4.305-4.305-4.305zm0 2.155a2.15 2.15 0 110 4.3 2.15 2.15 0 010-4.3zm6.626-2.61a1.44 1.44 0 110 2.88 1.44 1.44 0 010-2.88z" clip-rule="evenodd"/></svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8 flex items-center justify-between">
                <p class="text-xs text-gray-400">&copy; 2026 TodoPeludos. Todos los derechos reservados. Desarrollado con ❤️ para las mascotas.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('[PWA] Service Worker registered:', reg.scope))
                    .catch(err => console.warn('[PWA] SW registration failed:', err));
            });
        }
    </script>
</body>
</html>
