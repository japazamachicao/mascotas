<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
    <div class="max-w-5xl mx-auto">
        <!-- Header mejorado -->
        <div class="text-center mb-8">
            <div class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full p-4 mb-4">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                Análisis de Salud con IA
            </h1>
            <p class="text-gray-600 text-lg">Detección temprana de problemas de salud en tu mascota</p>
        </div>

        @if (!$result && !$error)
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Disclaimer mejorado -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-indigo-500 rounded-r-lg p-5 mb-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-indigo-900 mb-1">Herramienta de detección temprana</h3>
                            <p class="text-sm text-indigo-700">
                                Este análisis usa IA para detectar posibles problemas. No reemplaza la consulta veterinaria profesional.
                                Si detectamos algo inusual, te recomendaremos visitar a tu veterinario.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Paso 1: Selección de mascota con cards -->
                <div class="mb-8">
                    <label class="block text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 text-indigo-700 rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm font-bold">1</span>
                        Selecciona tu mascota
                    </label>
                    
                    @if ($pets->count() > 3)
                        <select wire:model="selectedPetId" class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                            <option value="">-- Selecciona una mascota --</option>
                            @foreach ($pets as $pet)
                                <option value="{{ $pet->id }}">{{ $pet->name }} ({{ $pet->species }})</option>
                            @endforeach
                        </select>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($pets as $pet)
                                <button 
                                    wire:click="$set('selectedPetId', {{ $pet->id }})"
                                    class="p-4 rounded-xl border-2 transition-all {{ $selectedPetId == $pet->id ? 'border-indigo-500 bg-indigo-50 shadow-lg' : 'border-gray-200 hover:border-indigo-300 hover:shadow-md' }}"
                                >
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-lg">
                                            {{ substr($pet->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3 text-left">
                                            <p class="font-semibold text-gray-900">{{ $pet->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $pet->species }}</p>
                                        </div>
                                        @if ($selectedPetId == $pet->id)
                                            <svg class="ml-auto w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                    @error('selectedPetId') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Paso 2: Tipo de análisis con cards grandes -->
                <div class="mb-8">
                    <label class="block text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 text-indigo-700 rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm font-bold">2</span>
                        ¿Qué quieres analizar?
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button 
                            wire:click="$set('analysisType', 'feces')"
                            class="p-6 rounded-2xl border-2 transition-all {{ $analysisType === 'feces' ? 'border-yellow-500 bg-yellow-50 shadow-lg' : 'border-gray-200 hover:border-yellow-300 hover:shadow-md' }}"
                        >
                            <div class="text-5xl mb-3">💩</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Heces</h3>
                            <p class="text-sm text-gray-600">Consistencia, color, sangre, parásitos</p>
                            @if ($analysisType === 'feces')
                                <div class="mt-3 flex justify-center">
                                    <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Seleccionado</span>
                                </div>
                            @endif
                        </button>

                        <button 
                            wire:click="$set('analysisType', 'urine')"
                            class="p-6 rounded-2xl border-2 transition-all {{ $analysisType === 'urine' ? 'border-blue-500 bg-blue-50 shadow-lg' : 'border-gray-200 hover:border-blue-300 hover:shadow-md' }}"
                        >
                            <div class="text-5xl mb-3">💧</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Orina</h3>
                            <p class="text-sm text-gray-600">Color, sangre, deshidratación</p>
                            @if ($analysisType === 'urine')
                                <div class="mt-3 flex justify-center">
                                    <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Seleccionado</span>
                                </div>
                            @endif
                        </button>

                        <button 
                            wire:click="$set('analysisType', 'skin')"
                            class="p-6 rounded-2xl border-2 transition-all {{ $analysisType === 'skin' ? 'border-pink-500 bg-pink-50 shadow-lg' : 'border-gray-200 hover:border-pink-300 hover:shadow-md' }}"
                        >
                            <div class="text-5xl mb-3">🔬</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">Piel/Lengua</h3>
                            <p class="text-sm text-gray-600">Lesiones, erupciones, parásitos, color</p>
                            @if ($analysisType === 'skin')
                                <div class="mt-3 flex justify-center">
                                    <span class="bg-pink-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Seleccionado</span>
                                </div>
                            @endif
                        </button>
                    </div>
                    @error('analysisType') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Paso 3: Subir foto mejorado -->
                <div class="mb-8">
                    <label class="block text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="bg-indigo-100 text-indigo-700 rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm font-bold">3</span>
                        Sube una foto clara
                    </label>
                    
                    <div class="relative">
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
                            <label class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer hover:border-indigo-400 hover:bg-indigo-50 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-7">
                                    <svg class="w-16 h-16 mb-4 text-gray-400 group-hover:text-indigo-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mb-2 text-lg font-semibold text-gray-700 group-hover:text-indigo-700">
                                        <span class="text-indigo-600">Click para subir</span> o arrastra aquí
                                    </p>
                                    <p class="text-sm text-gray-500">JPG, JPEG, PNG, GIF, WebP (máx. 10MB)</p>
                                </div>
                                <input id="file-upload" wire:model="photo" type="file" class="hidden" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                            </label>
                        @endif
                    </div>
                    @error('photo') <p class="text-red-500 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p> @enderror
                </div>

                <!-- Botón de análisis mejorado -->
                <button 
                    wire:click="analyze" 
                    wire:loading.attr="disabled"
                    wire:target="photo, analyze"
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 px-6 rounded-xl font-semibold text-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]"
                >
                    <span wire:loading.remove wire:target="analyze" class="flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Analizar con IA 🔬
                    </span>
                    <span wire:loading wire:target="photo" class="flex items-center justify-center">
                        <svg class="animate-spin h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Subiendo foto... ⏳
                    </span>
                    <span wire:loading wire:target="analyze" class="flex items-center justify-center">
                        <svg class="animate-spin h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Analizando con IA... ⏳
                    </span>
                </button>

                <!-- Contador de análisis restantes -->
                <div class="mt-4 text-center text-sm text-gray-500">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Análisis disponibles hoy: {{ $maxAnalyses - $analysesCount }} de {{ $maxAnalyses }}
                </div>
            </div>
        @endif

        <!-- Resultado con diseño mejorado -->
        @if ($result)
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Header del resultado -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold mb-1">Resultados del Análisis</h2>
                            <p class="text-indigo-100">{{ $result->pet->name }} • {{ ucfirst($analysisType) }}</p>
                        </div>
                        <button wire:click="resetAnalysis" class="bg-white bg-opacity-20 hover:bg-opacity-30 p-2 rounded-lg transition">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Alerta de estado -->
                    @if ($result->requires_attention)
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 rounded-r-xl p-6 mb-6 shadow-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-bold text-yellow-900 mb-1">⚠️ Requiere Atención Veterinaria</h3>
                                    <p class="text-yellow-800">Hemos detectado señales que requieren evaluación profesional. Te recomendamos programar una consulta con tu veterinario lo antes posible.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-r-xl p-6 mb-6 shadow-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-bold text-green-900 mb-1">✅ Todo Parece Normal</h3>
                                    <p class="text-green-800">No detectamos señales preocupantes. Ante cualquier duda, consulta con tu veterinario.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Hallazgos -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Hallazgos Principales
                        </h3>
                        <div class="grid gap-3">
                            @foreach ($result->findings as $finding)
                                <div class="flex items-start bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition">
                                    <svg class="w-5 h-5 text-indigo-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-gray-700">{{ $finding }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recomendaciones -->
                    @if ($result->recommendations)
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                Recomendaciones
                            </h3>
                            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border-2 border-purple-200">
                                <p class="text-gray-800 leading-relaxed">{{ $result->recommendations }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Footer con acciones -->
                    <div class="flex items-center justify-between pt-6 border-t-2 border-gray-100">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Confianza: <span class="font-bold ml-1">{{ round($result->confidence_score * 100) }}%</span>
                        </div>
                        <div class="flex gap-3">
                            <button wire:click="resetAnalysis" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                                Nuevo Análisis
                            </button>
                            <a href="{{ route('dashboard.health.history') }}" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition font-medium">
                                Ver Historial →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error mejorado -->
        @if ($error)
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-r-xl p-6 mb-6">
                    <div class="flex items-start">
                        <svg class="h-8 w-8 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-red-900 mb-1">Error al Analizar</h3>
                            <p class="text-red-800">{{ $error }}</p>
                        </div>
                    </div>
                </div>
                <button wire:click="resetAnalysis" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition font-semibold">
                    Intentar de Nuevo
                </button>
            </div>
        @endif
    </div>
</div>
