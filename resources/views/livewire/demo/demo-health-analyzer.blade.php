<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full p-4 mb-4">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                Análisis de Salud con IA - DEMO GRATUITA
            </h1>
            <p class="text-gray-600 text-lg">Prueba la detección temprana de problemas de salud</p>
            @if(!$demoUsed)
                <div class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold mt-3">
                    ✨ Puedes usar esta demo UNA VEZ gratis
                </div>
            @endif
        </div>

        @if(!$result && !$error)
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Tipo de análisis -->
                <div class="mb-8">
                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                        ¿Qué quieres analizar?
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button 
                            wire:click="$set('analysisType', 'feces')"
                            class="p-6 rounded-xl border-2 transition-all {{ $analysisType == 'feces' ? 'border-indigo-500 bg-indigo-50 shadow-lg' : 'border-gray-200 hover:border-indigo-300' }}">
                            <div class="text-4xl mb-2">💩</div>
                            <h3 class="font-bold text-gray-900">Heces</h3>
                            <p class="text-sm text-gray-600">Detectar parásitos, sangre</p>
                        </button>
                        <button 
                            wire:click="$set('analysisType', 'urine')"
                            class="p-6 rounded-xl border-2 transition-all {{ $analysisType == 'urine' ? 'border-indigo-500 bg-indigo-50 shadow-lg' : 'border-gray-200 hover:border-indigo-300' }}">
                            <div class="text-4xl mb-2">💧</div>
                            <h3 class="font-bold text-gray-900">Orina</h3>
                            <p class="text-sm text-gray-600">Color, sangre, deshidratación</p>
                        </button>
                        <button 
                            wire:click="$set('analysisType', 'skin')"
                            class="p-6 rounded-xl border-2 transition-all {{ $analysisType == 'skin' ? 'border-indigo-500 bg-indigo-50 shadow-lg' : 'border-gray-200 hover:border-indigo-300' }}">
                            <div class="text-4xl mb-2">👅</div>
                            <h3 class="font-bold text-gray-900">Piel/Lengua</h3>
                            <p class="text-sm text-gray-600">Lesiones, color anormal</p>
                        </button>
                    </div>
                </div>

                <!-- Subir foto -->
                <div class="mb-8">
                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                        Sube una foto clara
                    </label>
                    <input type="file" wire:model="photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                    @error('photo') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Botón analizar -->
                <button 
                    wire:click="analyze" 
                    wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-xl font-bold text-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg disabled:opacity-50">
                    <span wire:loading.remove wire:target="analyze">🔬 Analizar Ahora</span>
                    <span wire:loading wire:target="analyze">Analizando...</span>
                </button>
            </div>
        @endif

        @if($error)
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-lg">
                <p class="text-red-700">{{ $error }}</p>
            </div>
        @endif

        @if($result)
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold mb-4 text-gray-900">Resultados del Análisis</h2>
                
                <div class="mb-6 p-4 rounded-lg {{ $result->requires_attention ? 'bg-yellow-50 border-l-4 border-yellow-500' : 'bg-green-50 border-l-4 border-green-500' }}">
                    <p class="font-semibold {{ $result->requires_attention ? 'text-yellow-800' : 'text-green-800' }}">
                        {{ $result->requires_attention ? '⚠️ Requiere atención' : '✅ Todo parece normal' }}
                    </p>
                </div>

                <div class="mb-6">
                    <h3 class="font-bold text-gray-900 mb-2">Hallazgos:</h3>
                    @if(is_array($result->findings))
                        <ul class="list-disc list-inside text-gray-700 space-y-1">
                            @foreach($result->findings as $finding)
                                <li>{{ $finding }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-700">{{ $result->findings }}</p>
                    @endif
                </div>

                <div class="mb-6">
                    <h3 class="font-bold text-gray-900 mb-2">Recomendaciones:</h3>
                    @if(is_array($result->recommendations))
                        <ul class="list-disc list-inside text-gray-700 space-y-1">
                            @foreach($result->recommendations as $recommendation)
                                <li>{{ $recommendation }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-700">{{ $result->recommendations }}</p>
                    @endif
                </div>

                <p class="text-sm text-gray-500">Confianza: {{ number_format($result->confidence_score * 100, 0) }}%</p>
            </div>
        @endif
    </div>

    <!-- Modal de Registro -->
    @if($showRegistrationModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl max-w-md w-full p-8 shadow-2xl">
                <div class="text-center">
                    <div class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full p-4 mb-4">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">
                        {{ $result ? '¡Análisis Completado!' : 'Demo Usada' }}
                    </h2>
                    
                    <p class="text-gray-600 mb-6">
                        @if($result)
                            ¡Genial! Has probado el análisis con IA. Regístrate GRATIS para:
                        @else
                            Ya usaste tu demo gratuita. Regístrate GRATIS para:
                        @endif
                    </p>

                    <div class="text-left mb-6 space-y-3">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Análisis ilimitados de salud</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Planes de cuidado personalizados</span>
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
                            <span class="text-gray-700">Gestión de mascotas</span>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-4 rounded-xl font-bold text-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg mb-3">
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
