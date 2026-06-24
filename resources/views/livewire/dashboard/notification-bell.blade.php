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
        <div class="px-4 py-3 bg-gray-50 flex items-center justify-between">
            <span class="text-xs font-black text-gray-900">Notificaciones</span>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" 
                        class="text-[10px] font-bold text-primary-600 hover:text-primary-800 transition">
                    Marcar todo como leído
                </button>
            @endif
        </div>

        <!-- Lista -->
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
            @forelse($notifications as $notif)
                @php
                    $type = $notif->data['type'] ?? '';
                    $title = $notif->data['title'] ?? 'Nueva actualización';
                    $body = $notif->data['body'] ?? '';
                @endphp
                <button wire:click="handleNotificationClick('{{ $notif->id }}')" 
                        class="w-full text-left p-3.5 hover:bg-gray-50/80 transition flex gap-3 items-start">
                    
                    <!-- Icono según tipo -->
                    <div class="shrink-0 mt-0.5">
                        @if($type === 'chat_message')
                            <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-sm">💬</span>
                        @elseif($type === 'appointment_booked')
                            <span class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-sm">📅</span>
                        @elseif($type === 'appointment_status_changed')
                            <span class="w-8 h-8 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-sm">🔔</span>
                        @else
                            <span class="w-8 h-8 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center text-sm">📢</span>
                        @endif
                    </div>

                    <!-- Detalles -->
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 leading-tight">
                            {{ $title }}
                        </p>
                        @if($body)
                            <p class="text-[10px] text-gray-500 truncate mt-0.5 font-medium">
                                {{ $body }}
                            </p>
                        @endif
                        <span class="text-[8.5px] font-bold text-gray-400 block mt-1">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>
                </button>
            @empty
                <div class="p-8 text-center text-gray-400 text-xs italic">
                    <span class="block text-xl mb-1.5">📭</span>
                    No tienes notificaciones pendientes.
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="px-4 py-2 bg-gray-50 text-center">
            @if(Auth::user()->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']))
                <a href="{{ route('dashboard.provider', ['section' => 'appointments']) }}" class="text-[10px] font-bold text-gray-500 hover:text-primary-600 transition">
                    Ver todas mis citas
                </a>
            @else
                <a href="{{ route('dashboard.appointments') }}" class="text-[10px] font-bold text-gray-500 hover:text-primary-600 transition">
                    Ver todas mis citas
                </a>
            @endif
        </div>
    </div>
</div>
