<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <!-- Botón de la Campana -->
    <button @click="open = !open" 
            class="relative p-2 text-gray-400 hover:text-primary-600 focus:outline-none transition-colors duration-200 rounded-full hover:bg-gray-100/80">
        <span class="sr-only">Ver notificaciones</span>
        <!-- Icono de Campana SVG -->
        <svg class="h-6.5 w-6.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Badge de No Leídos con Animación Ping -->
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[9px] font-black text-white items-center justify-center">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            </span>
        @endif
    </button>

    <!-- Dropdown de Notificaciones -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2.5 w-80 bg-white rounded-2xl shadow-xl border border-gray-150 overflow-hidden z-50 origin-top-right divide-y divide-gray-100"
         style="display: none;">
        
        <!-- Header -->
        <div class="px-4.5 py-3.5 bg-gray-50/80 backdrop-blur-xs flex items-center justify-between border-b border-gray-100">
            <span class="text-xs font-black text-gray-900 tracking-wide uppercase">Notificaciones</span>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" 
                        class="text-[10.5px] font-black text-primary-600 hover:text-primary-800 transition-colors duration-150">
                    Marcar todo como leído
                </button>
            @endif
        </div>

        <!-- Lista -->
        <div class="max-h-85 overflow-y-auto divide-y divide-gray-100">
            @forelse($notifications as $notif)
                @php
                    $type = $notif->data['type'] ?? '';
                    $title = $notif->data['title'] ?? 'Nueva actualización';
                    $body = $notif->data['body'] ?? '';
                @endphp
                <button wire:click="handleNotificationClick('{{ $notif->id }}')" 
                        class="w-full text-left p-3.5 hover:bg-primary-50/20 transition-all duration-150 flex gap-3.5 items-start group">
                    
                    <!-- Icono según tipo -->
                    <div class="shrink-0 mt-0.5 transition-transform duration-200 group-hover:scale-105">
                        @if($type === 'chat_message')
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-650 flex items-center justify-center border border-blue-100/60 shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                        @elseif($type === 'appointment_booked')
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-650 flex items-center justify-center border border-emerald-100/60 shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @elseif($type === 'appointment_status_changed')
                            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-650 flex items-center justify-center border border-amber-100/60 shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        @else
                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-650 flex items-center justify-center border border-purple-100/60 shadow-xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Detalles -->
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 leading-tight group-hover:text-primary-700 transition-colors duration-150">
                            {{ $title }}
                        </p>
                        @if($body)
                            <p class="text-[10px] text-gray-500 truncate mt-0.5 font-medium leading-relaxed">
                                {{ $body }}
                            </p>
                        @endif
                        <span class="text-[9px] font-bold text-gray-400 block mt-1 tracking-wide uppercase">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>
                </button>
            @empty
                <div class="p-8 flex flex-col items-center justify-center text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50/80 flex items-center justify-center mb-3 shadow-inner border border-gray-100/50">
                        <svg class="w-6.5 h-6.5 text-gray-450" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-3.586-3.586a2 2 0 00-2.828 0L12 14m0 0l-1.586-1.586a2 2 0 00-2.828 0L4 14" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-gray-900">Bandeja al día</p>
                    <p class="text-[10px] text-gray-500 mt-1 max-w-[190px] leading-normal font-medium">No tienes notificaciones pendientes en este momento.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50/50 text-center border-t border-gray-100">
            @if(Auth::check() && Auth::user()->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']))
                <a href="{{ route('dashboard.provider', ['section' => 'appointments']) }}" class="text-[10px] font-bold text-gray-500 hover:text-primary-650 transition-colors duration-150 inline-flex items-center gap-1">
                    Ver todas mis citas
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            @else
                <a href="{{ route('dashboard.appointments') }}" class="text-[10px] font-bold text-gray-500 hover:text-primary-650 transition-colors duration-150 inline-flex items-center gap-1">
                    Ver todas mis citas
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            @endif
        </div>
    </div>
</div>
