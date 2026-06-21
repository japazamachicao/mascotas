<div class="flex flex-col justify-start pt-16 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Recupera tu contraseña
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Ingresa tu correo y te enviaremos un enlace para restablecerla.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            @if ($status)
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-100">
                    {{ $status }}
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Correo Electrónico
                    </label>
                    <div class="mt-1">
                        <input wire:model="email" id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    @error('email') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Enviar Enlace de Recuperación
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 text-sm">
                    Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>
</div>
