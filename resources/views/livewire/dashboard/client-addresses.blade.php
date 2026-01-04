<div class="py-12 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 px-4 sm:px-0 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Mis Direcciones</h2>
                <p class="text-gray-500 text-sm mt-1">Gestiona tus ubicaciones para recibir servicios en casa.</p>
            </div>
            <button wire:click="create" class="w-full sm:w-auto bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-primary-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nueva Dirección
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 mx-4 sm:mx-0 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm" role="alert">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="block sm:inline font-medium">{{ session('message') }}</span>
            </div>
        @endif

        @if($addresses->isEmpty())
            <div class="mx-4 sm:mx-0 text-center py-16 bg-white rounded-3xl shadow-sm border-2 border-dashed border-gray-200">
                <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">No tienes direcciones guardadas</h3>
                <p class="mt-2 text-sm text-gray-500 max-w-xs mx-auto">Agrega tu casa, oficina o parque favorito para solicitar servicios a domicilio fácilmente.</p>
                <div class="mt-6">
                    <button wire:click="create" class="text-primary-600 hover:text-primary-700 font-bold text-sm underline decoration-2 decoration-primary-200 hover:decoration-primary-500 transition-all">
                        Agregar primera dirección
                    </button>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
                @foreach($addresses as $addr)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative hover:shadow-xl hover:border-blue-100 transition-all group duration-300">
                        @if($addr->is_default)
                            <div class="absolute top-4 right-4">
                                <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-bold flex items-center shadow-sm">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Principal
                                </span>
                            </div>
                        @else
                           <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                               <span class="text-gray-300 text-xs font-medium">Secundaria</span>
                           </div>
                        @endif
                        
                        <div class="flex items-center mb-4">
                            <span class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl {{ stripos($addr->name, 'casa') !== false ? 'bg-indigo-50 text-indigo-600' : (stripos($addr->name, 'oficina') !== false ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600') }}">
                                @if(stripos($addr->name, 'casa') !== false) 
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                @elseif(stripos($addr->name, 'oficina') !== false) 
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                @else 
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                @endif
                            </span>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $addr->name }}</h3>
                                <p class="text-xs text-gray-400 mt-0.5">Creada {{ $addr->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <p class="text-gray-700 text-sm font-medium flex items-start">
                                <span class="text-gray-400 mr-2 mt-0.5 w-4 text-center">📍</span>
                                {{ $addr->address }}
                            </p>
                            <p class="text-gray-500 text-xs flex items-center">
                                <span class="text-gray-400 mr-2 w-4 text-center">🏙️</span>
                                {{ $addr->district->name ?? 'N/A' }}, {{ $addr->district->province->name ?? 'N/A' }}
                                <span class="hidden sm:inline">, {{ $addr->district->department->name ?? 'N/A' }}</span>
                            </p>
                            @if($addr->reference)
                                <p class="text-gray-500 text-xs flex items-start bg-gray-50 p-2 rounded-lg mt-2 italic border border-gray-100">
                                    <span class="text-gray-400 mr-2 mt-0.5 w-4 text-center">ℹ️</span>
                                    {{ $addr->reference }}
                                </p>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-gray-50 flex justify-between items-center opacity-80 group-hover:opacity-100 transition-opacity">
                            <button wire:click="edit({{ $addr->id }})" class="text-sm text-gray-500 hover:text-blue-600 font-semibold transition flex items-center px-2 py-1 hover:bg-blue-50 rounded-lg">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Editar
                            </button>
                            <button wire:click="delete({{ $addr->id }})" class="text-sm text-red-400 hover:text-red-600 font-semibold transition flex items-center px-2 py-1 hover:bg-red-50 rounded-lg" onclick="confirm('¿Estás seguro de eliminar esta dirección?') || event.stopImmediatePropagation()">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Eliminar
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Modal -->
        <div x-data="{ open: @entangle('isModalOpen') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-60 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                    <div class="bg-white px-6 py-6 sm:p-8">
                        <div class="text-left w-full">
                            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $addressId ? 'Editar Dirección' : 'Nueva Dirección' }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $addressId ? 'Actualiza los datos de tu ubicación.' : 'Añade una nueva ubicación para tus servicios.' }}
                                    </p>
                                </div>
                                <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <form wire:submit.prevent="save" class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <!-- Tip: Start with location -->
                                    <div class="col-span-1 md:col-span-2 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                                        <div class="flex items-center mb-4">
                                            <div class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            </div>
                                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Ubicación Geográfica</h4>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Departamento -->
                                            <div class="group">
                                                <label class="block text-xs font-semibold text-gray-400 mb-1 ml-1">Departamento</label>
                                                <div class="relative">
                                                    <select wire:model.live="selectedDepartment" class="block w-full py-2.5 px-4 bg-gray-50 border-transparent text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:bg-white focus:border-transparent transition-all hover:bg-white border-2 hover:border-gray-100">
                                                        <option value="">Seleccionar...</option>
                                                        @foreach($departments as $dep)
                                                            <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('selectedDepartment') <span class="text-red-500 text-xs ml-1">{{ $message }}</span> @enderror
                                            </div>

                                            <!-- Provincia -->
                                            <div class="group {{ empty($provinces) ? 'opacity-50' : '' }}">
                                                <label class="block text-xs font-semibold text-gray-400 mb-1 ml-1">Provincia</label>
                                                <div class="relative">
                                                    <select wire:model.live="selectedProvince" class="block w-full py-2.5 px-4 bg-gray-50 border-transparent text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:bg-white focus:border-transparent transition-all hover:bg-white border-2 hover:border-gray-100 cursor-{{ empty($provinces) ? 'not-allowed' : 'pointer' }}" {{ empty($provinces) ? 'disabled' : '' }}>
                                                        <option value="">{{ empty($provinces) ? 'Esperando...' : 'Seleccionar...' }}</option>
                                                        @foreach($provinces as $prov)
                                                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Distrito -->
                                            <div class="group {{ empty($districts) ? 'opacity-50' : '' }}">
                                                <label class="block text-xs font-semibold text-gray-400 mb-1 ml-1">Distrito</label>
                                                <div class="relative">
                                                    <select wire:model="district_id" class="block w-full py-2.5 px-4 bg-gray-50 border-transparent text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-primary-500 focus:bg-white focus:border-transparent transition-all hover:bg-white border-2 hover:border-gray-100 cursor-{{ empty($districts) ? 'not-allowed' : 'pointer' }}" {{ empty($districts) ? 'disabled' : '' }}>
                                                        <option value="">{{ empty($districts) ? 'Esperando...' : 'Seleccionar Distrito' }}</option>
                                                        @foreach($districts as $dist)
                                                            <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('district_id') <span class="text-red-500 text-xs ml-1 mt-1 block">Requerido</span> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Nombre / Alias</label>
                                        <input wire:model="name" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm h-10" placeholder="Ej: Mi Casa, Oficina, Parque Reducto">
                                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Dirección Exacta</label>
                                        <input wire:model="address" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm h-10" placeholder="Av. Larco 123, Dpto 401">
                                        @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-span-1 md:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Referencia (Opcional)</label>
                                        <input wire:model="reference" type="text" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm h-10" placeholder="Frente al supermercado...">
                                    </div>

                                    <div class="col-span-1 md:col-span-2 bg-gray-50 p-3 rounded-lg flex items-center">
                                        <input wire:model="is_default" id="is_default" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded cursor-pointer">
                                        <label for="is_default" class="ml-2 block text-sm text-gray-700 font-medium cursor-pointer">
                                            Establecer como dirección principal
                                        </label>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-6 gap-3">
                                    <button @click="open = false" type="button" class="bg-white border border-gray-300 text-gray-700 font-bold py-2.5 px-6 rounded-xl hover:bg-gray-50 transition shadow-sm">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-primary-500/30 transition transform hover:-translate-y-0.5">
                                        Guardar Dirección
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
