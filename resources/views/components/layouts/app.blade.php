<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN para MVP rápido) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9', // Sky blue
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        secondary: {
                            500: '#64748b',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
    
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col" x-data="{ mobileMenuOpen: false }">
    
    <!-- Navbar -->
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm backdrop-blur-md bg-opacity-90">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Desktop Nav -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer" onclick="window.location.href='/'">
                        <img class="h-8 w-auto" src="https://cdn-icons-png.flaticon.com/512/616/616554.png" alt="Logo">
                        <span class="font-bold text-xl tracking-tight text-gray-900">Mascotas<span class="text-primary-600">.pe</span></span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('home') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('home') ? 'border-primary-500 text-gray-900' : '' }}">Inicio</a>
                        <a href="{{ route('search') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">Buscar Servicios</a>

                        @auth
                            @if(Auth::user()->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']))
                                <!-- Links para Proveedores -->
                                <a href="{{ route('dashboard.provider') }}" class="border-transparent text-gray-500 hover:border-primary-500 hover:text-primary-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    Mi Agenda
                                </a>
                                <a href="#" class="border-transparent text-gray-400 hover:text-gray-500 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 cursor-not-allowed" title="Próximamente">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Mis Finanzas
                                </a>
                            @else
                                <!-- Links para Dueños (Clientes) -->
                                <a href="{{ route('dashboard') }}" class="border-transparent text-gray-500 hover:border-primary-500 hover:text-primary-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'border-primary-500 text-gray-900' : '' }}">
                                    Mis Mascotas
                                </a>
                                <a href="{{ route('dashboard.addresses') }}" class="border-transparent text-gray-500 hover:border-primary-500 hover:text-primary-600 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('dashboard.addresses') ? 'border-primary-500 text-gray-900' : '' }}">
                                    Mis Direcciones
                                </a>
                                <a href="#" class="border-transparent text-gray-400 hover:text-gray-500 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 cursor-not-allowed" title="Próximamente">
                                    Favoritos
                                </a>
                                <a href="#" class="border-transparent text-gray-400 hover:text-gray-500 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 cursor-not-allowed" title="Próximamente">
                                    Historial
                                </a>
                            @endif
                        @else
                            <!-- Links para Visitantes -->
                            <a href="{{ route('register') }}" class="border-transparent text-primary-600 hover:text-primary-700 font-bold inline-flex items-center px-1 pt-1 border-b-2 text-sm transition-colors duration-200">
                                ¿Eres Profesional? Únete
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right Side: User Menu & Mobile Button -->
                <div class="flex items-center space-x-4">
                    <!-- Desktop Auth -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
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
                                     class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                     style="display: none;">
                                    
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-xs text-gray-500">Conectado como</p>
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    </div>

                                    @if(Auth::user()->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']))
                                        <a href="{{ route('dashboard.provider') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Mi Panel</a>
                                        <a href="{{ route('profile.show', Auth::id()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Ver Mi Perfil Público</a>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Mis Mascotas</a>
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
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Abrir menú</span>
                            <!-- Icono menú -->
                            <svg x-show="!mobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <!-- Icono cerrar -->
                            <svg x-show="mobileMenuOpen" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Alpine) -->
        <div class="sm:hidden" id="mobile-menu" x-show="mobileMenuOpen" x-collapse style="display: none;">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="bg-primary-50 border-primary-500 text-primary-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Inicio</a>
                <a href="{{ route('search') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Buscar Servicios</a>
                
                @auth
                    @if(Auth::user()->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']))
                        <a href="{{ route('dashboard.provider') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Mi Agenda</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Mis Mascotas</a>
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
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
            <div class="mt-8 md:mt-0 md:order-1">
                <p class="text-center text-sm text-gray-400">&copy; 2026 Mascotas.pe. Todos los derechos reservados. Desarrollado con ❤️ para las mascotas.</p>
            </div>
            <div class="flex justify-center space-x-6 md:order-2">
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Facebook</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/></svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Instagram</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465C9.673 2.013 10.03 2 12.315 2zm-2.008 3.326c-2.378 0-4.305 1.927-4.305 4.305s1.927 4.305 4.305 4.305 4.305-1.927 4.305-4.305-1.927-4.305-4.305-4.305zm0 2.155a2.15 2.15 0 110 4.3 2.15 2.15 0 010-4.3zm6.626-2.61a1.44 1.44 0 110 2.88 1.44 1.44 0 010-2.88z" clip-rule="evenodd"/></svg>
                </a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
