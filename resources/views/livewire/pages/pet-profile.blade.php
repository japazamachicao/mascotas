<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo pequeño arriba -->
        <div class="flex justify-center mb-6">
            <span class="flex items-center gap-2 text-gray-400 font-medium tracking-wide text-sm uppercase">
                <img class="h-6 w-auto opacity-50" src="https://cdn-icons-png.flaticon.com/512/616/616554.png" alt="Logo">
                Mascotas.pe
            </span>
        </div>

        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden transform transition-all hover:scale-[1.005] duration-300 relative">
            <!-- Decorative Banner -->
            <div class="h-32 bg-gradient-to-r from-primary-500 to-indigo-600 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <svg class="h-full w-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <path d="M0 100 C 20 0 50 0 100 100 Z" />
                    </svg>
                </div>
            </div>

            <!-- Profile Photo (Overlapping) -->
            <div class="relative px-6 pb-4">
                <div class="-mt-16 flex justify-center">
                    <div class="relative rounded-full p-1.5 bg-white shadow-lg">
                        <img class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-sm" 
                             src="{{ $pet->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($pet->profile_photo_path) : 'https://ui-avatars.com/api/?name='.$pet->name.'&background=random&size=256' }}" 
                             alt="{{ $pet->name }}">
                        <!-- Verified Badge -->
                        <div class="absolute bottom-2 right-2 bg-blue-500 text-white rounded-full p-1 shadow-md border-2 border-white" title="Identidad Verificada">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="mt-3 text-center">
                    <div class="flex items-center justify-center gap-2 mb-1">
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $pet->name }}</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pet->gender == 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                            {{ $pet->gender == 'M' ? '♂ Macho' : '♀ Hembra' }}
                        </span>
                    </div>
                    <p class="text-lg font-medium text-primary-600">{{ $pet->breed ?? 'Raza no especificada' }}</p>
                    <p class="text-sm text-gray-400 uppercase tracking-widest mt-1 font-semibold">{{ $pet->species }}</p>
                </div>

                <!-- Alert Box -->
                <div class="mt-5 bg-red-50 border border-red-100 rounded-2xl p-4 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-2 -mr-2 w-12 h-12 bg-red-100 rounded-full opacity-50"></div>
                    <div class="flex items-start">
                        <div class="shrink-0">
                            <svg class="h-6 w-6 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">¿Me encontraste?</h3>
                            <div class="mt-1 text-sm text-red-700">
                                <p>Por favor ayúdame a regresar a casa contactando a mi familia.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <a href="tel:999999999" class="flex items-center justify-center px-4 py-3 border border-transparent shadow-md text-base font-bold rounded-xl text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        Llamar
                    </a>
                    <a href="https://wa.me/51999999999" target="_blank" class="flex items-center justify-center px-4 py-3 border border-transparent shadow-md text-base font-bold rounded-xl text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        WhatsApp
                    </a>
                </div>

                <!-- Pet Attributes -->
                <div class="mt-5 border-t border-gray-100 pt-4">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Peso</span>
                            <span class="block text-lg font-bold text-gray-800">{{ $pet->weight ?? '--' }} kg</span>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Cumpleaños</span>
                            <span class="block text-lg font-bold text-gray-800">{{ $pet->birth_date ? $pet->birth_date->format('d M Y') : '?' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Medical Notes -->
                @if($pet->medical_notes)
                    <div class="mt-4">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center mb-2">
                            <svg class="h-4 w-4 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Notas Médicas / Alergias
                        </h4>
                        <div class="bg-yellow-50 text-yellow-800 text-sm p-4 rounded-xl border border-yellow-100">
                            {{ $pet->medical_notes }}
                        </div>
                    </div>
                @endif
                
                <!-- Footer -->
                <div class="mt-5 pt-2 border-t border-gray-50 text-center">
                    <p class="text-[10px] text-gray-300 uppercase tracking-wider">Verificado por Mascotas.pe</p>
                    <div class="mt-1 opacity-80 hover:opacity-100 transition duration-500 flex justify-center">
                         <div class="scale-50 origin-top -mb-24">
                            {!! $qrCode !!}
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
