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

// Rutas Públicas de Demos IA
Route::get('/demo/analisis', \App\Livewire\Demo\DemoHealthAnalyzer::class)->name('demo.health.analyze');
Route::get('/demo/plan-cuidado', \App\Livewire\Demo\DemoCarePlan::class)->name('demo.care.plan');


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
    Route::get('/dashboard/favoritos', \App\Livewire\Dashboard\ClientFavorites::class)->name('dashboard.favorites');
    
    // Rutas de IA (Salud)
    Route::get('/dashboard/salud/analizar', \App\Livewire\Dashboard\HealthAnalyzer::class)->name('dashboard.health.analyze');
    Route::get('/dashboard/salud/plan', \App\Livewire\Dashboard\CarePlanGenerator::class)->name('dashboard.care.plan');
    Route::get('/dashboard/salud/historial', \App\Livewire\Dashboard\HealthHistory::class)->name('dashboard.health.history');
});

Route::get('/seed-services', function () {
    try {
        // Ejecutar el seeder completo (Roles, Ubigeo, Servicios, Usuarios demo)
        $seeder = new \Database\Seeders\DatabaseSeeder();
        $seeder->run();
        return "✅ Base de datos sembrada correctamente! (Roles, Ubigeos, Servicios y Usuarios creados). <br> Ve a <a href='/login'>/login</a> para entrar como admin@mascotas.pe / password";
    } catch (\Exception $e) {
        return "❌ Error al sembrar: " . $e->getMessage();
    }
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
