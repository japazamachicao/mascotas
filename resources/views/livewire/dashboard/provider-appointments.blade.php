<div class="py-10">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Mis Citas</h2>
                <p class="text-sm text-gray-500 mt-1">Gestiona las solicitudes de tus clientes.</p>
            </div>
            <a href="{{ route('dashboard.provider') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">← Volver al Panel</a>
        </div>

        @if(session('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm font-medium px-4 py-3 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        <!-- Tabs de estado -->
        <div class="flex gap-2 mb-6 flex-wrap">
            @foreach(['pending' => 'Pendientes', 'confirmed' => 'Confirmadas', 'completed' => 'Completadas', 'cancelled' => 'Canceladas', 'all' => 'Todas'] as $status => $label)
                @php
                    $badge = $counts[$status] ?? null;
                    $isActive = $filterStatus === $status;
                @endphp
                <button wire:click="$set('filterStatus', '{{ $status }}')"
                    class="px-4 py-2 rounded-lg text-sm font-semibold border transition
                        {{ $isActive
                            ? 'bg-primary-600 text-white border-primary-600 shadow-sm'
                            : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                    {{ $label }}
                    @if($badge !== null && $badge > 0)
                        <span class="ml-1.5 {{ $isActive ? 'bg-white text-primary-700' : 'bg-gray-100 text-gray-700' }} text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $badge }}</span>
                    @endif
                </button>
            @endforeach
        </div>

        <!-- Lista de citas -->
        <div class="space-y-4">
            @forelse($appointments as $apt)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

                        <!-- Info cliente -->
                        <div class="flex items-start gap-4 flex-1">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($apt->client->name) }}&background=0ea5e9&color=fff&size=48"
                                 class="w-12 h-12 rounded-full shrink-0" alt="{{ $apt->client->name }}">
                            <div>
                                <p class="font-bold text-gray-900 text-base">{{ $apt->client->name }}</p>
                                <p class="text-sm text-gray-500">{{ $apt->client->email }}</p>
                                @if($apt->pet)
                                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                        <span class="text-primary-600 shrink-0" title="Mascota">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="4.5" cy="10.5" r="2.5"/>
                                                <circle cx="9" cy="6" r="2.5"/>
                                                <circle cx="15" cy="6" r="2.5"/>
                                                <circle cx="19.5" cy="10.5" r="2.5"/>
                                                <path d="M12 10.5c-2.485 0-4.5 2.015-4.5 4.5 0 2.22 1.455 4.103 3.456 4.757l.006.002.5.5.5-.5c2.001-.654 3.456-2.537 3.456-4.759 0-2.485-2.015-4.5-4.5-4.5z"/>
                                            </svg>
                                        </span>
                                        {{ $apt->pet->name }} ({{ $apt->pet->species }})
                                    </p>
                                @endif
                                @if($apt->notes)
                                    <p class="text-sm text-gray-600 mt-2 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 italic">"{{ $apt->notes }}"</p>
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
                                            $decodedProvCharges = json_decode($apt->payment->description, true);
                                        @endphp
                                        @if(is_array($decodedProvCharges))
                                            <div class="space-y-1 bg-white p-2 rounded-lg border border-indigo-100/50">
                                                @foreach($decodedProvCharges as $c)
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

                                @if($apt->payment && $apt->payment->status === 'under_review')
                                    <div class="mt-4 p-4 bg-indigo-50/50 rounded-xl border border-indigo-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div class="space-y-1">
                                            <p class="text-xs font-bold text-indigo-900 uppercase tracking-wider">Verificación de Pago ({{ strtoupper($apt->payment->payment_method) }})</p>
                                            @if($apt->payment->transaction_reference)
                                                <p class="text-xs text-gray-600">Código de operación: <span class="font-bold text-gray-900">{{ $apt->payment->transaction_reference }}</span></p>
                                            @endif
                                            <p class="text-xs text-gray-600">Monto: <span class="font-bold text-gray-900">S/ {{ number_format($apt->payment->amount, 2) }}</span></p>
                                            @if($apt->payment->receipt_photo_path)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($apt->payment->receipt_photo_path) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-primary-600 hover:text-primary-700 underline mt-1">
                                                    🔎 Ver imagen del comprobante
                                                </a>
                                            @endif
                                        </div>
                                        <button wire:click="approvePayment({{ $apt->id }})" class="self-start md:self-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                                            Aprobar Pago
                                        </button>
                                    </div>
                                @elseif($apt->payment && $apt->payment->status === 'completed')
                                    <div class="mt-2 text-xs text-emerald-700 flex items-center gap-1 font-semibold">
                                        <span>✓ Pagado S/ {{ number_format($apt->payment->amount, 2) }} vía {{ strtoupper($apt->payment->payment_method) }}</span>
                                        @if($apt->payment->transaction_reference)
                                            <span class="text-gray-400 font-normal">(Ref: {{ $apt->payment->transaction_reference }})</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Fecha y estado -->
                        <div class="shrink-0 flex flex-col items-end gap-3">
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-800">{{ $apt->scheduled_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $apt->scheduled_at->format('H:i') }} hrs</p>
                            </div>

                            @php
                                $statusStyles = [
                                    'pending'   => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'completed' => 'bg-green-50 text-green-700 border-green-200',
                                    'cancelled'  => 'bg-red-50 text-red-700 border-red-200',
                                ];
                                $statusLabels = [
                                    'pending'   => 'Pendiente',
                                    'confirmed' => 'Confirmada',
                                    'completed' => 'Completada',
                                    'cancelled'  => 'Cancelada',
                                ];
                            @endphp
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full border {{ $statusStyles[$apt->status] ?? '' }}">
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
                                        'completed'    => 'Pago Aprobado',
                                        'failed'       => 'Pago Fallido',
                                    ];
                                @endphp
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full border inline-flex items-center gap-1 {{ $payStatusStyles[$apt->payment->status] ?? 'bg-gray-50 text-gray-700 border-gray-200' }}">
                                    <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <rect width="20" height="14" x="2" y="5" rx="2" />
                                        <path d="M2 10h20" />
                                    </svg>
                                    {{ $payStatusLabels[$apt->payment->status] ?? $apt->payment->status }}
                                </span>
                            @endif

                            <!-- Acciones -->
                            <div class="flex gap-2">
                                @if($apt->status === 'pending')
                                    <button wire:click="confirm({{ $apt->id }})"
                                        class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition">
                                        Confirmar
                                    </button>
                                    <button wire:click="$set('confirmingCancel', {{ $apt->id }})"
                                        class="px-3 py-1.5 bg-white border border-red-200 text-red-600 text-xs font-bold rounded-lg hover:bg-red-50 transition">
                                        Rechazar
                                    </button>
                                @elseif($apt->status === 'confirmed')
                                    <button wire:click="complete({{ $apt->id }})"
                                        class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 transition">
                                        Completar
                                    </button>
                                    <button wire:click="$set('confirmingCancel', {{ $apt->id }})"
                                        class="px-3 py-1.5 bg-white border border-red-200 text-red-600 text-xs font-bold rounded-lg hover:bg-red-50 transition">
                                        Cancelar
                                    </button>
                                @endif

                                @if($apt->client->whatsapp ?? null)
                                    <a href="https://wa.me/51{{ preg_replace('/\D/','',$apt->client->whatsapp ?? '') }}"
                                       target="_blank"
                                       class="px-3 py-1.5 bg-green-500 text-white text-xs font-bold rounded-lg hover:bg-green-600 transition flex items-center gap-1">
                                        WA
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de confirmación de cancelación -->
                @if($confirmingCancel === $apt->id)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 -mt-2">
                        <p class="text-sm text-red-800 font-semibold mb-3">¿Confirmas que quieres cancelar esta cita?</p>
                        <div class="flex gap-2">
                            <button wire:click="cancel({{ $apt->id }})"
                                class="px-4 py-1.5 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700">
                                Sí, cancelar
                            </button>
                            <button wire:click="$set('confirmingCancel', null)"
                                class="px-4 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50">
                                No, volver
                            </button>
                        </div>
                    </div>
                @endif

            @empty
                <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4 text-gray-500 font-medium">No hay citas en este estado.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $appointments->links() }}
        </div>

    </div>
</div>
