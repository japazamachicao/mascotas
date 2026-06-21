<div class="py-10" x-data="{ showDoc: false, docUrl: '', docTitle: '' }">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6 border-b border-gray-200/60 pb-6">
            <div>
                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-100 text-red-700">
                    🛡️ Acceso Administrativo
                </span>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight mt-2">Panel de Control General</h2>
                <p class="text-sm text-gray-500 mt-1">Supervisa las métricas operativas de la plataforma, valida proveedores y gestiona la comunidad.</p>
            </div>
        </div>

        <!-- Mensajes de Alerta -->
        @if(session('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-4 py-3 rounded-2xl shadow-xs flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        <!-- Menú de Pestañas Premium -->
        <div class="flex gap-2 mb-8 border-b border-gray-200/60 pb-px scrollbar-none overflow-x-auto">
            <button wire:click="$set('activeTab', 'stats')"
                class="px-6 py-3.5 font-bold text-sm border-b-2 transition duration-200 flex items-center gap-2 shrink-0
                {{ $activeTab === 'stats' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>📊</span> Estadísticas y KPIs
            </button>
            <button wire:click="$set('activeTab', 'verifications')"
                class="px-6 py-3.5 font-bold text-sm border-b-2 transition duration-200 relative flex items-center gap-2 shrink-0
                {{ $activeTab === 'verifications' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>🔍</span> Verificaciones Pendientes
                @if(count($pendingVerifications) > 0)
                    <span class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full ring-2 ring-white animate-pulse">
                        {{ count($pendingVerifications) }}
                    </span>
                @endif
            </button>
            <button wire:click="$set('activeTab', 'users')"
                class="px-6 py-3.5 font-bold text-sm border-b-2 transition duration-200 flex items-center gap-2 shrink-0
                {{ $activeTab === 'users' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>👤</span> Gestión de Usuarios
            </button>
        </div>

        <!-- CONTENIDO: ESTADÍSTICAS -->
        @if($activeTab === 'stats')
            <div class="space-y-8" x-transition>
                <!-- KPIs Grid Premium (Degradados Metálicos Suaves) -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Total Usuarios -->
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white overflow-hidden shadow-md rounded-3xl p-6 hover:shadow-lg hover:-translate-y-0.5 transition duration-300">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-wider text-blue-100">Total Usuarios</p>
                                <p class="text-3xl font-black mt-2">{{ $kpis['totalUsers'] }}</p>
                            </div>
                            <div class="p-3 bg-white/10 rounded-2xl text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Clientes -->
                    <div class="bg-gradient-to-br from-purple-500 to-violet-600 text-white overflow-hidden shadow-md rounded-3xl p-6 hover:shadow-lg hover:-translate-y-0.5 transition duration-300">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-wider text-purple-100">Dueños / Clientes</p>
                                <p class="text-3xl font-black mt-2">{{ $kpis['totalClients'] }}</p>
                            </div>
                            <div class="p-3 bg-white/10 rounded-2xl text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Proveedores -->
                    <div class="bg-gradient-to-br from-teal-500 to-emerald-600 text-white overflow-hidden shadow-md rounded-3xl p-6 hover:shadow-lg hover:-translate-y-0.5 transition duration-300">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-wider text-teal-100">Proveedores Activos</p>
                                <p class="text-3xl font-black mt-2">{{ $kpis['totalProviders'] }}</p>
                            </div>
                            <div class="p-3 bg-white/10 rounded-2xl text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Calificación -->
                    <div class="bg-gradient-to-br from-amber-500 to-yellow-600 text-white overflow-hidden shadow-md rounded-3xl p-6 hover:shadow-lg hover:-translate-y-0.5 transition duration-300">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-wider text-amber-100">Reputación General</p>
                                <p class="text-3xl font-black mt-2">{{ $kpis['averageRating'] }} / 5.0</p>
                            </div>
                            <div class="p-3 bg-white/10 rounded-2xl text-white">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Proveedores por Categoría -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-200/80 p-6 lg:col-span-2">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <span>💼</span> Proveedores por Especialidad
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach([
                                'vets' => ['label' => '🩺 Veterinarios', 'color' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
                                'walkers' => ['label' => '🚶 Paseadores de Perros', 'color' => 'bg-indigo-50 text-indigo-700 border-indigo-100'],
                                'groomers' => ['label' => '✂️ Estilistas / Baño', 'color' => 'bg-pink-50 text-pink-700 border-pink-100'],
                                'hotels' => ['label' => '🏨 Hospedajes Caninos', 'color' => 'bg-cyan-50 text-cyan-700 border-cyan-100'],
                                'shelters' => ['label' => '🏠 Albergues / Rescates', 'color' => 'bg-red-50 text-red-700 border-red-100'],
                                'trainers' => ['label' => '🎓 Adiestradores', 'color' => 'bg-amber-50 text-amber-700 border-amber-100'],
                                'sitters' => ['label' => '🐾 Cuidadores a Domicilio', 'color' => 'bg-violet-50 text-violet-700 border-violet-100'],
                                'taxis' => ['label' => '🚗 Transporte de Mascotas', 'color' => 'bg-slate-50 text-slate-700 border-slate-100'],
                                'photographers' => ['label' => '📸 Fotógrafos de Mascotas', 'color' => 'bg-sky-50 text-sky-700 border-sky-100']
                            ] as $key => $meta)
                                <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl border border-gray-200/60 hover:bg-white hover:shadow-xs transition duration-200">
                                    <span class="text-sm font-semibold text-gray-700">{{ $meta['label'] }}</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-black border {{ $meta['color'] }}">
                                        {{ $kpis[$key] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Estado de Citas -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-200/80 p-6 flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <span>📅</span> Control de Citas
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                    <span class="text-sm font-bold text-gray-500 uppercase">Total Citas</span>
                                    <span class="font-black text-gray-900 text-xl">{{ $kpis['totalAppointments'] }}</span>
                                </div>

                                @foreach([
                                    'appointments_pending' => ['label' => 'Pendientes', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50'],
                                    'appointments_confirmed' => ['label' => 'Confirmadas', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                                    'appointments_completed' => ['label' => 'Completadas', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                                    'appointments_cancelled' => ['label' => 'Canceladas', 'color' => 'text-red-600', 'bg' => 'bg-red-50']
                                ] as $key => $meta)
                                    <div class="flex items-center justify-between py-2.5">
                                        <span class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full {{ $meta['bg'] }} border {{ $meta['color'] }} inline-block"></span>
                                            {{ $meta['label'] }}
                                        </span>
                                        <span class="font-black text-gray-800">{{ $kpis[$key] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Barra de progreso simulando confirmación/éxito -->
                        <div class="pt-6 border-t border-gray-100 mt-6">
                            @php
                                $successRate = $kpis['totalAppointments'] > 0
                                    ? round(($kpis['appointments_completed'] / $kpis['totalAppointments']) * 100)
                                    : 0;
                            @endphp
                            <div class="flex justify-between items-center text-xs font-bold text-gray-500 mb-2">
                                <span>TASA DE COMPLETADO</span>
                                <span>{{ $successRate }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $successRate }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- CONTENIDO: VERIFICACIONES -->
        @if($activeTab === 'verifications')
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/85 overflow-hidden" x-transition>
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Verificación de Profesionales</h3>
                    <p class="text-sm text-gray-500 mt-1">Valida la autenticidad de los documentos que acreditan la identidad y títulos del proveedor.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Proveedor</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Especialidad</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Intentos</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Documento</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($pendingVerifications as $pending)
                                <tr class="hover:bg-gray-50/40 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-950 text-sm">{{ $pending['user_name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $pending['user_email'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-700">
                                            {{ $pending['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">
                                        {{ $pending['attempts'] }} / 2
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($pending['document_path'])
                                            <button @click="docUrl = '{{ \Illuminate\Support\Facades\Storage::url($pending['document_path']) }}'; docTitle = '{{ $pending['user_name'] }} - {{ $pending['label'] }}'; showDoc = true"
                                                    class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-700 font-bold transition">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                Verificar En Pantalla
                                            </button>
                                        @else
                                            <span class="text-gray-400 italic">No cargado</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold">
                                        <div class="flex gap-2 justify-end">
                                            <button wire:click="approveVerification({{ $pending['id'] }}, '{{ $pending['role'] }}')"
                                                    class="px-3.5 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition shadow-xs font-bold text-xs">
                                                Aprobar
                                            </button>
                                            <button wire:click="rejectVerification({{ $pending['id'] }}, '{{ $pending['role'] }}')"
                                                    class="px-3.5 py-2 bg-white border border-red-200 text-red-650 rounded-lg hover:bg-red-50 transition shadow-xs font-bold text-xs">
                                                Rechazar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-20 text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-3xl shadow-xs">
                                                ✓
                                            </div>
                                            <p class="mt-4 font-bold text-gray-800 text-base">¡Bandeja vacía!</p>
                                            <p class="text-xs text-gray-400 mt-1">No hay solicitudes de verificación pendientes por revisar hoy.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- CONTENIDO: GESTIÓN DE USUARIOS -->
        @if($activeTab === 'users')
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200/85 overflow-hidden space-y-6" x-transition>
                <!-- Search and Stats -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Directorio de Cuentas</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Administra y filtra todos los perfiles de clientes y proveedores.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <!-- Selector de Rol (Nuevo Filtro Premium) -->
                        <div class="w-full sm:w-48">
                            <select wire:model.live="filterRole" class="block w-full px-3 py-2 border border-gray-300 rounded-xl shadow-xs focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="all">👥 Todos los roles</option>
                                <option value="super-admin">🛡️ Administradores</option>
                                <option value="client">👦 Clientes / Dueños</option>
                                <option value="veterinarian">🩺 Veterinarios</option>
                                <option value="walker">🚶 Paseadores</option>
                                <option value="groomer">✂️ Estilistas</option>
                                <option value="hotel">🏨 Hoteles</option>
                                <option value="shelter">🏠 Albergues</option>
                                <option value="trainer">🎓 Adiestradores</option>
                                <option value="pet_sitter">🐾 Cuidadores</option>
                                <option value="pet_taxi">🚗 Taxis</option>
                                <option value="pet_photographer">📸 Fotógrafos</option>
                            </select>
                        </div>

                        <!-- Buscador -->
                        <div class="w-full sm:w-72 relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input type="text" wire:model.live="search"
                                   class="pl-10 block w-full px-3 py-2 border border-gray-300 rounded-xl shadow-xs placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   placeholder="Buscar por nombre o email...">
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Usuario</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Roles / Perfiles</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Registro</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($users as $usr)
                                <tr class="hover:bg-gray-50/40 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">
                                        #{{ $usr->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <img class="h-9 w-9 rounded-full object-cover bg-gray-200 shrink-0 border border-gray-100 shadow-xs group-hover:scale-105 transition"
                                                 src="{{ $usr->profile_photo_path ? \Illuminate\Support\Facades\Storage::url($usr->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($usr->name).'&background=random&color=fff' }}"
                                                 alt="{{ $usr->name }}">
                                            <div>
                                                <div class="font-bold text-gray-950 text-sm">{{ $usr->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $usr->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($usr->roles as $role)
                                                @php
                                                    $roleColors = [
                                                        'super-admin' => 'bg-red-50 text-red-700 border-red-100',
                                                        'client' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                        'veterinarian' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                        'walker' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                                        'groomer' => 'bg-pink-50 text-pink-700 border-pink-100',
                                                        'hotel' => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                                                        'shelter' => 'bg-red-50 text-red-700 border-red-100',
                                                    ];
                                                    $color = $roleColors[$role->name] ?? 'bg-gray-50 text-gray-750 border-gray-200';
                                                    $labelText = $role->name === 'super-admin' ? 'Administrador' : ($role->name === 'client' ? 'Cliente' : ucfirst($role->name));
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $color }}">
                                                    {{ $labelText }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $usr->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold">
                                        @if(!$usr->hasRole('client') && !$usr->hasRole('super-admin'))
                                            <a href="{{ route('profile.show', $usr->id) }}" target="_blank"
                                               class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 text-xs font-bold transition">
                                                🔍 Perfil Público
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs font-normal">No requiere perfil</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-20 text-gray-400 italic bg-white">
                                        No se encontraron cuentas que coincidan con la búsqueda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-6 border-t border-gray-100 bg-gray-50/20">
                    {{ $users->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- Modal Visor de Documentos (AlpineJS) -->
    <div x-show="showDoc" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showDoc = false">
                <div class="absolute inset-0 bg-gray-950/50 backdrop-blur-xs"></div>
            </div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
                <div class="bg-white px-6 py-4.5 border-b border-gray-200/80 flex items-center justify-between">
                    <h3 class="text-base font-black text-gray-900 flex items-center gap-2">
                        📄 Documento de: <span class="text-primary-750 font-bold" x-text="docTitle"></span>
                    </h3>
                    <button @click="showDoc = false" class="text-gray-400 hover:text-gray-500 focus:outline-none transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 bg-gray-50 flex justify-center">
                    <template x-if="docUrl && docUrl.toLowerCase().indexOf('.pdf') !== -1">
                        <iframe :src="docUrl" class="w-full h-[550px] rounded-2xl border border-gray-200/80 bg-white shadow-xs" frameborder="0"></iframe>
                    </template>
                    <template x-if="docUrl && docUrl.toLowerCase().indexOf('.pdf') === -1">
                        <div class="max-h-[550px] overflow-auto flex items-center justify-center p-4 bg-white rounded-2xl border border-gray-200 shadow-xs w-full">
                            <img :src="docUrl" class="max-w-full max-h-[500px] object-contain rounded-lg" alt="Documento de verificación">
                        </div>
                    </template>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200/80 flex justify-end gap-2.5">
                    <a :href="docUrl" download class="px-4 py-2 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 text-xs font-black shadow-xs transition">
                        📥 Descargar Archivo
                    </a>
                    <button @click="showDoc = false" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-black shadow-xs transition">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
