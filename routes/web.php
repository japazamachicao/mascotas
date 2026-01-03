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
Route::get('/perfil/{id}', \App\Livewire\Pages\Profile::class)->name('profile.show');
Route::get('/p/{uuid}', \App\Livewire\Pages\PetProfile::class)->name('pet.profile');

// Rutas Protegidas (Requieren Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer'])) {
            return redirect()->route('dashboard.provider');
        }
        return \App::call(\App\Livewire\Dashboard\ClientDashboard::class);
    })->name('dashboard');
    Route::get('/dashboard/proveedor', \App\Livewire\Dashboard\ProviderDashboard::class)->name('dashboard.provider');
    Route::get('/dashboard/mascota/crear', \App\Livewire\Dashboard\PetForm::class)->name('dashboard.pet.create');
    Route::get('/dashboard/mascota/editar/{pet}', \App\Livewire\Dashboard\PetForm::class)->name('dashboard.pet.edit');
    Route::get('/dashboard/direcciones', \App\Livewire\Dashboard\ClientAddresses::class)->name('dashboard.addresses');
});

Route::get('/seed-services', function () {
    $seeder = new \Database\Seeders\ServiceSeeder();
    $seeder->run();
    return "Servicios sembrados correctamente! Ahora ve a <a href='/buscar'>/buscar</a>";
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
