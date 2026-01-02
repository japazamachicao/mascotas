<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UbigeoSeeder::class,
            ServiceSeeder::class,
            PetSeeder::class,
        ]);
        
        // Admin
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('super-admin');

        // Veterinario
        $vet = User::factory()->create([
            'name' => 'Dr. Veterinario',
            'email' => 'vet@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $vet->assignRole('veterinarian');
        $vet->veterinarianProfile()->create([
            'license_number' => 'CMVP-12345',
            'bio' => 'Veterinario experto con 10 años de experiencia.',
            'allows_home_visits' => true,
            'emergency_24h' => true,
            'district_id' => '150101'
        ]);

        // Paseador
        $walker = User::factory()->create([
            'name' => 'Juan Paseador',
            'email' => 'walker@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $walker->assignRole('walker');
        $walker->walkerProfile()->create([
            'experience' => 'Paseador de perros certificado.',
            'hourly_rate' => 25.00,
            'district_id' => '150101'
        ]);

        // Estilista (Groomer)
        $groomer = User::factory()->create([
            'name' => 'Ana Estilista',
            'email' => 'groomer@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $groomer->assignRole('groomer');
        $groomer->groomerProfile()->create([
            'bio' => 'Especialista en cortes de raza y spa canino.',
            'allows_home_visits' => false,
            'district_id' => '150101'
        ]);

        // Hotel Canino
        $hotel = User::factory()->create([
            'name' => 'Hotel De Pelos',
            'email' => 'hotel@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $hotel->assignRole('hotel');
        $hotel->hotelProfile()->create([
            'bio' => 'Tu mascota se sentirá como en casa. Sin jaulas.',
            'address' => 'Av. Las Palmeras 123',
            'capacity' => 20,
            'cage_free' => true,
            'has_transport' => true,
            'check_in_time' => '14:00',
            'check_out_time' => '12:00',
            'district_id' => '150101'
        ]);

        // Albergue
        $shelter = User::factory()->create([
            'name' => 'Albergue Esperanza',
            'email' => 'shelter@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $shelter->assignRole('shelter');
        $shelter->shelterProfile()->create([
            'bio' => 'Rescatamos angelitos de la calle.',
            'address' => 'Calle Los Pinos 456',
            'capacity' => 50,
            'accepting_adoptions' => true,
            'accepting_volunteers' => true,
            'donation_info' => 'BCP: 191-12345678-0-99 (Yape)',
            'district_id' => '150101'
        ]);

        // Trainer
        $trainer = User::factory()->create([
            'name' => 'César Millan (Fan)',
            'email' => 'trainer@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $trainer->assignRole('trainer');
        $trainer->trainerProfile()->create([
            'bio' => 'Adiestramiento positivo y corrección de conducta.',
            'methodology' => 'Refuerzo Positivo',
            'allows_home_visits' => true,
            'district_id' => '150101'
        ]);

        // Pet Sitter
        $sitter = User::factory()->create([
            'name' => 'Claudia Cuidadora',
            'email' => 'sitter@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $sitter->assignRole('pet_sitter');
        $sitter->petSitterProfile()->create([
            'bio' => 'Amo a los perros, tengo un patio grande.',
            'housing_type' => 'Casa',
            'has_yard' => true,
            'allows_home_visits' => true,
            'district_id' => '150101'
        ]);

        // Pet Taxi
        $taxi = User::factory()->create([
            'name' => 'Taxi Mascota Segura',
            'email' => 'taxi@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $taxi->assignRole('pet_taxi');
        $taxi->petTaxiProfile()->create([
            'bio' => 'Traslados seguros y cómodos a cualquier punto de Lima.',
            'vehicle_type' => 'Van',
            'has_ac' => true,
            'provides_crate' => true,
            'district_id' => '150101'
        ]);

        // Fotógrafo
        $photo = User::factory()->create([
            'name' => 'Fotonimal',
            'email' => 'photo@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $photo->assignRole('pet_photographer');
        $photo->petPhotographerProfile()->create([
            'bio' => 'Capturando la esencia de tu mejor amigo.',
            'specialty' => 'Retratos en Estudio',
            'has_studio' => true,
            'district_id' => '150101'
        ]);

        // Cliente
        $client = User::factory()->create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $client->assignRole('client');
    }
}
