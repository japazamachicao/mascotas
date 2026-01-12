<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-indigo-50 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-block bg-gradient-to-r from-purple-600 to-pink-600 rounded-full p-4 mb-4">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                Plan de Cuidado IA - DEMO GRATUITA
            </h1>
            <p class="text-gray-600 text-lg">Plan personalizado generado por IA para tu mascota</p>
            @if(!$demoUsed)
                <div class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold mt-3">
                    ✨ Puedes usar esta demo UNA VEZ gratis
                </div>
            @endif
        </div>

        @if(!$carePlan && !$error)
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="mb-8 bg-gradient-to-r from-purple-50 to-pink-50 border-l-4 border-purple-500 rounded-r-lg p-5">
                    <p class="text-purple-900 font-semibold">
                        📸 Sube una foto de tu mascota y la IA creará un plan completo de cuidado
                    </p>
                </div>

                <div class="mb-8">
                    <label class="block text-lg font-semibold text-gray-900 mb-4">
                        Foto de tu mascota
                    </label>
                    <input type="file" wire:model="photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                    @error('photo') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <button 
                    wire:click="generateFromPhoto" 
                    wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 rounded-xl font-bold text-lg hover:from-purple-700 hover:to-pink-700 transition shadow-lg disabled:opacity-50">
                    <span wire:loading.remove wire:target="generateFromPhoto">📋 Generar Plan Ahora</span>
                    <span wire:loading wire:target="generateFromPhoto">Generando plan...</span>
                </button>
            </div>
        @endif

        @if($error)
            <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-r-lg">
                <p class="text-red-700">{{ $error }}</p>
            </div>
        @endif

        @if($carePlan)
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-3xl font-bold mb-2 text-gray-900">Plan de Cuidado para {{ $petData['name'] }}</h2>
                <p class="text-gray-600 mb-6">{{ ucfirst($petData['breed']) }} • {{ ucfirst($petData['species']) }}</p>

                <div class="space-y-6">
                    @foreach($carePlan as $category => $details)
                        <div class="border-l-4 {{ $loop->index % 3 == 0 ? 'border-purple-500 bg-purple-50' : ($loop->index % 3 == 1 ? 'border-pink-500 bg-pink-50' : 'border-indigo-500 bg-indigo-50') }} rounded-r-lg p-6">
                            <h3 class="text-xl font-bold mb-3 {{ $loop->index % 3 == 0 ? 'text-purple-900' : ($loop->index % 3 == 1 ? 'text-pink-900' : 'text-indigo-900') }}">
                                {{ $category }}
                            </h3>
                            <p class="text-gray-700">{{ is_array($details) ? implode('. ', $details) : $details }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg">
                    <p class="text-yellow-800 text-sm">
                        ⚠️ Este plan es una guía general. Consulta con tu veterinario para necesidades específicas.
                    </p>
                </div>
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
                            ¡Increíble! Has visto el poder de la IA. Regístrate GRATIS para:
                        @else
                            Ya usaste tu demo gratuita. Regístrate GRATIS para:
                        @endif
                    </p>

                    <div class="text-left mb-6 space-y-3">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Planes ilimitados personalizados</span>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Guardado en historial</span>
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
                            <span class="text-gray-700">Gestión de mascotas</span>
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
