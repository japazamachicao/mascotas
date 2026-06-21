<div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 font-sans">
    <div class="bg-white shadow-xl rounded-2xl border border-gray-150 overflow-hidden flex h-[700px]">
        
        <!-- SIDEBAR: LISTA DE CONVERSACIONES -->
        <div class="w-full md:w-80 lg:w-96 border-r border-gray-250 flex flex-col bg-gray-50/50">
            <!-- Search & Title Header -->
            <div class="p-4 border-b border-gray-200 bg-white">
                <h2 class="text-xl font-black text-gray-900 mb-3 flex items-center gap-2">
                    💬 Mensajes
                </h2>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.250ms="searchQuery" placeholder="Buscar conversación..." 
                           class="w-full pl-9 pr-4 py-2 border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all text-xs">
                </div>
            </div>

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto divide-y divide-gray-100">
                @forelse($conversations as $convo)
                    @php
                        $isActive = $activeConversationId === $convo->id;
                    @endphp
                    <button wire:click="selectConversation({{ $convo->id }})" 
                            class="w-full flex items-center gap-3.5 p-4 text-left transition hover:bg-gray-100/70 {{ $isActive ? 'bg-primary-50/70 border-l-4 border-primary-500 hover:bg-primary-50/90' : 'border-l-4 border-transparent' }}">
                        <!-- Photo -->
                        <div class="shrink-0 relative">
                            <img class="h-11 w-11 rounded-full object-cover border border-gray-200 shadow-sm" 
                                 src="{{ $convo->contact_user->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($convo->contact_user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($convo->contact_user->name).'&background=0ea5e9&color=fff' }}" 
                                 alt="{{ $convo->contact_user->name }}">
                            @if($convo->unread_count > 0)
                                <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-black text-white ring-2 ring-white">
                                    {{ $convo->unread_count }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-black text-gray-900 truncate pr-2">
                                    {{ $convo->contact_user->name }}
                                </p>
                                <span class="text-[9px] font-bold text-gray-400">
                                    {{ $convo->last_message ? $convo->last_message->created_at->format('H:i') : $convo->updated_at->format('d/m') }}
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-500 truncate mt-0.5 font-medium">
                                @if($convo->last_message)
                                    @if($convo->last_message->sender_id === auth()->id())
                                        <span class="text-gray-400">Tú:</span>
                                    @endif
                                    {{ $convo->last_message->body }}
                                @else
                                    <span class="italic text-gray-400">Inicia una conversación</span>
                                @endif
                            </p>
                        </div>
                    </button>
                @empty
                    <div class="p-8 text-center text-gray-400 text-xs italic">
                        No hay conversaciones activas.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- VENTANA DE CONVERSACIÓN -->
        <div class="flex-1 flex flex-col bg-white">
            @if($activeConversationId && $activeContact)
                <!-- Chat Window Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-white shadow-sm z-10">
                    <div class="flex items-center gap-3">
                        <img class="h-10 w-10 rounded-full object-cover border border-gray-150" 
                             src="{{ $activeContact->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($activeContact->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($activeContact->name).'&background=0ea5e9&color=fff' }}" 
                             alt="{{ $activeContact->name }}">
                        <div>
                            <h3 class="text-sm font-black text-gray-900 leading-tight">{{ $activeContact->name }}</h3>
                            <p class="text-[10px] font-bold text-primary-600 uppercase mt-0.5">
                                @if($activeContact->hasRole('veterinarian')) Veterinario
                                @elseif($activeContact->hasRole('walker')) Paseador
                                @elseif($activeContact->hasRole('groomer')) Estilista
                                @elseif($activeContact->hasRole('hotel')) Hotel Canino
                                @elseif($activeContact->hasRole('shelter')) Albergue
                                @elseif($activeContact->hasRole('trainer')) Adiestrador
                                @elseif($activeContact->hasRole('pet_sitter')) Cuidador
                                @elseif($activeContact->hasRole('pet_taxi')) Transporte
                                @elseif($activeContact->hasRole('pet_photographer')) Fotógrafo
                                @else Cliente
                                @endif
                            </p>
                        </div>
                    </div>
                    <div>
                        @if($activeContact->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']))
                            <a href="{{ route('profile.show', $activeContact->id) }}" target="_blank" 
                               class="text-xs font-bold text-gray-500 hover:text-primary-600 transition flex items-center gap-1 border border-gray-200 rounded-lg px-2.5 py-1.5 hover:border-primary-200 bg-gray-50/50">
                                Ver Perfil
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Messages Window -->
                <div x-data="{ 
                         scrollToBottom() {
                             const el = $refs.msgContainer;
                             if(el) { el.scrollTop = el.scrollHeight; }
                         }
                     }"
                     x-init="
                         scrollToBottom();
                         Livewire.on('message-sent', () => { $nextTick(() => scrollToBottom()) });
                     "
                     x-ref="msgContainer"
                     class="flex-1 overflow-y-auto p-6 bg-slate-50/50 space-y-4 flex flex-col">
                    
                    @php
                        $lastDate = null;
                    @endphp

                    @foreach($activeMessages as $msg)
                        @php
                            $msgDate = $msg->created_at->format('d/m/Y');
                            $isMe = $msg->sender_id === auth()->id();
                        @endphp

                        @if($lastDate !== $msgDate)
                            <div class="text-center my-4">
                                <span class="px-2.5 py-1 bg-gray-200/60 rounded-full text-[9px] font-black text-gray-500 uppercase tracking-wider">
                                    {{ $msg->created_at->isToday() ? 'Hoy' : ($msg->created_at->isYesterday() ? 'Ayer' : $msgDate) }}
                                </span>
                            </div>
                            @php
                                $lastDate = $msgDate;
                            @endphp
                        @endif

                        <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }} w-full">
                            <div class="max-w-[70%] rounded-2xl px-4 py-2.5 shadow-sm text-xs font-medium leading-relaxed
                                {{ $isMe ? 'bg-gradient-to-br from-primary-600 to-indigo-600 text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-150 rounded-bl-none' }}">
                                <p class="whitespace-pre-wrap">{{ $msg->body }}</p>
                            </div>
                            <span class="text-[9px] font-bold text-gray-400 mt-1 px-1">
                                {{ $msg->created_at->format('H:i') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <!-- Input area -->
                <div class="p-4 border-t border-gray-200 bg-white">
                    <form wire:submit.prevent="sendMessage" class="flex gap-2 items-center">
                        <textarea wire:model="newMessageBody" 
                                  wire:keydown.enter.prevent="sendMessage"
                                  placeholder="Escribe tu mensaje aquí..." 
                                  rows="1" 
                                  class="flex-1 rounded-xl border border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-xs py-2 px-3 resize-none max-h-24 bg-gray-50/50 focus:bg-white transition"></textarea>
                        
                        <button type="submit" class="p-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold transition shadow-sm shrink-0 flex items-center justify-center">
                            <svg class="h-4.5 w-4.5 transform rotate-90 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </form>
                    @error('newMessageBody') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
            @else
                <!-- Empty State -->
                <div class="flex-1 flex flex-col items-center justify-center p-8 bg-gray-50/30 text-center">
                    <span class="w-16 h-16 rounded-full bg-primary-50 text-primary-600 font-extrabold text-2xl flex items-center justify-center mb-4 shadow-inner">
                        ✉️
                    </span>
                    <h3 class="text-base font-black text-gray-900 mb-1">Tus Conversaciones</h3>
                    <p class="text-xs text-gray-500 max-w-sm leading-relaxed">
                        Selecciona un hilo de chat en la barra lateral para empezar a comunicarte o visita el perfil de un profesional para enviarle un mensaje.
                    </p>
                </div>
            @endif
        </div>

    </div>
</div>
