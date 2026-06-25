<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Register;
use App\Livewire\Pages\Home;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Home::class)->name('home');
Route::get('/register', Register::class)->name('register');
Route::get('/buscar', \App\Livewire\Pages\Search::class)->name('search');
Route::get('/servicios/{serviceType}/{districtName?}', \App\Livewire\Pages\Search::class)->name('services.seo');
Route::get('/perfil/{id}', \App\Livewire\Pages\Profile::class)->name('profile.show');
Route::get('/p/{uuid}', \App\Livewire\Pages\PetProfile::class)->name('pet.profile');

// Rutas Protegidas (Requieren Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->hasRole('super-admin')) {
            return redirect()->route('dashboard.admin');
        }
        if ($user->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer'])) {
            return redirect()->route('dashboard.provider');
        }
        return \App::call(\App\Livewire\Dashboard\ClientDashboard::class);
    })->name('dashboard');
    Route::get('/dashboard/admin', \App\Livewire\Dashboard\AdminDashboard::class)->name('dashboard.admin');
    Route::get('/dashboard/proveedor', \App\Livewire\Dashboard\ProviderDashboard::class)->name('dashboard.provider');
    Route::get('/dashboard/proveedor/citas', \App\Livewire\Dashboard\ProviderAppointments::class)->name('dashboard.provider.appointments');
    Route::get('/dashboard/mascota/crear', \App\Livewire\Dashboard\PetForm::class)->name('dashboard.pet.create');
    Route::get('/dashboard/mascota/editar/{pet}', \App\Livewire\Dashboard\PetForm::class)->name('dashboard.pet.edit');
    Route::get('/dashboard/direcciones', \App\Livewire\Dashboard\ClientAddresses::class)->name('dashboard.addresses');
    Route::get('/dashboard/favoritos', \App\Livewire\Dashboard\ClientFavorites::class)->name('dashboard.favorites');
    Route::get('/dashboard/citas', \App\Livewire\Dashboard\ClientAppointments::class)->name('dashboard.appointments');
    Route::get('/dashboard/perfil', \App\Livewire\Dashboard\ClientProfile::class)->name('dashboard.profile');
    Route::get('/dashboard/mensajes/{contactId?}', \App\Livewire\Dashboard\MessagesDashboard::class)->name('dashboard.messages');
    

});

// Rutas de Reset de Contraseña para Invitados (Guest)
Route::middleware(['guest'])->group(function () {
    Route::get('/forgot-password', \App\Livewire\Auth\ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', \App\Livewire\Auth\ResetPassword::class)->name('password.reset');
});

Route::get('/seed-services', function () {
    if (!app()->isLocal()) {
        abort(403, 'Unauthorized action.');
    }
    try {
        // Ejecutar el seeder completo (Roles, Ubigeo, Servicios, Usuarios demo)
        $seeder = new \Database\Seeders\DatabaseSeeder();
        $seeder->run();
        return "✅ Base de datos sembrada correctamente! (Roles, Ubigeos, Servicios y Usuarios creados). <br> Ve a <a href='/login'>/login</a> para entrar como admin@todopeludos.com / password";
    } catch (\Exception $e) {
        return "❌ Error al sembrar: " . $e->getMessage();
    }
})->middleware(['auth']);

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware(['auth']);

Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
