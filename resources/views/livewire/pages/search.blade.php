<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header del Buscador -->
        <div class="bg-white rounded-lg shadow mb-8 p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Encuentra el mejor servicio para tu mascota</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Tipo de Servicio -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">¿Qué buscas?</label>
                    <select wire:model.live="serviceType" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="veterinarian">Veterinarios</option>
                        <option value="walker">Paseadores</option>
                    </select>
                </div>

                <!-- Departamento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <select wire:model.live="department_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Todos</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Provincia -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provincia</label>
                    <select wire:model.live="province_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" {{ empty($provinces) ? 'disabled' : '' }}>
                        <option value="">Todas</option>
                        @foreach($provinces as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Distrito -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Distrito</label>
                    <select wire:model.live="district_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" {{ empty($districts) ? 'disabled' : '' }}>
                        <option value="">Todos</option>
                        @foreach($districts as $dist)
                            <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Resultados -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($results as $result)
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <img class="h-12 w-12 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ $result->user->name }}&background=0ea5e9&color=fff" alt="">
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $result->user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $serviceType === 'veterinarian' ? 'Veterinario Certificado' : 'Paseador de Perros' }}</p>
                            </div>
                        </div>

                        <div class="text-sm text-gray-600 mb-4">
                            <p class="flex items-center">
                                <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $result->district->name ?? 'Distrito no especificado' }}, {{ $result->district->province->name ?? '' }}
                            </p>
                        </div>

                        @if($serviceType === 'veterinarian')
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $result->bio }}</p>
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                @if($result->allows_home_visits)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Va a Domicilio</span>
                                @endif
                                <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">{{ $result->license_number }}</span>
                            </div>
                        @else
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $result->experience }}</p>
                            <p class="font-bold text-gray-900">S/ {{ number_format($result->hourly_rate, 2) }} <span class="font-normal text-gray-500">/ hora</span></p>
                        @endif

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('profile.show', $result->user->id) }}" class="block w-full text-center bg-white border border-primary-500 text-primary-600 py-2 rounded-md font-medium hover:bg-primary-50 transition">
                                Ver Perfil
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron resultados</h3>
                    <p class="mt-1 text-sm text-gray-500">Intenta ajustar los filtros de búsqueda.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $results->links() }}
        </div>
    </div>
</div>
