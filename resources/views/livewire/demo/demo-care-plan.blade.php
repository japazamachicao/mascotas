@push('styles')
<style>
@keyframes pulse-slow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
.animate-pulse-slow {
    animation: pulse-slow 2s ease-in-out infinite;
}
</style>
@endpush

<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-indigo-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header mejorado -->
        <div class="text-center mb-8">
            <div class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 rounded-full p-4 mb-4">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                Plan de Cuidado Personalizado 🐾 - DEMO GRATUITA
            </h1>
            <p class="text-gray-600 text-lg">Prueba el plan completo de nutrición, ejercicio, salud y más</p>
            @if(!$demoUsed)
                <div class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold mt-3">
                    ✨ Puedes usar esta demo UNA VEZ gratis
                </div>
            @endif
        </div>

        @if (!$carePlan)
            <div class="bg-white rounded-2xl shadow-xl p-8">
                @if ($error)
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-5 mb-6">
                        <div class="flex items-start">
                            <svg class="h-6 w-6 text-red-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-red-800 font-medium">{{ $error }}</p>
                        </div>
                    </div>
                @endif

                <!-- Disclaimer mejorado -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-l-4 border-purple-500 rounded-r-lg p-5 mb-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-purple-900 mb-1">Plan personalizado con IA</h3>
                            <p class="text-sm text-purple-700">
                                La IA detectará automáticamente la raza de tu mascota y generará un plan de cuidado completo y personalizado.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Subir Foto -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="bg-purple-100 text-purple-700 rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm font-bold">1</span>
                            Sube una foto de tu mascota
                        </label>
                        
                        @if ($photo)
                            <div class="relative group">
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-64 object-cover rounded-2xl shadow-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition rounded-2xl flex items-center justify-center">
                                    <button 
                                        type="button" 
                                        wire:click="$set('photo', null)"
                                        class="bg-red-500 text-white px-6 py-2 rounded-full opacity-0 group-hover:opacity-100 transition-all hover:bg-red-600 font-semibold"
                                    >
                                        Cambiar foto
                                    </button>
                                </div>
                            </div>
                        @else
                            <label class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-7">
                                    <svg class="w-16 h-16 mb-4 text-gray-400 group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mb-2 text-lg font-semibold text-gray-700 group-hover:text-purple-700">
                                        <span class="text-purple-600">Click para subir</span> o arrastra aquí
                                    </p>
                                    <p class="text-sm text-gray-500">PNG, JPG, GIF (máx. 10MB)</p>
                                </div>
                                <input id="file-upload-photo" wire:model="photo" type="file" class="hidden" accept="image/*">
                            </label>
                        @endif
                        @error('photo') <p class="text-red-500 text-sm mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p> @enderror
                    </div>

                    @if ($photo)
                        <button 
                            wire:click="generateFromPhoto"
                            wire:loading.attr="disabled"
                            class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-4 focus:ring-purple-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]"
                        >
                            <span wire:loading.remove wire:target="generateFromPhoto" class="flex items-center justify-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Generar Plan con IA 🔬
                            </span>
                            <span wire:loading wire:target="generateFromPhoto" class="flex items-center justify-center">
                                <svg class="animate-spin h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Analizando y generando... ⏳
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        @endif

        @if ($carePlan && $petData)
            <div class="space-y-6">
                <!-- Header del Plan Mejorado -->
                <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-indigo-600 rounded-2xl shadow-2xl p-8 text-white">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                        <div class="flex-1">
                            <h2 class="text-3xl font-bold mb-2">Plan de Cuidado para {{ $petData['name'] }}</h2>
                            <p class="text-purple-100 text-lg">{{ $petData['species'] }} • {{ $petData['breed'] }} • {{ round($petData['weight'], 1) }} kg</p>
                        </div>
                    </div>
                </div>

                <!-- 1. Nutrición -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                        <h3 class="text-2xl font-bold text-white flex items-center">
                            <span class="text-3xl mr-3">🍖</span> Nutrición
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border-2 border-blue-200">
                                <p class="text-sm text-blue-700 font-medium mb-1">Calorías Diarias</p>
                                <p class="text-3xl font-bold text-blue-900">{{ $carePlan['nutrition']['daily_calories'] }}</p>
                                <p class="text-xs text-blue-600">kcal/día</p>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border-2 border-green-200">
                                <p class="text-sm text-green-700 font-medium mb-1">Comidas al Día</p>
                                <p class="text-3xl font-bold text-green-900">{{ $carePlan['nutrition']['meals_per_day'] }}</p>
                                <p class="text-xs text-green-600">veces</p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border-2 border-purple-200">
                                <p class="text-sm text-purple-700 font-medium mb-1">Tipo de Alimento</p>
                                <p class="text-lg font-bold text-purple-900">{{ $carePlan['nutrition']['food_type'] }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-xl p-5">
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Horarios Sugeridos
                                </h4>
                                <ul class="space-y-2">
                                    @foreach ($carePlan['nutrition']['schedule'] as $time)
                                        <li class="flex items-center text-gray-700">
                                            <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3"></span>
                                            {{ $time }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="bg-red-50 rounded-xl p-5 border-2 border-red-200">
                                <h4 class="font-bold text-red-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Alimentos a Evitar
                                </h4>
                                <ul class="space-y-2">
                                    @foreach (array_slice($carePlan['nutrition']['avoid_foods'], 0, 5) as $food)
                                        <li class="flex items-center text-red-700">
                                            <span class="text-red-500 mr-2">✗</span>
                                            {{ $food }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Ejercicio -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
                        <h3 class="text-2xl font-bold text-white flex items-center">
                            <span class="text-3xl mr-3">🏃</span> Ejercicio
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-5 border-2 border-orange-200">
                                <p class="text-sm text-orange-700 font-medium mb-1">{{ $carePlan['exercise']['daily_walks'] ?? 'Actividad Diaria' }}</p>
                                <p class="text-2xl font-bold text-orange-900">{{ $carePlan['exercise']['total_daily'] ?? $carePlan['exercise']['duration'] ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-5 border-2 border-yellow-200">
                                <p class="text-sm text-yellow-700 font-medium mb-1">Intensidad</p>
                                <p class="text-2xl font-bold text-yellow-900 capitalize">{{ $carePlan['exercise']['intensity'] ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-5 border-2 border-red-200">
                                <p class="text-sm text-red-700 font-medium mb-1">{{ isset($carePlan['exercise']['duration_per_walk']) ? 'Por Sesión' : 'Frecuencia' }}</p>
                                <p class="text-2xl font-bold text-red-900">{{ $carePlan['exercise']['duration_per_walk'] ?? $carePlan['exercise']['frequency'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        @if (isset($carePlan['exercise']['recommended_activities']) && !empty($carePlan['exercise']['recommended_activities']))
                            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-5">
                                <h4 class="font-bold text-gray-900 mb-3">Actividades Recomendadas:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($carePlan['exercise']['recommended_activities'] as $activity)
                                        <span class="bg-orange-200 text-orange-900 px-4 py-2 rounded-full text-sm font-semibold">{{ $activity }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 3. Grooming -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-4">
                        <h3 class="text-2xl font-bold text-white flex items-center">
                            <span class="text-3xl mr-3">✂️</span> Cuidado e Higiene
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-5 border-2 border-blue-200">
                                <h4 class="font-bold text-blue-900 mb-3 text-lg">🛁 Baños</h4>
                                <p class="text-gray-700"><span class="font-semibold">Frecuencia:</span> {{ $carePlan['grooming']['baths']['frequency'] }}</p>
                                <p class="text-gray-700 mt-2"><span class="font-semibold">Shampoo:</span> {{ $carePlan['grooming']['baths']['shampoo_type'] }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-5 border-2 border-purple-200">
                                <h4 class="font-bold text-purple-900 mb-3 text-lg">🪮 Cepillado</h4>
                                <p class="text-gray-700"><span class="font-semibold">Frecuencia:</span> {{ $carePlan['grooming']['brushing']['frequency'] }}</p>
                                <p class="text-gray-700 mt-2"><span class="font-semibold">Tipo:</span> {{ $carePlan['grooming']['brushing']['brush_type'] }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-5 border-2 border-amber-200">
                                <h4 class="font-bold text-amber-900 mb-3 text-lg">💅 Uñas</h4>
                                <p class="text-gray-700">{{ $carePlan['grooming']['nails']['frequency'] }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 border-2 border-green-200">
                                <h4 class="font-bold text-green-900 mb-3 text-lg">🦷 Dientes</h4>
                                <p class="text-gray-700"><span class="font-semibold">Cepillado:</span> {{ $carePlan['grooming']['teeth']['brushing'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Salud -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-4">
                        <h3 class="text-2xl font-bold text-white flex items-center">
                            <span class="text-3xl mr-3">💉</span> Salud Preventiva
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-r-xl p-5">
                            <p class="font-bold text-green-900 text-lg mb-2">Chequeos Veterinarios</p>
                            <p class="text-green-800">{{ $carePlan['health']['checkups']['frequency'] }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500 rounded-r-xl p-5">
                            <p class="font-bold text-blue-900 text-lg mb-2">Desparasitación</p>
                            <p class="text-blue-800">{{ $carePlan['health']['deworming']['adults'] }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl p-5 border-2 border-yellow-200">
                            <h4 class="font-bold text-yellow-900 mb-3 text-lg flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Señales de Alerta
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($carePlan['health']['signs_to_watch'] as $sign)
                                    <div class="flex items-start">
                                        <span class="text-yellow-600 mr-2">⚠️</span>
                                        <span class="text-gray-700 text-sm">{{ $sign }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Entrenamiento -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                        <h3 class="text-2xl font-bold text-white flex items-center">
                            <span class="text-3xl mr-3">🎓</span> Entrenamiento
                        </h3>
                    </div>
                    <div class="p-6">
                        @if (isset($carePlan['training']['basic_commands']) && !empty($carePlan['training']['basic_commands']))
                            <div class="mb-6">
                                <h4 class="font-bold text-gray-900 mb-3 text-lg">Comandos Básicos:</h4>
                                <div class="flex flex-wrap gap-3">
                                    @foreach ($carePlan['training']['basic_commands'] as $command)
                                        <span class="bg-gradient-to-r from-purple-100 to-pink-100 text-purple-800 px-4 py-2 rounded-full font-semibold border-2 border-purple-200">{{ $command }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        @if (isset($carePlan['training']['training_tips']) && !empty($carePlan['training']['training_tips']))
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-5">
                                <h4 class="font-bold text-gray-900 mb-3 text-lg">Tips de Entrenamiento:</h4>
                                <ul class="space-y-2">
                                    @foreach ($carePlan['training']['training_tips'] as $tip)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-gray-700">{{ $tip }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if (isset($carePlan['training']['message']))
                            <div class="bg-blue-50 rounded-xl p-5 mt-4 border-2 border-blue-200">
                                <p class="text-blue-800">{{ $carePlan['training']['message'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 6. Cuidados Especiales -->
                @if (!empty($carePlan['special_care']))
                    <div class="bg-gradient-to-br from-amber-50 to-yellow-50 rounded-2xl shadow-xl overflow-hidden border-2 border-yellow-300">
                        <div class="bg-gradient-to-r from-yellow-500 to-amber-600 px-6 py-4">
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <span class="text-3xl mr-3">⭐</span> Cuidados Especiales
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach ($carePlan['special_care'] as $category => $items)
                                <div class="bg-white rounded-xl p-5 shadow-md">
                                    <h4 class="font-bold text-yellow-900 capitalize mb-3 text-lg">{{ str_replace('_', ' ', $category) }}</h4>
                                    <ul class="space-y-2">
                                        @foreach ($items as $item)
                                            <li class="flex items-start">
                                                <span class="text-yellow-600 mr-2">★</span>
                                                <span class="text-gray-700">{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Modal de Registro -->
    @if($showRegistrationModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl max-w-md w-full p-8 shadow-2xl">
                <div class="text-center">
                    <div class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 rounded-full p-4 mb-4">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">
                        {{ $carePlan ? '¡Plan Generado!' : 'Demo Usada' }}
                    </h2>
                    
                    <p class="text-gray-600 mb-6">
                        @if($carePlan)
                            ¡Genial! Has probado el plan de cuidado con IA. Regístrate GRATIS para:
                        @else
                            Ya usaste tu demo gratuita. Regístrate GRATIS para:
                        @endif
                    </p>

                    <div class="text-left mb-6 space-y-3">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Planes ilimitados de cuidado</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Análisis de salud con IA</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Historial completo guardado</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Gestión de varias mascotas</span>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="block w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 rounded-xl font-bold text-lg hover:from-purple-700 hover:to-pink-700 transition shadow-lg mb-3">
                        Registrarme GRATIS
                    </a>

                    <button wire:click="$set('showRegistrationModal', false)" class="text-gray-500 hover:text-gray-700 text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
