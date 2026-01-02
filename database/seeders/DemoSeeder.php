<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Veterinarian;
use App\Models\Pet;
use Illuminate\Support\Facades\Hash;
use App\Models\District;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear VETERINARIO DEMO
        $vetUser = User::firstOrCreate(
            ['email' => 'vet_demo@mascotas.pe'],
            [
                'name' => 'Dr. Demo Veterinario',
                'password' => Hash::make('password'), // Contraseña fácil
            ]
        );
        
        $vetUser->syncRoles(['veterinarian']);

        // Asignarle perfil si no tiene
        if (!$vetUser->veterinarianProfile) {
            $district = District::where('province_id', '1501')->inRandomOrder()->first(); // Un distrito de Lima
            Veterinarian::create([
                'user_id' => $vetUser->id,
                'license_number' => 'CMVP-DEMO',
                'bio' => 'Soy un veterinario de demostración. Edita este perfil en tu dashboard para ver los cambios.',
                'address' => 'Av. Larco 123, Miraflores',
                'district_id' => $district->id ?? '150101',
                'is_verified' => true,
                'allows_home_visits' => true,
            ]);
        }

        // 2. Crear CLIENTE DEMO
        $clientUser = User::firstOrCreate(
            ['email' => 'cliente_demo@mascotas.pe'],
            [
                'name' => 'Ana Cliente Demo',
                'password' => Hash::make('password'),
            ]
        );

        $clientUser->syncRoles(['client']);

        // Crear una mascota para este cliente
        $pet = Pet::firstOrCreate(
            ['name' => 'Bobby Demo', 'user_id' => $clientUser->id],
            [
                'species' => 'Perro',
                'breed' => 'Beagle',
                'birth_date' => '2022-05-20',
                'gender' => 'M',
                'weight' => 12.5,
                'medical_notes' => 'Mascota de prueba para el dashboard.',
                'uuid' => Str::uuid(),
            ]
        );

        $this->command->info('----------------------------------------------');
        $this->command->info('USUARIOS DEMO CREADOS:');
        $this->command->info('1. Veterinario: vet_demo@mascotas.pe / password');
        $this->command->info('2. Cliente:     cliente_demo@mascotas.pe / password');
        $this->command->info('----------------------------------------------');
    }
}
