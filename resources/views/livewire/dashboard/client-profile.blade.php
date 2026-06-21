<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div>
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl">
                Mi Perfil
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Gestiona tus datos personales y la seguridad de tu cuenta.
            </p>
        </div>

        <!-- Sección: Datos Personales -->
        <div class="bg-white shadow sm:rounded-lg overflow-hidden border border-gray-200">
            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-bold leading-6 text-gray-900">
                    Datos Personales
                </h3>
                <p class="mt-1 text-xs text-gray-500">
                    Actualiza tu nombre, correo electrónico y foto de perfil.
                </p>
            </div>
            
            <div class="p-6">
                @if (session()->has('profile_message'))
                    <div class="mb-6 rounded-xl bg-green-50 p-4 border border-green-200 text-sm font-semibold text-green-800">
                        {{ session('profile_message') }}
                    </div>
                @endif

                <form wire:submit.prevent="updateProfile" class="space-y-6">
                    <!-- Foto de Perfil -->
                    <div class="flex items-center gap-6">
                        <div class="relative">
                            <div class="h-24 w-24 rounded-full overflow-hidden border-2 border-gray-200 bg-gray-100 shadow-inner flex items-center justify-center">
                                @if ($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" class="h-full w-full object-cover">
                                @elseif ($existingPhoto)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($existingPhoto) }}" class="h-full w-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=0ea5e9&color=fff&size=128" class="h-full w-full object-cover">
                                @endif
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Foto de Perfil</label>
                            <input type="file" wire:model="photo" id="photo" class="hidden">
                            <label for="photo" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Cambiar Foto
                            </label>
                            <span class="block text-[10px] text-gray-400 mt-2">Formatos permitidos: PNG, JPG, JPEG. Máx 1MB.</span>
                            @error('photo') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700">Nombre Completo</label>
                        <div class="mt-1">
                            <input type="text" wire:model="name" id="name" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        </div>
                        @error('name') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700">Correo Electrónico</label>
                        <div class="mt-1">
                            <input type="email" wire:model="email" id="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        </div>
                        @error('email') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Botón Guardar -->
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sección: Cambiar Contraseña -->
        <div class="bg-white shadow sm:rounded-lg overflow-hidden border border-gray-200">
            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-bold leading-6 text-gray-900">
                    Seguridad / Cambiar Contraseña
                </h3>
                <p class="mt-1 text-xs text-gray-500">
                    Asegúrate de que tu cuenta esté utilizando una contraseña segura y única.
                </p>
            </div>

            <div class="p-6">
                @if (session()->has('password_message'))
                    <div class="mb-6 rounded-xl bg-green-50 p-4 border border-green-200 text-sm font-semibold text-green-800">
                        {{ session('password_message') }}
                    </div>
                @endif

                <form wire:submit.prevent="updatePassword" class="space-y-6">
                    <!-- Contraseña Actual -->
                    <div x-data="{ show: false }">
                        <label for="current_password" class="block text-sm font-bold text-gray-700">Contraseña Actual</label>
                        <div class="mt-1 relative">
                            <input :type="show ? 'text' : 'password'" wire:model="current_password" id="current_password" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="show = !show">
                                <span x-show="!show" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </span>
                                <span x-show="show" class="text-gray-400 hover:text-gray-500" style="display: none;">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </span>
                            </div>
                        </div>
                        @error('current_password') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nueva Contraseña -->
                    <div x-data="{ show: false }">
                        <label for="new_password" class="block text-sm font-bold text-gray-700">Nueva Contraseña</label>
                        <div class="mt-1 relative">
                            <input :type="show ? 'text' : 'password'" wire:model="new_password" id="new_password" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="show = !show">
                                <span x-show="!show" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </span>
                                <span x-show="show" class="text-gray-400 hover:text-gray-500" style="display: none;">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </span>
                            </div>
                        </div>
                        @error('new_password') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div x-data="{ show: false }">
                        <label for="new_password_confirmation" class="block text-sm font-bold text-gray-700">Confirmar Nueva Contraseña</label>
                        <div class="mt-1 relative">
                            <input :type="show ? 'text' : 'password'" wire:model="new_password_confirmation" id="new_password_confirmation" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" @click="show = !show">
                                <span x-show="!show" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </span>
                                <span x-show="show" class="text-gray-400 hover:text-gray-500" style="display: none;">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
