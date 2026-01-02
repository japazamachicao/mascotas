<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Welcome Section -->
        <div class="mb-8 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Hola, {{ Auth::user()->name }} 👋
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Bienvenido a tu panel de control. Aquí puedes gestionar a tus engreídos.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('dashboard.pet.create') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Agregar Mascota
                </a>
            </div>
        </div>

        <!-- Pets Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($pets as $pet)
                <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <img class="h-16 w-16 rounded-full object-cover" 
                                     src="{{ $pet->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($pet->profile_photo_path) : 'https://ui-avatars.com/api/?name='.$pet->name.'&background=random' }}" 
                                     alt="{{ $pet->name }}">
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ $pet->species }} ({{ $pet->breed }})
                                    </dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900">
                                            {{ $pet->name }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    {{-- Actions --}}
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm flex justify-between">
                            <a href="{{ route('pet.profile', $pet->uuid) }}" target="_blank" class="font-medium text-primary-600 hover:text-primary-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Ver QR Público
                            </a>
                            <a href="#" class="font-medium text-gray-500 hover:text-gray-700">
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-lg border-2 border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes mascotas registradas</h3>
                    <p class="mt-1 text-sm text-gray-500">Agrega a tu primer engreído para generar su QR.</p>
                    <div class="mt-6">
                        <a href="{{ route('dashboard.pet.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Agregar Mascota
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
