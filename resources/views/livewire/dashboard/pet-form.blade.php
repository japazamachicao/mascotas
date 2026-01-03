<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Tab Navigation (Alpine.js) -->
        <div x-data="{ activeTab: @entangle('activeTab') }" class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header & Tabs -->
            <div class="border-b border-gray-100 bg-gray-50/50">
                <div class="px-8 py-6">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $pet ? 'Editar: ' . $pet->name : 'Agregar Nueva Mascota' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $pet ? 'Completa su perfil para acceder a mejores servicios.' : 'Datos básicos para empezar.' }}
                    </p>
                </div>
                
                <div class="flex px-4 overflow-x-auto gap-4">
                    <button @click="activeTab = 'general'" 
                            :class="activeTab === 'general' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="whitespace-nowrap pb-4 px-4 border-b-2 font-medium text-sm transition-colors duration-200">
                        📋 Datos Básicos
                    </button>
                    
                    @if($pet)
                        <button @click="activeTab = 'behavior'" 
                                :class="activeTab === 'behavior' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                class="whitespace-nowrap pb-4 px-4 border-b-2 font-medium text-sm transition-colors duration-200">
                            🧠 Comportamiento
                        </button>
                        <button @click="activeTab = 'health'" 
                                :class="activeTab === 'health' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                                class="whitespace-nowrap pb-4 px-4 border-b-2 font-medium text-sm transition-colors duration-200">
                            🩺 Salud
                        </button>
                    @endif
                </div>
            </div>

            <form wire:submit.prevent="save" class="px-8 py-8 min-h-[400px]">
                
                <!-- TAB 1: DATOS GENERALES -->
                <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <!-- Foto de Perfil Centrada -->
                    <div class="flex flex-col items-center justify-center space-y-4 mb-8">
                        <div class="relative group">
                            <div class="h-32 w-32 rounded-full overflow-hidden border-4 border-white shadow-lg ring-2 ring-gray-100 bg-gray-50">
                                @if ($photo)
                                    <img class="h-full w-full object-cover" src="{{ $photo->temporaryUrl() }}" alt="Preview">
                                @elseif ($existingPhoto)
                                    <img class="h-full w-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::url($existingPhoto) }}" alt="Foto Actual">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-300">
                                        <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    </div>
                                @endif
                            </div>
                            <label for="photo-upload" class="absolute bottom-0 right-0 bg-primary-600 rounded-full p-2 text-white shadow-md cursor-pointer hover:bg-primary-700 transition">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </label>
                            <input id="photo-upload" type="file" wire:model="photo" class="hidden">
                        </div>
                        @error('photo') <span class="text-red-500 text-xs font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label for="name" class="block text-sm font-semibold text-gray-700">Nombre *</label>
                            <input wire:model="name" type="text" id="name" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50 focus:bg-white transition" placeholder="Ej: Rocky">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="species" class="block text-sm font-semibold text-gray-700">Especie *</label>
                            <select wire:model.live="species" id="species" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50 focus:bg-white transition">
                                <option value="Perro">Perro 🐶</option>
                                <option value="Gato">Gato 🐱</option>
                                <option value="Otro">Otro 🐰</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="breed" class="block text-sm font-semibold text-gray-700">Raza</label>
                            <input list="breeds" wire:model="breed" type="text" id="breed" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50 focus:bg-white transition" placeholder="Busca o escribe...">
                            <datalist id="breeds">
                                @foreach($this->breeds as $breedOption)
                                    <option value="{{ $breedOption }}">
                                @endforeach
                            </datalist>
                        </div>
                        
                        <div class="space-y-1">
                            <label for="gender" class="block text-sm font-semibold text-gray-700">Género *</label>
                            <select wire:model="gender" id="gender" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50 focus:bg-white transition">
                                <option value="M">Macho ♂</option>
                                <option value="F">Hembra ♀</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="color" class="block text-sm font-semibold text-gray-700">Color *</label>
                            <select wire:model="color" id="color" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50 focus:bg-white transition">
                                <option value="">Selecciona...</option>
                                @foreach($this->colors as $colorOption)
                                    <option value="{{ $colorOption }}">{{ $colorOption }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="weight" class="block text-sm font-semibold text-gray-700">Peso (kg) *</label>
                            <div class="relative rounded-md shadow-sm">
                                <input wire:model="weight" type="number" step="0.1" id="weight" class="block w-full rounded-lg border-gray-300 pl-4 pr-12 focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 bg-gray-50 focus:bg-white transition" placeholder="0.0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">kg</span>
                                </div>
                            </div>
                            @error('weight') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label for="birth_date" class="block text-sm font-semibold text-gray-700">Fecha Nacimiento <span class="text-gray-400 font-normal">(Aprox)</span></label>
                            <input wire:model="birth_date" type="date" id="birth_date" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50 focus:bg-white transition">
                        </div>

                        <div class="space-y-1">
                            <label for="chip_id" class="block text-sm font-semibold text-gray-700">N° Microchip <span class="text-gray-400 font-normal">(Opcional)</span></label>
                            <input wire:model="chip_id" type="text" id="chip_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-gray-50 focus:bg-white transition" placeholder="Ej: 981098...">
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'behavior'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-blue-50/50 rounded-xl p-6 border border-blue-100 mb-6">
                        <div class="flex items-start gap-3 mb-6">
                            <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900">Personalidad y Carácter</h4>
                                <p class="text-sm text-blue-700">Ayuda a los cuidadores a entender mejor a tu mascota.</p>
                            </div>
                        </div>
                        
                        <div class="space-y-8">
                            <!-- Nivel de Energía -->
                            <div class="space-y-3">
                                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide">Nivel de Energía</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <!-- Baja -->
                                    <label class="cursor-pointer group">
                                        <input type="radio" wire:model="energy_level" value="baja" class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-blue-300 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-200 peer-checked:ring-opacity-50">
                                            <div class="flex flex-col items-center text-center">
                                                <span class="text-2xl mb-2 group-hover:scale-110 transition-transform">🛋️</span>
                                                <span class="text-sm font-bold text-gray-900">Tranquila</span>
                                                <span class="text-xs text-gray-500 mt-1">Prefiere dormir</span>
                                            </div>
                                        </div>
                                    </label>
                                    <!-- Media -->
                                    <label class="cursor-pointer group">
                                        <input type="radio" wire:model="energy_level" value="media" class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-blue-300 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-200 peer-checked:ring-opacity-50">
                                            <div class="flex flex-col items-center text-center">
                                                <span class="text-2xl mb-2 group-hover:scale-110 transition-transform">🚶</span>
                                                <span class="text-sm font-bold text-gray-900">Moderada</span>
                                                <span class="text-xs text-gray-500 mt-1">Paseos normales</span>
                                            </div>
                                        </div>
                                    </label>
                                    <!-- Alta -->
                                    <label class="cursor-pointer group">
                                        <input type="radio" wire:model="energy_level" value="alta" class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-orange-300 transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:ring-2 peer-checked:ring-orange-200 peer-checked:ring-opacity-50">
                                            <div class="flex flex-col items-center text-center">
                                                <span class="text-2xl mb-2 group-hover:scale-110 transition-transform">⚡</span>
                                                <span class="text-sm font-bold text-gray-900">Intensa</span>
                                                <span class="text-xs text-gray-500 mt-1">Necesita correr</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Sociabilidad -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide">Sociable con...</label>
                                    <div class="flex flex-wrap gap-3">
                                        <label class="cursor-pointer">
                                            <input type="checkbox" wire:model="sociable_kids" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-all peer-checked:bg-green-100 peer-checked:text-green-700 peer-checked:border-green-300 font-medium flex items-center gap-2 select-none">
                                                <span>👶</span> Niños
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="checkbox" wire:model="sociable_dogs" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-all peer-checked:bg-green-100 peer-checked:text-green-700 peer-checked:border-green-300 font-medium flex items-center gap-2 select-none">
                                                <span>🐕</span> Perros
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="checkbox" wire:model="sociable_cats" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-all peer-checked:bg-green-100 peer-checked:text-green-700 peer-checked:border-green-300 font-medium flex items-center gap-2 select-none">
                                                <span>🐈</span> Gatos
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Miedos -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide text-red-800/80">Miedos / Alertas</label>
                                    <div class="flex flex-wrap gap-3">
                                        <label class="cursor-pointer">
                                            <input type="checkbox" wire:model="fear_fireworks" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-all peer-checked:bg-red-100 peer-checked:text-red-700 peer-checked:border-red-300 font-medium flex items-center gap-2 select-none">
                                                <span>🎆</span> Pirotecnia
                                            </div>
                                        </label>
                                        <label class="cursor-pointer">
                                            <input type="checkbox" wire:model="fear_cars" class="peer sr-only">
                                            <div class="px-4 py-2 rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-all peer-checked:bg-red-100 peer-checked:text-red-700 peer-checked:border-red-300 font-medium flex items-center gap-2 select-none">
                                                <span>🏍️</span> Autos/Motos
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: SALUD -->
                <div x-show="activeTab === 'health'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="bg-green-50/50 rounded-xl p-6 border border-green-100 mb-6">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-1">
                                <label for="vaccination_date" class="block text-sm font-semibold text-gray-700">Última Vacunación 💉</label>
                                <input wire:model="vaccination_date" type="date" id="vaccination_date" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-3 px-4 bg-white transition">
                                <p class="text-xs text-gray-500 mt-1">Fecha de su última vacuna importante.</p>
                            </div>

                            <div class="space-y-1">
                                <label for="deworming_date" class="block text-sm font-semibold text-gray-700">Última Desparasitación 🦠</label>
                                <input wire:model="deworming_date" type="date" id="deworming_date" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-3 px-4 bg-white transition">
                            </div>

                            <div class="flex items-center pt-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input wire:model="is_sterilized" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50 h-5 w-5">
                                    <span class="ml-3 text-sm font-medium text-gray-700">¿Está esterilizado?</span>
                                </label>
                            </div>
                            
                            <!-- Notas Moved Here too -->
                            <div class="sm:col-span-2 space-y-1">
                                <label for="medical_notes" class="block text-sm font-semibold text-gray-700">Notas Médicas / Alergias</label>
                                <textarea wire:model="medical_notes" id="medical_notes" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3 px-4 bg-white transition" placeholder="Ej: Alérgico al pollo, toma medicación para el corazón..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones Globales -->
                <div class="flex justify-end pt-8 gap-3 border-t border-gray-100 mt-4">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 font-medium transition shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold hover:shadow-lg transform hover:-translate-y-0.5 transition shadow-md flex items-center">
                        <svg wire:loading.class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" wire:target="save" class="hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Guardar Cambios</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
