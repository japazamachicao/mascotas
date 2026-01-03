<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Veterinarian;
use App\Models\Walker;
use App\Models\Trainer;
use App\Models\Groomer;
use App\Models\PetSitter;
use App\Models\PetHotel;
use App\Models\PetTaxi;
use App\Models\PetPhotographer;
use App\Models\District;
use Illuminate\Support\Facades\Hash;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener distritos de Lima para asignar
        $limaDistricts = District::where('province_id', '1501')->get();

        if ($limaDistricts->count() == 0) {
            $this->command->info('No hay distritos cargados. Corriendo UbigeoSeeder...');
            return;
        }

        // 1. Crear VETERINARIOS
        $vetNames = [
            'Clínica Veterinaria San Francisco', 'VetCare Perú', 'Animal Salud', 
            'Dr. Patitas', 'Mundo Mascota', 'Veterinaria El Sol', 
            'Pet Doctors', 'Emergencias Veterinarias 24/7', 'Vida Animal', 'Tu Amigo Fiel'
        ];

        foreach ($vetNames as $index => $name) {
            $user = User::firstOrCreate(['email' => "vet{$index}@example.com"], [
                'name' => $name, 
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('veterinarian');
            Veterinarian::firstOrCreate(['user_id' => $user->id], [
                'license_number' => 'CMVP-' . rand(1000, 9999),
                'bio' => 'Veterinaria integral con más de 10 años de experiencia. Ofrecemos servicios de cirugía, rayos X, y laboratorio clínico.',
                'address' => 'Av. Principal ' . rand(100, 999),
                'district_id' => $limaDistricts->random()->id,
                'is_verified' => true,
                'allows_home_visits' => rand(0, 1),
            ]);
        }

        // 2. Crear PASEADORES
        $walkerNames = [
            'Juan Pérez', 'María Gómez', 'Carlos Ruiz', 
            'Ana López', 'Luis Torres', 'Sofía Castillo',
            'Pedro Ramirez', 'Lucia Fernandez', 'Jorge Chavez', 'Elena Paz'
        ];

        foreach ($walkerNames as $index => $name) {
            $user = User::firstOrCreate(['email' => "walker{$index}@example.com"], [
                'name' => $name,
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('walker');
            Walker::firstOrCreate(['user_id' => $user->id], [
                'experience' => 'Paseador certificado con curso de etología canina. 3 años paseando perros de raza grande.',
                'district_id' => $limaDistricts->random()->id,
                'hourly_rate' => rand(15, 35),
                'is_verified' => true,
            ]);
        }

        // 3. Crear ADIESTRADORES (Trainers)
        $trainerNames = ['César Millán (Fan)', 'Patitas Educadas', 'Dog Training Lima', 'Conducta Canina', 'Educa a tu Perro'];
        foreach ($trainerNames as $index => $name) {
            $user = User::firstOrCreate(['email' => "trainer{$index}@example.com"], [
                'name' => $name,
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('trainer');
            Trainer::firstOrCreate(['user_id' => $user->id], [
                'bio' => 'Especialista en modificación de conducta y obediencia básica. Método positivo.',
                'methodology' => 'Refuerzo Positivo',
                'allows_home_visits' => true,
                'district_id' => $limaDistricts->random()->id,
            ]);
        }

        // 4. Crear GROOMERS (Estilistas)
        $groomerNames = ['Peluditos Spa', 'Estética Canina Guau', 'Spa de Mascotas', 'Baño y Corte Lulú', 'Grooming Pro'];
        foreach ($groomerNames as $index => $name) {
            $user = User::firstOrCreate(['email' => "groomer{$index}@example.com"], [
                'name' => $name,
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('groomer');
            Groomer::firstOrCreate(['user_id' => $user->id], [
                'bio' => 'Baños medicados, corte de uñas, limpieza de oídos y cortes según la raza.',
                'allows_home_visits' => rand(0, 1),
                'district_id' => $limaDistricts->random()->id,
            ]);
        }

        // 5. Crear PET SITTERS (Cuidadores)
        $sitterNames = ['Claudia Cuidadora', 'El Hogar de Firulais', 'Cuidado Amoroso', 'Tu Mascota Feliz', 'Nanny Dog'];
        foreach ($sitterNames as $index => $name) {
            $user = User::firstOrCreate(['email' => "sitter{$index}@example.com"], [
                'name' => $name,
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('pet_sitter');
            PetSitter::firstOrCreate(['user_id' => $user->id], [
                'bio' => 'Cuido a tu mascota en mi casa como si fuera mía. Tengo patio grande y seguro.',
                'housing_type' => 'Casa',
                'has_yard' => true,
                'allows_home_visits' => true,
                'district_id' => $limaDistricts->random()->id,
            ]);
        }

        // 6. Crear PET HOTELS (Hospedaje)
        $hotelNames = ['Hotel Canino 5 Estrellas', 'Resort de Mascotas', 'Campamento Canino', 'Hospedaje Huellitas'];
        foreach ($hotelNames as $index => $name) {
            $user = User::firstOrCreate(['email' => "hotel{$index}@example.com"], [
                'name' => $name,
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('hotel'); // Asumiendo rol 'hotel' o similar
            PetHotel::firstOrCreate(['user_id' => $user->id], [
                'bio' => 'Hospedaje libre de jaulas, con supervisión las 24 horas y cámaras web.',
                'address' => 'Av. Los Frutales ' . rand(100, 999),
                'capacity' => rand(10, 50),
                'cage_free' => true,
                'has_transport' => true,
                'check_in_time' => '14:00',
                'check_out_time' => '12:00',
                'district_id' => $limaDistricts->random()->id,
            ]);
        }

        // 7. Crear PET PHOTOGRAPHERS
        $photoNames = ['Foto Mascota', 'Retratos Peludos', 'Arte Animal', 'Captura tu Amigo'];
        foreach ($photoNames as $index => $name) {
            $user = User::firstOrCreate(['email' => "photo{$index}@example.com"], [
                'name' => $name,
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('pet_photographer');
            PetPhotographer::firstOrCreate(['user_id' => $user->id], [
                'bio' => 'Fotografía profesional para mascotas en exteriores o estudio.',
                'specialty' => 'Retratos',
                'has_studio' => rand(0, 1),
                'district_id' => $limaDistricts->random()->id,
            ]);
        }

        // 8. Crear PET TAXI
        $taxiNames = ['Taxi Mascota Segura', 'Pet Move', 'Transporte Animal', 'Amigo Viajero'];
        foreach ($taxiNames as $index => $name) {
            $user = User::firstOrCreate(['email' => "taxi{$index}@example.com"], [
                'name' => $name,
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('pet_taxi');
            PetTaxi::firstOrCreate(['user_id' => $user->id], [
                'bio' => 'Traslados seguros a veterinarias, peluquerías o aeropuerto. Aire acondicionado.',
                'vehicle_type' => 'Van',
                'has_ac' => true,
                'provides_crate' => true,
                'district_id' => $limaDistricts->random()->id,
            ]);
        }
    }
}
