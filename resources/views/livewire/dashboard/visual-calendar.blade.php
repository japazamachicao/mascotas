<div class="space-y-5">
    <!-- Header de Navegación del Calendario -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <div class="text-left">
            <h3 class="text-base font-black text-gray-950 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-violet-100">
                    <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
                Agenda y Bloqueo de Fechas
            </h3>
            <p class="text-xs text-gray-500 mt-1 text-left">
                Visualiza tus citas semanales y bloquea días en los que no estarás disponible para recibir nuevas reservas.
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button wire:click="goToPreviousWeek"
                class="w-8 h-8 border border-gray-200 rounded-xl hover:bg-gray-50 text-gray-600 transition flex items-center justify-center shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button wire:click="goToCurrentWeek"
                class="px-3 py-1.5 border border-violet-200 rounded-xl hover:bg-violet-50 text-xs font-bold text-violet-700 transition shadow-xs">
                Esta Semana
            </button>
            <button wire:click="goToNextWeek"
                class="w-8 h-8 border border-gray-200 rounded-xl hover:bg-gray-50 text-gray-600 transition flex items-center justify-center shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>

    <!-- Rango de Fechas Activo -->
    <div class="flex items-center justify-center gap-2 bg-violet-50 border border-violet-100 rounded-2xl py-3 px-4 shadow-inner">
        <svg class="w-4 h-4 text-violet-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="text-sm font-black text-violet-950 uppercase tracking-wide">
            Semana del {{ $weekStart->translatedFormat('d \d\e F') }} al {{ $weekEnd->translatedFormat('d \d\e F, Y') }}
        </span>
    </div>

    <!-- Mensaje Flash -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="rounded-2xl bg-green-50 p-4 border border-green-200 shadow-sm flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-xs font-semibold text-green-800">{{ session('message') }}</p>
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600 ml-3">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </button>
        </div>
    @endif

    <!-- Calendario Principal Grid -->
    <div class="bg-white rounded-2xl border border-gray-150 shadow-md overflow-hidden">
        <!-- Contenedor Scrollable Horizontal para Móvil -->
        <div class="overflow-x-auto min-w-full">
            <div class="grid grid-cols-8 divide-x divide-gray-150 min-w-[900px] border-b border-gray-150">
                <!-- Columna Vacía para Horas -->
                <div class="bg-gray-50/50 p-3 flex flex-col justify-end text-center pb-4">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Hora</span>
                </div>

                <!-- Cabeceras de Días (7 columnas) -->
                @foreach($daysOfWeek as $day)
                    <div class="p-3 text-center flex flex-col items-center justify-between {{ $day['is_today'] ? 'bg-violet-50/40' : 'bg-gray-50/20' }}">
                        <div class="flex flex-col items-center">
                            <span class="text-[10px] font-bold text-gray-400 uppercase leading-none">{{ $day['short_name'] }}</span>
                            <span class="text-lg font-black mt-1 leading-none {{ $day['is_today'] ? 'text-violet-600 bg-violet-100/70 rounded-full h-8 w-8 flex items-center justify-center' : 'text-gray-900' }}">
                                {{ $day['day_num'] }}
                            </span>
                        </div>

                        <!-- Botón Bloquear / Desbloquear Día -->
                        <div class="mt-3 w-full">
                            @if($day['is_blocked'])
                                <button wire:click="unblockDate('{{ $day['formatted'] }}')"
                                        class="w-full py-1 px-1.5 text-[10px] font-bold bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg transition flex items-center justify-center gap-1 shadow-sm">
                                    <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                    Desbloquear
                                </button>
                            @else
                                <button wire:click="openBlockModal('{{ $day['formatted'] }}')"
                                        class="w-full py-1 px-1.5 text-[10px] font-bold bg-gray-50 hover:bg-gray-100 text-gray-500 border border-gray-200 rounded-lg transition flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zM10 9a2 2 0 114 0v2a2 2 0 01-4 0V9z"/></svg>
                                    Bloquear
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Filas Horarias (08:00 - 20:00) -->
            <div class="divide-y divide-gray-150 min-w-[900px]">
                @foreach($hours as $hour)
                    <div class="grid grid-cols-8 divide-x divide-gray-150 align-stretch">
                        <!-- Celda Horaria -->
                        <div class="p-3 bg-gray-50/20 text-center flex items-center justify-center">
                            <span class="text-xs font-black text-gray-500">{{ $hour }}</span>
                        </div>

                        <!-- Celdas para cada Día en esta hora -->
                        @foreach($daysOfWeek as $day)
                            @php
                                $isBlocked = $day['is_blocked'];
                                $hourApps = $day['appointments_by_hour'][$hour] ?? [];
                            @endphp

                            <div class="p-1 min-h-[70px] relative transition-colors duration-200
                                 {{ $isBlocked ? 'bg-repeating-diagonal-gray bg-gray-100/50' : 'hover:bg-gray-50/50' }}">
                                
                                @if($isBlocked)
                                    <!-- Celda Bloqueada -->
                                    <div class="absolute inset-0 flex items-center justify-center bg-gray-150/20 backdrop-blur-[0.5px]"
                                         title="{{ $day['block_record']->notes ?? 'Día bloqueado manualmente' }}">
                                        <div class="text-center select-none">
                                            <span class="text-[10px] font-bold text-red-500 flex items-center gap-1 bg-white border border-red-100 px-1.5 py-0.5 rounded-lg shadow-sm">
                                                <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zM10 9a2 2 0 114 0v2a2 2 0 01-4 0V9z"/></svg>
                                                Bloqueado
                                            </span>
                                            @if(!empty($day['block_record']->notes))
                                                <span class="block text-[8px] text-gray-400 mt-0.5 truncate max-w-[80px]">
                                                    "{{ $day['block_record']->notes }}"
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <!-- Renderizar citas en esta hora -->
                                    @foreach($hourApps as $app)
                                        @php
                                            $statusClass = [
                                                'pending'   => 'bg-amber-50 border-amber-200 text-amber-800 hover:bg-amber-100/70',
                                                'confirmed' => 'bg-indigo-50 border-indigo-200 text-indigo-800 hover:bg-indigo-100/70',
                                                'completed' => 'bg-green-50 border-green-200 text-green-800 hover:bg-green-100/70',
                                            ][$app->status] ?? 'bg-gray-50 border-gray-200 text-gray-800';
                                        @endphp
                                        <button wire:click.prevent="$parent.openAppointmentModal({{ $app->id }})" 
                                           class="w-full text-left block p-1.5 rounded-lg border text-[10px] leading-tight font-medium shadow-sm transition {{ $statusClass }} mb-1 cursor-pointer">
                                            <div class="flex items-center justify-between font-bold">
                                                <span class="truncate pr-1">{{ $app->client->name }}</span>
                                                <span class="text-[8px] shrink-0 font-extrabold uppercase">
                                                    {{ $app->status === 'pending' ? 'Pend' : ($app->status === 'confirmed' ? 'Conf' : 'Comp') }}
                                                </span>
                                            </div>
                                            <div class="text-[8.5px] text-gray-500 truncate mt-0.5 flex items-center gap-0.5">
                                                <svg class="w-2 h-2 inline shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M4.5 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM14.25 8.625a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0zM1.5 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM17.25 19.128l-.001.144a2.25 2.25 0 01-.233.96 10.088 10.088 0 005.06-1.01.75.75 0 00.42-.643 4.875 4.875 0 00-6.957-4.611 8.586 8.586 0 011.71 5.157v.003z"/></svg>
                                                {{ $app->pet->name }}
                                            </div>
                                        </button>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Leyenda de colores -->
    <div class="flex flex-wrap gap-4 items-center bg-gray-50 p-4 rounded-2xl border border-gray-200 shadow-sm text-xs font-semibold text-gray-600">
        <span class="font-bold text-gray-800">Leyenda:</span>
        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-amber-100 border border-amber-300"></span> Pendiente</span>
        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-indigo-100 border border-indigo-300"></span> Confirmada</span>
        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-green-100 border border-green-300"></span> Completada</span>
        <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 rounded bg-gray-200 bg-repeating-diagonal-gray border border-gray-300"></span> Bloqueado</span>
    </div>

    <!-- Modal para Bloqueo de Fecha -->
    @if($showBlockModal)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeBlockModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative z-10 inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full">
                    <!-- Header del modal -->
                    <div class="bg-gradient-to-r from-red-600 to-orange-600 px-6 pt-6 pb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zM10 9a2 2 0 114 0v2a2 2 0 01-4 0V9z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-white" id="modal-title">Bloquear Fecha</h3>
                                <p class="text-red-100 text-xs mt-0.5">
                                    {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d \d\e F Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Cuerpo -->
                    <div class="bg-white px-6 pt-5 pb-4">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Los clientes <strong class="text-gray-900">no podrán agendar citas</strong> en este día. Puedes desbloquear el día en cualquier momento.
                        </p>
                        <div class="mt-4">
                            <label class="block text-[10px] font-black text-gray-700 uppercase tracking-wider mb-1.5">Notas / Motivo (Opcional)</label>
                            <input type="text" wire:model="blockNotes"
                                placeholder="Ej: Trámite médico, vacaciones, etc." 
                                class="w-full rounded-xl border-gray-200 focus:ring-2 focus:ring-red-100 focus:border-red-400 text-sm py-2.5 px-3 bg-gray-50/50">
                            @error('blockNotes') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <!-- Botones -->
                    <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                        <button type="button" wire:click="closeBlockModal"
                            class="w-full sm:w-auto inline-flex justify-center items-center gap-1.5 px-4 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Cancelar
                        </button>
                        <button type="button" wire:click="blockDate"
                            class="w-full sm:w-auto inline-flex justify-center items-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition shadow-md">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zM10 9a2 2 0 114 0v2a2 2 0 01-4 0V9z"/></svg>
                            Bloquear Fecha
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Estilo CSS personalizado para la trama de bloqueo -->
    <style>
        .bg-repeating-diagonal-gray {
            background-image: repeating-linear-gradient(
                45deg,
                #f3f4f6,
                #f3f4f6 5px,
                #e5e7eb 5px,
                #e5e7eb 10px
            );
        }
    </style>
</div>
