<div class="py-12 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 px-4 sm:px-0 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Mis Favoritos</h2>
                <p class="text-gray-500 text-sm mt-1">Guarda a tus profesionales de confianza para contactarlos rápidamente.</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 mx-4 sm:mx-0 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm" role="alert">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="block sm:inline font-medium">{{ session('message') }}</span>
            </div>
        @endif

        @if($favorites->isEmpty())
            <div class="mx-4 sm:mx-0 text-center py-16 bg-white rounded-3xl shadow-sm border-2 border-dashed border-gray-200">
                <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-10 w-10 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Aún no tienes favoritos</h3>
                <p class="mt-2 text-sm text-gray-500 max-w-xs mx-auto">Explora nuestros servicios y guarda a los mejores profesionales aquí.</p>
                <div class="mt-6">
                    <a href="{{ route('search') }}" class="text-primary-600 hover:text-primary-700 font-bold text-sm underline decoration-2 decoration-primary-200 hover:decoration-primary-500 transition-all">
                        Buscar Profesionales
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
                @foreach($favorites as $provider)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative hover:shadow-xl hover:border-red-100 transition-all group duration-300">
                        
                        <div class="flex items-center mb-4">
                            <img class="h-14 w-14 rounded-full object-cover border-2 border-white shadow-sm" src="{{ $provider->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($provider->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($provider->name) . '&background=random' }}" alt="{{ $provider->name }}">
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $provider->name }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    @if($provider->isVeterinarian()) Veterinario
                                    @elseif($provider->isWalker()) Paseador
                                    @elseif($provider->isGroomer()) Estilista
                                    @else Profesional
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-6">
                            <!-- Aquí podríamos poner info extra como ubicación o calificación si la tuviéramos a mano -->
                            <p class="text-sm text-gray-600 line-clamp-2">
                                {{-- $provider->about_me --}}
                                Profesional verificado y listo para ayudar a tus mascotas.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('profile.show', $provider->id) }}" class="flex-1 bg-primary-50 hover:bg-primary-100 text-primary-700 font-bold py-2 rounded-xl text-sm text-center transition">
                                Ver Perfil
                            </a>
                            <button wire:click="removeFavorite({{ $provider->id }})" class="bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 p-2 rounded-xl transition" title="Eliminar de favoritos">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
