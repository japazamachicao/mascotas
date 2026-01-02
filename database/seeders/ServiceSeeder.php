<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Veterinarian;
use App\Models\Walker;
use App\Models\District;
use Illuminate\Support\Facades\Hash;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener distritos de Lima para asignar (asumiendo que corrió el UbigeoSeeder)
        $limaDistricts = District::where('province_id', '1501')->get();

        if ($limaDistricts->count() == 0) {
            $this->command->info('No hay distritos cargados. Corriendo UbigeoSeeder...');
            // Fallback por si acaso
            return;
        }

        // 1. Crear VETERINARIOS
        $vetNames = [
            'Clínica Veterinaria San Francisco', 'VetCare Perú', 'Animal Salud', 
            'Dr. Patitas', 'Mundo Mascota', 'Veterinaria El Sol', 
            'Pet Doctors', 'Emergencias Veterinarias 24/7', 'Vida Animal', 'Tu Amigo Fiel'
        ];

        foreach ($vetNames as $index => $name) {
            $user = User::firstOrCreate(
                ['email' => "vet{$index}@example.com"],
                [
                    'name' => $name, // En vets, el nombre de usuario suele ser el nombre comercial
                    'password' => Hash::make('password'),
                ]
            );
            
            $user->assignRole('veterinarian');

            Veterinarian::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'license_number' => 'CMVP-' . rand(1000, 9999),
                    'bio' => 'Veterinaria integral con más de 10 años de experiencia. Ofrecemos servicios de cirugía, rayos X, y laboratorio clínico.',
                    'address' => 'Av. Principal ' . rand(100, 999),
                    'district_id' => $limaDistricts->random()->id,
                    'is_verified' => true,
                    'allows_home_visits' => rand(0, 1),
                ]
            );
        }

        // 2. Crear PASEADORES
        $walkerNames = [
            'Juan Pérez', 'María Gómez', 'Carlos Ruiz', 
            'Ana López', 'Luis Torres', 'Sofía Castillo'
        ];

        foreach ($walkerNames as $index => $name) {
            $user = User::firstOrCreate(
                ['email' => "walker{$index}@example.com"],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                ]
            );
            
            $user->assignRole('walker');

            Walker::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'experience' => 'Paseador certificado con curso de etología canina. 3 años paseando perros de raza grande.',
                    'district_id' => $limaDistricts->random()->id,
                    'hourly_rate' => rand(15, 35), // Soles por hora
                    'is_verified' => true,
                ]
            );
        }
    }
}
