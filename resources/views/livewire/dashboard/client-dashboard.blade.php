<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Welcome Section -->
        <div class="mb-8 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Hola, {{ Auth::user()->name }} 👋
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Bienvenido a tu panel de control. Aquí puedes gestionar a tus engreídos.
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('dashboard.pet.create') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Agregar Mascota
                </a>
            </div>
        </div>

        <!-- Pets Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($pets as $pet)
                <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <img class="h-16 w-16 rounded-full object-cover" 
                                     src="{{ $pet->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($pet->profile_photo_path) : 'https://ui-avatars.com/api/?name='.$pet->name.'&background=random' }}" 
                                     alt="{{ $pet->name }}">
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        {{ $pet->species }} ({{ $pet->breed }})
                                    </dt>
                                    <dd>
                                        <div class="text-lg font-medium text-gray-900">
                                            {{ $pet->name }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    {{-- Actions --}}
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="mt-4 flex flex-col gap-3">
                            <!-- Quick Actions / Progress -->
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('dashboard.pet.edit', ['pet' => $pet->uuid, 'section' => 'behavior']) }}" 
                                   class="flex-1 inline-flex justify-center items-center px-3 py-1.5 rounded-lg border {{ $pet->behavior ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-gray-200 bg-gray-50 text-gray-500 hover:bg-gray-100' }} text-xs font-medium transition">
                                    <span class="mr-1">🧠</span> {{ $pet->behavior ? 'Carácter' : 'Agregar' }}
                                </a>
                                <a href="{{ route('dashboard.pet.edit', ['pet' => $pet->uuid, 'section' => 'health']) }}" 
                                   class="flex-1 inline-flex justify-center items-center px-3 py-1.5 rounded-lg border {{ $pet->health_features ? 'border-green-200 bg-green-50 text-green-700' : 'border-gray-200 bg-gray-50 text-gray-500 hover:bg-gray-100' }} text-xs font-medium transition">
                                    <span class="mr-1">🩺</span> {{ $pet->health_features ? 'Salud' : 'Agregar' }}
                                </a>
                            </div>

                            <div class="border-t border-gray-100 pt-3 flex justify-between items-center">
                                <a href="{{ route('pet.profile', $pet->uuid) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    Perfil
                                </a>
                                <button wire:click="openQrModal({{ $pet->id }})" class="text-indigo-600 hover:text-indigo-800 text-sm font-bold flex items-center transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Ver QR
                                </button>
                                <a href="{{ route('dashboard.pet.edit', $pet) }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-lg border-2 border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes mascotas registradas</h3>
                    <p class="mt-1 text-sm text-gray-500">Agrega a tu primer engreído para generar su QR.</p>
                    <div class="mt-6">
                        <a href="{{ route('dashboard.pet.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Agregar Mascota
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Modal Placa QR -->
        @if($showQrModal && $selectedPet)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeQrModal"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-2xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                                    <h3 class="text-lg font-bold text-gray-950 flex items-center gap-2">
                                        🏷️ Placa de Identificación QR
                                    </h3>
                                    <button wire:click="closeQrModal" class="text-gray-400 hover:text-gray-500 transition">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="my-6 flex justify-center">
                                    <div id="print-area" class="w-full max-w-sm p-6 bg-gradient-to-br from-indigo-50 via-white to-purple-50 rounded-2xl border-2 border-indigo-100 shadow-md relative overflow-hidden flex flex-col items-center text-center">
                                        <div class="absolute -top-6 -right-6 w-20 h-20 bg-indigo-100 rounded-full opacity-50 blur-xl"></div>
                                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-purple-100 rounded-full opacity-50 blur-xl"></div>

                                        <div class="flex items-center gap-2 mb-4">
                                            <span class="text-indigo-600 shrink-0" title="Mascota">
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="4.5" cy="10.5" r="2.5"/>
                                                    <circle cx="9" cy="6" r="2.5"/>
                                                    <circle cx="15" cy="6" r="2.5"/>
                                                    <circle cx="19.5" cy="10.5" r="2.5"/>
                                                    <path d="M12 10.5c-2.485 0-4.5 2.015-4.5 4.5 0 2.22 1.455 4.103 3.456 4.757l.006.002.5.5.5-.5c2.001-.654 3.456-2.537 3.456-4.759 0-2.485-2.015-4.5-4.5-4.5z"/>
                                                </svg>
                                            </span>
                                            <span class="text-xs font-black uppercase tracking-widest text-indigo-700">TodoPeludos Placa QR</span>
                                        </div>

                                        <div class="flex items-center gap-4 mb-4 text-left w-full justify-center">
                                            <img class="h-14 w-14 rounded-full object-cover border-2 border-indigo-200 shadow-sm shrink-0" 
                                                 src="{{ $selectedPet->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($selectedPet->profile_photo_path) : 'https://ui-avatars.com/api/?name='.$selectedPet->name.'&background=random' }}" 
                                                 alt="{{ $selectedPet->name }}">
                                            <div>
                                                <h4 class="text-lg font-black text-gray-900 leading-tight">{{ $selectedPet->name }}</h4>
                                                <p class="text-xs text-gray-500 font-semibold">{{ $selectedPet->species }} • {{ $selectedPet->breed }}</p>
                                            </div>
                                        </div>

                                        <div class="bg-white p-4 rounded-xl border border-indigo-100 shadow-sm flex items-center justify-center mb-4">
                                             @if($selectedPet->qr_code_path && \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->exists($selectedPet->qr_code_path))
                                                 <img src="{{ \Illuminate\Support\Facades\Storage::url($selectedPet->qr_code_path) }}" class="h-36 w-36 object-contain" alt="Código QR {{ $selectedPet->name }}">
                                             @else
                                                 {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->margin(1)->generate(route('pet.profile', ['uuid' => $selectedPet->uuid])) !!}
                                             @endif
                                        </div>

                                        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-3 w-full">
                                            <p class="text-[11px] font-bold text-indigo-950 leading-relaxed uppercase tracking-wider">
                                                🚨 ¿Me encontraste perdido?
                                            </p>
                                            <p class="text-[10px] font-bold text-indigo-800 mt-0.5">
                                                Escanea este código QR para ver mi perfil y contactar a mi familia de inmediato.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-2">
                                    <button onclick="window.print()" type="button" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto transition">
                                        🖨️ Imprimir Placa
                                    </button>
                                    <button wire:click="closeQrModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto transition">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <style>
            @media print {
                body * {
                    visibility: hidden !important;
                }
                #print-area, #print-area * {
                    visibility: visible !important;
                }
                #print-area {
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%);
                    width: 320px !important;
                    border: 2px solid #e0e7ff !important;
                    padding: 24px !important;
                    background: white !important;
                    border-radius: 16px !important;
                    box-shadow: none !important;
                }
            }
        </style>
    </div>
</div>
