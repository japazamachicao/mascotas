<div class="py-10">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Mis Citas</h2>
                <p class="text-sm text-gray-500 mt-1">Aquí puedes ver el historial y estado de tus citas solicitadas.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">← Volver a Mis Mascotas</a>
        </div>

        @if(session('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-4 py-3 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-6 bg-red-50 border border-red-200 text-red-800 text-sm font-medium px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabs de estado -->
        <div class="flex gap-2 mb-6 flex-wrap">
            @foreach(['all' => 'Todas', 'pending' => 'Pendientes', 'confirmed' => 'Confirmadas', 'completed' => 'Completadas', 'cancelled' => 'Canceladas', 'payment_pending' => 'Por Pagar'] as $status => $label)
                @php
                    $badge = $counts[$status] ?? null;
                    $isActive = $filterStatus === $status;
                @endphp
                <button wire:click="$set('filterStatus', '{{ $status }}')"
                    class="px-4 py-2 rounded-lg text-sm font-semibold border transition
                        {{ $isActive
                            ? ($status === 'payment_pending' ? 'bg-amber-600 text-white border-amber-600 shadow-sm' : 'bg-primary-600 text-white border-primary-600 shadow-sm')
                            : ($status === 'payment_pending' && $badge > 0
                                ? 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100/70'
                                : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50') }}">
                    {{ $label }}
                    @if($badge !== null && $badge > 0)
                        <span class="ml-1.5 {{ $isActive ? ($status === 'payment_pending' ? 'text-amber-700 bg-white' : 'text-primary-700 bg-white') : ($status === 'payment_pending' ? 'bg-amber-200 text-amber-900' : 'bg-gray-100 text-gray-700') }} text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $badge }}</span>
                    @endif
                </button>
            @endforeach
        </div>

        <!-- Filtros y Búsqueda Premium -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm mb-6 p-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <!-- Buscar por Proveedor -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Buscar Proveedor</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="searchProvider" placeholder="Nombre del profesional..." class="w-full pl-9 py-2 border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all duration-200 text-sm shadow-sm hover:border-gray-300">
                    </div>
                </div>

                <!-- Filtrar por Mascota -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Mascota</label>
                    <div class="relative group">
                        <select wire:model.live="filterPetId" class="w-full py-2 px-3 border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all duration-200 text-sm shadow-sm cursor-pointer hover:border-gray-300">
                            <option value="">Todas las Mascotas</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->id }}">{{ $pet->name }} ({{ $pet->species }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Filtrar por Fecha -->
                <div class="flex gap-2 items-end">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Fecha de Cita</label>
                        <input type="date" wire:model.live="filterDate" class="w-full py-2 px-3 border-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-primary-100 focus:border-primary-500 transition-all duration-200 text-sm shadow-sm hover:border-gray-300">
                    </div>
                    @if($searchProvider || $filterPetId || $filterDate)
                        <button wire:click="resetFilters" class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition shadow-sm text-xs font-bold shrink-0" title="Limpiar Filtros">
                            Limpiar
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Lista de citas -->
        <div class="space-y-4">
            @forelse($appointments as $apt)
                @php
                    $providerProfile = $apt->provider->provider_profile;
                    $whatsapp = $providerProfile->whatsapp_number ?? null;
                    $waLink = null;
                    if ($whatsapp) {
                        $phone = preg_replace('/\D/', '', $whatsapp);
                        if (strlen($phone) === 9) {
                            $phone = '51' . $phone;
                        }
                        $date = $apt->scheduled_at->format('d/m/Y H:i');
                        $msg = urlencode("Hola {$apt->provider->name}, tengo una cita programada para el {$date} a través de TodoPeludos.com. Quería realizar una consulta.");
                        $waLink = "https://wa.me/{$phone}?text={$msg}";
                    }
                @endphp
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5" x-data="{ confirmingCancel: false }">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

                        <!-- Info proveedor -->
                        <div class="flex items-start gap-4 flex-1">
                            <img src="{{ $apt->provider->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($apt->provider->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($apt->provider->name).'&background=0ea5e9&color=fff&size=48' }}"
                                 class="w-12 h-12 rounded-full object-cover shrink-0" alt="{{ $apt->provider->name }}">
                            <div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ $apt->provider->profileUrl() }}" class="font-bold text-gray-900 text-base hover:text-primary-600 transition">
                                        {{ $apt->provider->name }}
                                    </a>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        @if($apt->provider->hasRole('veterinarian')) Veterinario
                                        @elseif($apt->provider->hasRole('walker')) Paseador
                                        @elseif($apt->provider->hasRole('groomer')) Estilista
                                        @elseif($apt->provider->hasRole('hotel')) Hotel
                                        @elseif($apt->provider->hasRole('shelter')) Albergue
                                        @elseif($apt->provider->hasRole('trainer')) Adiestrador
                                        @elseif($apt->provider->hasRole('pet_sitter')) Cuidador
                                        @elseif($apt->provider->hasRole('pet_taxi')) Transporte
                                        @elseif($apt->provider->hasRole('pet_photographer')) Fotógrafo
                                        @endif
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">{{ $apt->provider->email }}</p>
                                @if($apt->pet)
                                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                        <span class="text-primary-600 shrink-0" title="Mascota">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="4.5" cy="10.5" r="2.5"/>
                                                <circle cx="9" cy="6" r="2.5"/>
                                                <circle cx="15" cy="6" r="2.5"/>
                                                <circle cx="19.5" cy="10.5" r="2.5"/>
                                                <path d="M12 10.5c-2.485 0-4.5 2.015-4.5 4.5 0 2.22 1.455 4.103 3.456 4.757l.006.002.5.5.5-.5c2.001-.654 3.456-2.537 3.456-4.759 0-2.485-2.015-4.5-4.5-4.5z"/>
                                            </svg>
                                        </span>
                                        Mascota: <span class="font-semibold text-gray-600">{{ $apt->pet->name }}</span> ({{ $apt->pet->species }})
                                    </p>
                                @endif
                                @if($apt->notes)
                                    <p class="text-sm text-gray-600 mt-2 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 italic font-medium">"{{ $apt->notes }}"</p>
                                @endif
                                @if($apt->payment && $apt->payment->description)
                                    <div class="mt-2.5 p-3 bg-indigo-50/40 rounded-xl border border-indigo-100 text-xs text-left max-w-md">
                                        <p class="font-bold text-indigo-900 mb-1.5 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <rect width="20" height="14" x="2" y="5" rx="2" />
                                                <path d="M2 10h20" />
                                            </svg>
                                            Desglose de Cobros:
                                        </p>
                                        @php
                                            $decodedAptCharges = json_decode($apt->payment->description, true);
                                        @endphp
                                        @if(is_array($decodedAptCharges))
                                            <div class="space-y-1 bg-white p-2 rounded-lg border border-indigo-100/50">
                                                @foreach($decodedAptCharges as $c)
                                                    <div class="flex justify-between text-[11px] text-gray-700 py-0.5 border-b border-gray-100 last:border-0">
                                                        <span>{{ $c['concept'] }}</span>
                                                        <span class="font-bold text-gray-900">S/ {{ number_format($c['amount'], 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-750 bg-white p-2.5 rounded-lg border border-indigo-100/50 leading-relaxed font-medium">{{ $apt->payment->description }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Fecha y estado -->
                        <div class="shrink-0 flex flex-col sm:items-end gap-3">
                            <div class="sm:text-right">
                                <p class="text-sm font-bold text-gray-800">{{ $apt->scheduled_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $apt->scheduled_at->format('H:i') }} hrs</p>
                            </div>

                            @php
                                $statusStyles = [
                                    'pending'   => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'completed' => 'bg-green-50 text-green-700 border-green-200',
                                    'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                ];
                                $statusLabels = [
                                    'pending'   => 'Pendiente',
                                    'confirmed' => 'Confirmada',
                                    'completed' => 'Completada',
                                    'cancelled' => 'Cancelada',
                                ];
                            @endphp
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full border self-start sm:self-auto {{ $statusStyles[$apt->status] ?? '' }}">
                                {{ $statusLabels[$apt->status] ?? $apt->status }}
                            </span>

                            @if($apt->payment)
                                @php
                                    $payStatusStyles = [
                                        'pending'      => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'under_review' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'completed'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'failed'       => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                    $payStatusLabels = [
                                        'pending'      => 'Pago Pendiente',
                                        'under_review' => 'Pago En Revisión',
                                        'completed'    => 'Pago Completado',
                                        'failed'       => 'Pago Fallido',
                                    ];
                                @endphp
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full border self-start sm:self-auto inline-flex items-center gap-1 {{ $payStatusStyles[$apt->payment->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                    <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <rect width="20" height="14" x="2" y="5" rx="2" />
                                        <path d="M2 10h20" />
                                    </svg>
                                    {{ $payStatusLabels[$apt->payment->status] ?? $apt->payment->status }}
                                </span>
                            @endif

                            <!-- Acciones -->
                            <div class="flex gap-2 items-center">
                                @if(in_array($apt->status, ['confirmed', 'completed']) && $apt->payment && in_array($apt->payment->status, ['pending', 'failed']))
                                    <button wire:click="openPaymentModal({{ $apt->id }})"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition">
                                        Pagar Cita (S/ {{ number_format($apt->payment->amount, 2) }})
                                    </button>
                                @endif

                                @if(in_array($apt->status, ['pending', 'confirmed']))
                                    <button @click="confirmingCancel = true" x-show="!confirmingCancel"
                                        class="px-3 py-1.5 bg-white border border-red-200 text-red-600 text-xs font-bold rounded-lg hover:bg-red-50 transition">
                                        Cancelar Cita
                                    </button>
                                @endif

                                @if($waLink)
                                    <a href="{{ $waLink }}"
                                       target="_blank"
                                       class="px-3 py-1.5 bg-green-500 text-white text-xs font-bold rounded-lg hover:bg-green-600 transition flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Banner de Pago Requerido/Pendiente -->
                    @if(in_array($apt->status, ['confirmed', 'completed']) && $apt->payment && in_array($apt->payment->status, ['pending', 'failed']))
                        <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-[0_2px_4px_rgba(217,119,6,0.03)]">
                            <div class="flex items-start gap-2.5 text-amber-800">
                                <span class="text-lg leading-none mt-0.5">⚠️</span>
                                <div>
                                    <h5 class="font-extrabold text-amber-900 text-sm">Monto Pendiente de Pago</h5>
                                    <p class="mt-0.5 text-xs text-amber-700 leading-relaxed font-medium">Esta cita está {{ $apt->status === 'completed' ? 'completada' : 'confirmada' }} pero el pago aún no se ha completado. Por favor, selecciona "Pagar Ahora" para ver las opciones de Yape o Plin.</p>
                                </div>
                            </div>
                            <button wire:click="openPaymentModal({{ $apt->id }})"
                                class="shrink-0 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-black rounded-lg shadow-sm transition hover:scale-[1.02] flex items-center justify-center gap-1.5">
                                💳 Pagar Ahora (S/ {{ number_format($apt->payment->amount, 2) }})
                            </button>
                        </div>
                    @endif

                    <!-- Modal de confirmación de cancelación -->
                    <div x-show="confirmingCancel" x-transition class="mt-4 bg-red-50 border border-red-200 rounded-xl p-4" style="display: none;">
                        <p class="text-sm text-red-800 font-semibold mb-3">¿Confirmas que quieres cancelar esta cita?</p>
                        <div class="flex gap-2">
                            <button wire:click="cancelAppointment({{ $apt->id }})"
                                class="px-4 py-1.5 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700">
                                Sí, cancelar
                            </button>
                            <button @click="confirmingCancel = false" type="button"
                                class="px-4 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50">
                                No, volver
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4 text-gray-500 font-medium">No tienes citas registradas.</p>
                    <div class="mt-4">
                        <a href="{{ route('search') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-md text-white bg-primary-600 hover:bg-primary-700">
                            Buscar Proveedores
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $appointments->links() }}
        </div>

        <!-- Payment Modal -->
        @if($showPaymentModal && $selectedAppointment)
            <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-data="{ localPaymentMethod: @entangle('paymentMethod') }">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true" wire:click="$set('showPaymentModal', false)"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div class="relative z-10 inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-gray-100">
                        <div class="bg-white px-6 pt-6 pb-6">
                            <div class="flex items-start justify-between pb-4 border-b">
                                <h3 class="text-lg font-bold text-gray-900" id="modal-title">Realizar Pago</h3>
                                <button wire:click="$set('showPaymentModal', false)" class="text-gray-400 hover:text-gray-600">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            
                            <div class="mt-4 space-y-4">
                                <div class="bg-primary-50 p-4 rounded-xl flex justify-between items-center">
                                    <span class="text-sm font-semibold text-primary-800">Monto a pagar:</span>
                                    <span class="text-xl font-extrabold text-primary-600">S/ {{ number_format($selectedAppointment->payment->amount, 2) }}</span>
                                </div>

                                @if($selectedAppointment->payment->description)
                                    <div class="bg-indigo-50/40 p-4 rounded-xl border border-indigo-100 text-xs">
                                        <p class="font-bold text-indigo-900 mb-1.5 flex items-center gap-1.5 text-left">
                                            <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <rect width="20" height="14" x="2" y="5" rx="2" />
                                                <path d="M2 10h20" />
                                            </svg>
                                            Detalle de los Cobros:
                                        </p>
                                        @php
                                            $decodedModalCharges = json_decode($selectedAppointment->payment->description, true);
                                        @endphp
                                        @if(is_array($decodedModalCharges))
                                            <div class="space-y-1.5 bg-white p-2.5 rounded-lg border border-indigo-100/50">
                                                @foreach($decodedModalCharges as $c)
                                                    <div class="flex justify-between text-[11px] text-gray-700 py-0.5 border-b border-gray-100 last:border-0">
                                                        <span class="text-left">{{ $c['concept'] }}</span>
                                                        <span class="font-bold text-gray-900">S/ {{ number_format($c['amount'], 2) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-700 bg-white p-2.5 rounded-lg border border-indigo-100/50 text-left font-medium leading-relaxed">
                                                {{ $selectedAppointment->payment->description }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                <!-- Métodos de pago selector -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Selecciona método de pago</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <!-- Yape -->
                                        <label class="flex flex-col items-center justify-center p-3 border rounded-xl cursor-pointer transition duration-200 select-none shadow-sm"
                                            :class="localPaymentMethod === 'yape' ? 'border-purple-600 bg-purple-50/40 text-purple-900 shadow-sm' : 'border-gray-200 bg-white hover:bg-gray-50/50 text-gray-500 hover:text-gray-700'">
                                            <input type="radio" name="payment_method_sel" wire:model.live="paymentMethod" value="yape" class="sr-only">
                                            <span class="text-xl mb-1 filter drop-shadow-sm">🟣</span>
                                            <span class="text-xs font-black">Yape</span>
                                        </label>

                                        <!-- Plin -->
                                        <label class="flex flex-col items-center justify-center p-3 border rounded-xl cursor-pointer transition duration-200 select-none shadow-sm"
                                            :class="localPaymentMethod === 'plin' ? 'border-teal-600 bg-teal-50/40 text-teal-900 shadow-sm' : 'border-gray-200 bg-white hover:bg-gray-50/50 text-gray-500 hover:text-gray-700'">
                                            <input type="radio" name="payment_method_sel" wire:model.live="paymentMethod" value="plin" class="sr-only">
                                            <span class="text-xl mb-1 filter drop-shadow-sm">🟢</span>
                                            <span class="text-xs font-black">Plin</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Secciones específicas por método -->
                                <div x-show="localPaymentMethod === 'yape'" class="space-y-4 pt-2" style="display: none;">
                                    <div class="bg-purple-50/40 p-5 rounded-2xl border border-purple-100 space-y-4">
                                        <div class="flex items-center gap-2 text-purple-900 font-extrabold text-sm border-b border-purple-100/50 pb-2">
                                            <span>🟣</span>
                                            <span>Pagar con Yape</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-3">
                                            @if($selectedAppointment->provider->yape_qr_path)
                                                <div class="p-2.5 bg-white rounded-xl shadow-sm border border-purple-100/50">
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($selectedAppointment->provider->yape_qr_path) }}" class="h-40 w-40 object-contain">
                                                </div>
                                                <span class="text-[10px] text-purple-600 font-extrabold tracking-wide uppercase">1. Escanea este código QR en tu app Yape</span>
                                            @else
                                                <div class="p-4 border border-dashed border-purple-200 rounded-xl text-xs text-purple-600 italic bg-white text-center w-full">
                                                    El proveedor no registró su código QR de Yape.
                                                </div>
                                            @endif
                                            
                                            <div class="bg-white rounded-xl px-4 py-2.5 border border-purple-100 w-full text-center shadow-sm">
                                                <span class="block text-[9px] text-gray-400 font-bold uppercase tracking-wider">Número de Celular</span>
                                                <span class="text-lg font-black text-purple-950">{{ $selectedAppointment->provider->yape_number ?? 'No registrado' }}</span>
                                            </div>
                                            <span class="text-[10px] text-purple-600 font-extrabold tracking-wide uppercase">2. O yapea al número del proveedor</span>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="localPaymentMethod === 'plin'" class="space-y-4 pt-2" style="display: none;">
                                    <div class="bg-teal-50/40 p-5 rounded-2xl border border-teal-100 space-y-4">
                                        <div class="flex items-center gap-2 text-teal-900 font-extrabold text-sm border-b border-teal-100/50 pb-2">
                                            <span>🟢</span>
                                            <span>Pagar con Plin</span>
                                        </div>
                                        <div class="flex flex-col items-center gap-3">
                                            @if($selectedAppointment->provider->plin_qr_path)
                                                <div class="p-2.5 bg-white rounded-xl shadow-sm border border-teal-100/50">
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($selectedAppointment->provider->plin_qr_path) }}" class="h-40 w-40 object-contain">
                                                </div>
                                                <span class="text-[10px] text-teal-600 font-extrabold tracking-wide uppercase">1. Escanea este código QR desde tu app financiera</span>
                                            @else
                                                <div class="p-4 border border-dashed border-teal-200 rounded-xl text-xs text-teal-600 italic bg-white text-center w-full">
                                                    El proveedor no registró su código QR de Plin.
                                                </div>
                                            @endif
                                            
                                            <div class="bg-white rounded-xl px-4 py-2.5 border border-teal-100 w-full text-center shadow-sm">
                                                <span class="block text-[9px] text-gray-400 font-bold uppercase tracking-wider">Número de Celular</span>
                                                <span class="text-lg font-black text-teal-950">{{ $selectedAppointment->provider->plin_number ?? 'No registrado' }}</span>
                                            </div>
                                            <span class="text-[10px] text-teal-600 font-extrabold tracking-wide uppercase">2. O realiza Plin al número del proveedor</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Formulario de subida de recibo para Yape/Plin -->
                                <div x-show="localPaymentMethod === 'yape' || localPaymentMethod === 'plin'" class="space-y-4" style="display: none;">
                                    <div class="border-t border-gray-100 pt-4">
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Comprobante de Pago</label>
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center justify-center w-full">
                                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-200 border-dashed rounded-xl cursor-pointer hover:bg-gray-50/50 transition-all duration-200">
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                        <svg class="w-8 h-8 mb-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <p class="text-xs text-gray-500 font-extrabold">Sube la captura del comprobante</p>
                                                        <p class="text-[10px] text-gray-400 mt-1">PNG, JPG, JPEG (máx. 10MB)</p>
                                                    </div>
                                                    <input type="file" wire:model.live="receiptPhoto" class="hidden">
                                                </label>
                                            </div>
                                            @error('receiptPhoto') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                                            
                                            @if($receiptPhoto)
                                                <div class="mt-1 flex items-center justify-between p-2.5 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 text-xs font-semibold">
                                                    <span class="truncate pr-4">📸 {{ $receiptPhoto->getClientOriginalName() }}</span>
                                                    <span class="text-[9px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full font-black uppercase tracking-wider shrink-0">Cargado</span>
                                                </div>
                                            @endif

                                            <div wire:loading wire:target="receiptPhoto" class="text-xs text-primary-600 font-semibold mt-1">
                                                Cargando imagen...
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Código de Operación (Opcional)</label>
                                        <input type="text" wire:model="operationCode" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm text-sm" placeholder="Ej: 123456">
                                    </div>

                                    <button wire:click="submitManualPayment" class="w-full py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition duration-250">
                                        Enviar Comprobante
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <script src="https://checkout.culqi.com/js/v4"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                let currentAmount = 0;
                let currentDescription = '';
                let currentEmail = '';
                let currentAppointmentId = null;

                Livewire.on('openCulqiCheckout', (data) => {
                    let params = data[0];
                    currentAmount = params.amount;
                    currentEmail = params.email;
                    currentDescription = params.description;
                    currentAppointmentId = params.appointmentId;

                    // Configure Culqi Settings
                    Culqi.publicKey = '{{ config('services.culqi.public_key') }}';
                    Culqi.settings({
                        title: 'TodoPeludos',
                        currency: 'PEN',
                        amount: currentAmount,
                        description: currentDescription
                    });

                    Culqi.options({
                        lang: 'auto',
                        installments: false,
                        customButton: 'Pagar S/ ' + (currentAmount / 100).toFixed(2),
                        style: {
                            logo: 'https://todopeludos.com/logo.png',
                        }
                    });
                });

                // Handle the payment button click inside the modal (since Culqi SDK is initialized, we open it)
                document.addEventListener('click', function (e) {
                    if (e.target && e.target.id === 'btn-pay-culqi' || e.target.closest('#btn-pay-culqi')) {
                        Culqi.open();
                    }
                });

                // Set up globally-scoped culqi callback for checkout success
                window.culqi = function () {
                    if (Culqi.token) {
                        var token = Culqi.token.id;
                        var email = Culqi.token.email;
                        
                        // Dispatch to Livewire component to charge in backend
                        Livewire.dispatch('culqiPaymentSuccess', {
                            token: token,
                            email: email,
                            appointmentId: currentAppointmentId
                        });
                    } else {
                        console.error(Culqi.error);
                        alert(Culqi.error.user_message || 'Ocurrió un error al procesar el pago.');
                    }
                };
            });
        </script>

    </div>
</div>
