<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Agregar Nueva Mascota
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Completa la ficha completa para cuidar mejor de tu engreído.
                </p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <form wire:submit.prevent="save" class="space-y-8">
                    
                    <!-- Foto de Perfil -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto de tu Mascota</label>
                        <div class="mt-2 flex items-center space-x-6">
                            <div class="shrink-0">
                                @if ($photo)
                                    <img class="h-24 w-24 object-cover rounded-full ring-2 ring-primary-500" src="{{ $photo->temporaryUrl() }}" alt="Preview">
                                @else
                                    <span class="inline-block h-24 w-24 rounded-full overflow-hidden bg-gray-100">
                                        <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <input type="file" wire:model="photo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition"/>
                                <p class="mt-2 text-xs text-gray-500">PNG, JPG hasta 5MB</p>
                                @error('photo') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 border-t border-gray-100 pt-6">
                        <!-- Nombre -->
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre *</label>
                            <div class="mt-1">
                                <input wire:model="name" type="text" id="name" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Chip ID -->
                        <div class="sm:col-span-3">
                            <label for="chip_id" class="block text-sm font-medium text-gray-700">N° Microchip (Opcional)</label>
                            <div class="mt-1">
                                <input wire:model="chip_id" type="text" id="chip_id" placeholder="Ej: 981098..." class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Vital si se pierde.</p>
                        </div>

                        <!-- Especie -->
                        <div class="sm:col-span-2">
                            <label for="species" class="block text-sm font-medium text-gray-700">Especie *</label>
                            <div class="mt-1">
                                <select wire:model.live="species" id="species" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="Perro">Perro</option>
                                    <option value="Gato">Gato</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                        </div>

                        <!-- Raza -->
                        <div class="sm:col-span-2">
                            <label for="breed" class="block text-sm font-medium text-gray-700">Raza </label>
                            <div class="mt-1">
                                <input list="breeds" wire:model="breed" type="text" id="breed" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Escribe o selecciona...">
                                <datalist id="breeds">
                                    @foreach($this->breeds as $breedOption)
                                        <option value="{{ $breedOption }}">
                                    @endforeach
                                </datalist>
                            </div>
                        </div>

                        <!-- Color -->
                        <div class="sm:col-span-2">
                            <label for="color" class="block text-sm font-medium text-gray-700">Color *</label>
                            <div class="mt-1">
                                <select wire:model="color" id="color" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Selecciona...</option>
                                    @foreach($this->colors as $colorOption)
                                        <option value="{{ $colorOption }}">{{ $colorOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('color') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Género -->
                        <div class="sm:col-span-2">
                            <label for="gender" class="block text-sm font-medium text-gray-700">Género *</label>
                            <div class="mt-1">
                                <select wire:model="gender" id="gender" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="M">Macho</option>
                                    <option value="F">Hembra</option>
                                </select>
                            </div>
                        </div>

                        <!-- Peso -->
                        <div class="sm:col-span-2">
                            <label for="weight" class="block text-sm font-medium text-gray-700">Peso (kg) *</label>
                            <div class="mt-1">
                                <input wire:model="weight" type="number" step="0.1" id="weight" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('weight') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Esterilizado -->
                        <div class="sm:col-span-2 flex items-center pt-6">
                            <input wire:model="is_sterilized" id="is_sterilized" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="is_sterilized" class="ml-2 block text-sm text-gray-900">
                                ¿Está esterilizado?
                            </label>
                        </div>

                        <!-- Fecha Nacimiento -->
                        <div class="sm:col-span-3">
                            <label for="birth_date" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento (Aprox)</label>
                            <div class="mt-1">
                                <input wire:model="birth_date" type="date" id="birth_date" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Notas Médicas -->
                        <div class="sm:col-span-6">
                            <label for="medical_notes" class="block text-sm font-medium text-gray-700">Notas Médicas / Alergias</label>
                            <div class="mt-1">
                                <textarea wire:model="medical_notes" id="medical_notes" rows="3" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">¿Tiene alguna condición crónica o alergia alimentaria?</p>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-gray-200 flex justify-end">
                        <a href="{{ route('dashboard') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Cancelar
                        </a>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg wire:loading.class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" wire:target="save" class="hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Guardar Mascota</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
