@push('styles')
<style>
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes scale-in {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.animate-fade-in {
    animation: fade-in 0.2s ease-out;
}
.animate-scale-in {
    animation: scale-in 0.2s ease-out;
}
</style>
@endpush

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header mejorado -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                        Historial de Salud y Cuidado
                    </h1>
                    <p class="text-gray-600">Todos tus análisis y planes en un solo lugar</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('dashboard.health.analyze') }}" class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-3 rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Análisis
                    </a>
                    <a href="{{ route('dashboard.care.plan') }}" class="flex items-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl hover:from-purple-700 hover:to-purple-800 transition shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Plan
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros mejorados -->
        <div class="mb-8 bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center gap-3 mb-4">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <h2 class="text-lg font-semibold text-gray-900">Filtrar por tipo</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <button 
                    wire:click="filterBy('all')" 
                    class="px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 {{ $filterType === 'all' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                >
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        Todos
                    </span>
                </button>
                <button 
                    wire:click="filterBy('feces')" 
                    class="px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 {{ $filterType === 'feces' ? 'bg-yellow-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                >
                    <span class="flex items-center gap-2">
                        💩 Heces
                    </span>
                </button>
                <button 
                    wire:click="filterBy('urine')" 
                    class="px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 {{ $filterType === 'urine' ? 'bg-blue-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                >
                    <span class="flex items-center gap-2">
                        💧 Orina
                    </span>
                </button>
                <button 
                    wire:click="filterBy('skin')" 
                    class="px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 {{ $filterType === 'skin' ? 'bg-pink-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                >
                    <span class="flex items-center gap-2">
                        🔬 Piel/Lengua
                    </span>
                </button>
                <button 
                    wire:click="filterBy('care_plan')" 
                    class="px-6 py-3 rounded-xl font-medium transition-all transform hover:scale-105 {{ $filterType === 'care_plan' ? 'bg-purple-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                >
                    <span class="flex items-center gap-2">
                        📋 Planes
                    </span>
                </button>
            </div>
        </div>

        <!-- Estado vacío mejorado -->
        @if ($items->isEmpty())
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <div class="inline-block bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full p-6 mb-6">
                    <svg class="w-16 h-16 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No hay registros aún</h3>
                <p class="text-gray-600 mb-8">Comienza realizando un análisis de salud o generando un plan de cuidado para tu mascota</p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('dashboard.health.analyze') }}" class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-8 py-3 rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition font-semibold shadow-lg">
                        Hacer Análisis
                    </a>
                    <a href="{{ route('dashboard.care.plan') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-3 rounded-xl hover:from-purple-700 hover:to-purple-800 transition font-semibold shadow-lg">
                        Crear Plan
                    </a>
                </div>
            </div>
        @else
            <!-- Lista de items mejorada -->
            <div class="grid grid-cols-1 gap-6">
                @foreach ($items as $item)
                    @if ($item['type'] === 'health_analysis')
                        {{-- Card de Análisis de Salud Mejorada --}}
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all overflow-hidden group">
                            <div class="flex flex-col md:flex-row">
                                <!-- Imagen -->
                                <div class="md:w-48 h-48 md:h-auto relative overflow-hidden bg-gray-100">
                                    <img src="{{ Storage::url($item['item']->image_path) }}" alt="Análisis" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                    <div class="absolute top-3 left-3">
                                        @php
                                            $bgColor = match($item['item']->analysis_type) {
                                                'feces' => 'bg-yellow-500',
                                                'urine' => 'bg-blue-500',
                                                'skin' => 'bg-pink-500',
                                                default => 'bg-gray-500'
                                            };
                                            $icon = match($item['item']->analysis_type) {
                                                'feces' => '💩',
                                                'urine' => '💧',
                                                'skin' => '🔬',
                                                default => '📋'
                                            };
                                        @endphp
                                        <span class="{{ $bgColor }} text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                            {{ $icon }} {{ $item['item']->analysisTypeName }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Contenido -->
                                <div class="flex-1 p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                @if ($item['item']->pet)
                                                    <h3 class="text-2xl font-bold text-gray-900">{{ $item['item']->pet->name }}</h3>
                                                @endif
                                                @if ($item['item']->requires_attention)
                                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1">
                                                        ⚠️ Requiere atención
                                                    </span>
                                                @else
                                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1">
                                                        ✅ Normal
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $item['item']->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Hallazgos principales:</h4>
                                        <ul class="space-y-1">
                                            @foreach (array_slice($item['item']->findings, 0, 2) as $finding)
                                                <li class="flex items-start text-sm text-gray-600">
                                                    <svg class="w-4 h-4 text-indigo-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ Str::limit($finding, 100) }}
                                                </li>
                                            @endforeach
                                            @if (count($item['item']->findings) > 2)
                                                <li class="text-sm text-indigo-600 font-medium">+{{ count($item['item']->findings) - 2 }} hallazgos más</li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <span class="text-xs text-gray-500 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Confianza: {{ round($item['item']->confidence_score * 100) }}%
                                        </span>
                                        <button 
                                            wire:click="viewDetails({{ $item['id'] }}, 'health_analysis')"
                                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition font-medium text-sm shadow-md hover:shadow-lg"
                                        >
                                            Ver detalles →
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Card de Plan de Cuidado Mejorada --}}
                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl shadow-lg hover:shadow-2xl transition-all overflow-hidden border-2 border-purple-200 group">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="bg-purple-600 text-white p-2 rounded-xl">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-bold text-purple-900">{{ $item['item']->petName }}</h3>
                                                <p class="text-sm text-purple-600 font-medium">Plan de Cuidado Personalizado</p>
                                            </div>
                                        </div>
                                        <p class="text-sm text-purple-700 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $item['item']->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <div class="bg-white rounded-xl p-4 shadow-sm">
                                        <p class="text-xs text-gray-600 mb-1">Raza</p>
                                        <p class="text-sm font-bold text-purple-900">{{ Str::limit($item['item']->petBreed, 15) }}</p>
                                    </div>
                                    <div class="bg-white rounded-xl p-4 shadow-sm">
                                        <p class="text-xs text-gray-600 mb-1">Peso</p>
                                        <p class="text-sm font-bold text-purple-900">{{ $item['item']->pet_data['weight'] ?? 'N/A' }} kg</p>
                                    </div>
                                    <div class="bg-white rounded-xl p-4 shadow-sm">
                                        <p class="text-xs text-gray-600 mb-1">Método</p>
                                        <p class="text-sm font-bold text-purple-900">{{ $item['item']->generation_method === 'photo' ? '📸 Foto' : '🐾 Perfil' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-purple-200">
                                    <div class="flex gap-2">
                                        <span class="bg-purple-200 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">Nutrición</span>
                                        <span class="bg-purple-200 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">Ejercicio</span>
                                        <span class="bg-purple-200 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold">+4 más</span>
                                    </div>
                                    <button 
                                        wire:click="viewDetails({{ $item['id'] }}, 'care_plan')"
                                        class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-lg hover:from-purple-700 hover:to-pink-700 transition font-medium text-sm shadow-md hover:shadow-lg"
                                    >
                                        Ver plan →
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- Modal mejorado --}}
    @if ($showModal && $selectedItem)
        <div class="fixed z-50 inset-0 overflow-y-auto animate-fade-in">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity backdrop-blur-sm" wire:click="closeModal"></div>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full animate-scale-in">
                    @if ($selectedItem->type === 'health_analysis')
                        {{-- Modal Análisis --}}
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-2xl font-bold mb-1">{{ $selectedItem->pet->name ?? 'Mascota' }}</h3>
                                    <p class="text-indigo-100">{{ $selectedItem->analysisTypeName }}</p>
                                </div>
                                <button wire:click="closeModal" class="bg-white bg-opacity-20 hover:bg-opacity-30 p-2 rounded-lg transition">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-8">
                            <img src="{{ Storage::url($selectedItem->image_path) }}" alt="Análisis" class="w-full rounded-xl mb-6 shadow-lg">
                            
                            @if ($selectedItem->requires_attention)
                                <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-r-xl p-5 mb-6">
                                    <p class="text-yellow-900 font-semibold">⚠️ Este análisis requiere atención veterinaria</p>
                                </div>
                            @else
                                <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-5 mb-6">
                                    <p class="text-green-900 font-semibold">✅ Todo parece estar en orden</p>
                                </div>
                            @endif

                            <div class="space-y-6">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-3">Hallazgos:</h4>
                                    <ul class="space-y-2">
                                        @foreach ($selectedItem->findings as $finding)
                                            <li class="flex items-start bg-gray-50 rounded-lg p-3">
                                                <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-gray-700">{{ $finding }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                @if ($selectedItem->recommendations)
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900 mb-3">Recomendaciones:</h4>
                                        <div class="bg-indigo-50 rounded-xl p-5 border-2 border-indigo-200">
                                            <p class="text-gray-800">{{ $selectedItem->recommendations }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Modal Plan --}}
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-8 py-6 text-white">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-2xl font-bold mb-1">{{ $selectedItem->petName }}</h3>
                                    <p class="text-purple-100">Plan de Cuidado Personalizado</p>
                                </div>
                                <button wire:click="closeModal" class="bg-white bg-opacity-20 hover:bg-opacity-30 p-2 rounded-lg transition">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 mb-6 border-2 border-purple-200">
                                <p class="text-center text-purple-700">
                                    <svg class="w-8 h-8 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Para ver el plan completo con todas las secciones, visita el
                                    <a href="{{ route('dashboard.care.plan') }}" class="font-bold text-purple-900 hover:underline">Generador de Planes</a>
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="bg-gray-50 px-8 py-4 flex justify-end">
                        <button wire:click="closeModal" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition font-semibold shadow-lg">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
