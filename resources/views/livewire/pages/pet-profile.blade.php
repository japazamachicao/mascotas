<div class="min-h-screen bg-gray-100 py-6 sm:py-12">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl m-4">
        <div class="md:flex">
            <!-- Foto de la Mascota -->
            <div class="md:shrink-0">
                <img class="h-48 w-full object-cover md:h-full md:w-48" 
                     src="{{ $pet->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($pet->profile_photo_path) : 'https://ui-avatars.com/api/?name='.$pet->name.'&background=random&size=200' }}" 
                     alt="{{ $pet->name }}">
            </div>
            
            <div class="p-8 w-full">
                <div class="uppercase tracking-wide text-sm text-primary-600 font-semibold">{{ $pet->species ?? 'Mascota' }}</div>
                <h1 class="block mt-1 text-3xl leading-tight font-bold text-gray-900">{{ $pet->name }}</h1>
                <p class="mt-2 text-gray-500">{{ $pet->breed ?? 'Raza no especificada' }}</p>

                <!-- Botones de Acción Rápida (Contacto) -->
                <div class="mt-6 space-y-3">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4">
                        <div class="flex">
                            <div class="shrink-0">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Si encontraste a esta mascota, por favor contacta a su dueño:
                                </p>
                            </div>
                        </div>
                    </div>

                    <a href="tel:999999999" class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        Llamar al Dueño
                    </a>
                    
                    <a href="https://wa.me/51999999999" target="_blank" class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-500 hover:bg-green-600 md:py-4 md:text-lg">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        WhatsApp
                    </a>
                </div>

                <!-- Detalles de Mascota -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Sexo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->gender == 'M' ? 'Macho' : 'Hembra' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Peso</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pet->weight ?? 'No registrado' }} kg</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Notas Médicas Importantes</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $pet->medical_notes ?? 'Ninguna alergia o condición registrada.' }}
                            </dd>
                        </div>
                    </dl>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    <p class="text-xs text-gray-400">Identidad Digital verificada por Mascotas.pe</p>
                    <div class="mt-2 flex justify-center opacity-50">
                       {!! $qrCode !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
