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
        $admin = User::firstOrCreate(
            ['email' => 'admin@mascotas.pe'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('super-admin');

        // Veterinario
        $vet = User::firstOrCreate(
            ['email' => 'vet@mascotas.pe'],
            [
                'name' => 'Dr. Veterinario',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $vet->syncRoles(['veterinarian']);
        
        if (!$vet->veterinarianProfile) {
            $vet->veterinarianProfile()->create([
                'license_number' => 'CMVP-12345',
                'bio' => 'Veterinario experto con 10 años de experiencia.',
                'allows_home_visits' => true,
                'emergency_24h' => true,
                'district_id' => '150101'
            ]);
        }

        // Paseador
        $walker = User::firstOrCreate(
            ['email' => 'walker@mascotas.pe'],
            [
                'name' => 'Juan Paseador',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $walker->syncRoles(['walker']);
        
        if (!$walker->walkerProfile) {
            $walker->walkerProfile()->create([
                'experience' => 'Paseador de perros certificado.',
                'hourly_rate' => 25.00,
                'district_id' => '150101'
            ]);
        }

        // Estilista (Groomer)
        $groomer = User::firstOrCreate(
            ['email' => 'groomer@mascotas.pe'],
            [
                'name' => 'Ana Estilista',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $groomer->syncRoles(['groomer']);
        $groomer->groomerProfile()->create([
            'bio' => 'Especialista en cortes de raza y spa canino.',
            'allows_home_visits' => false,
            'district_id' => '150101'
        ]);

        // Hotel Canino
        $hotel = User::firstOrCreate(
            ['email' => 'hotel@mascotas.pe'],
            [
                'name' => 'Hotel De Pelos',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $hotel->syncRoles(['hotel']);
        
        if (!$hotel->hotelProfile) {
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
        }

        // Cliente
        $client = User::firstOrCreate(
            ['email' => 'cliente@mascotas.pe'],
            [
                'name' => 'Cliente Demo',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $client->syncRoles(['client']);

        // Albergue
        $shelter = User::firstOrCreate(
            ['email' => 'shelter@mascotas.pe'],
            [
                'name' => 'Albergue Esperanza',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $shelter->syncRoles(['shelter']);
        
        if (!$shelter->shelterProfile) {
            $shelter->shelterProfile()->create([
                'bio' => 'Rescatamos angelitos de la calle.',
                'address' => 'Calle Los Pinos 456',
                'capacity' => 50,
                'accepting_adoptions' => true,
                'accepting_volunteers' => true,
                'donation_info' => 'BCP: 191-12345678-0-99 (Yape)',
                'district_id' => '150101'
            ]);
        }

        // Trainer
        $trainer = User::firstOrCreate(
            ['email' => 'trainer@mascotas.pe'],
            [
                'name' => 'César Millan (Fan)',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $trainer->syncRoles(['trainer']);
        
        if (!$trainer->trainerProfile) {
            $trainer->trainerProfile()->create([
                'bio' => 'Adiestramiento positivo y corrección de conducta.',
                'methodology' => 'Refuerzo Positivo',
                'allows_home_visits' => true,
                'district_id' => '150101'
            ]);
        }

        // Pet Sitter
        $sitter = User::firstOrCreate(
            ['email' => 'sitter@mascotas.pe'],
            [
                'name' => 'Claudia Cuidadora',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $sitter->syncRoles(['pet_sitter']);
        
        if (!$sitter->petSitterProfile) {
            $sitter->petSitterProfile()->create([
                'bio' => 'Amo a los perros, tengo un patio grande.',
                'housing_type' => 'Casa',
                'has_yard' => true,
                'allows_home_visits' => true,
                'district_id' => '150101'
            ]);
        }
        
        // Pet Taxi
        $taxi = User::firstOrCreate(
            ['email' => 'taxi@mascotas.pe'],
            [
                'name' => 'Taxi Mascota Segura',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $taxi->syncRoles(['pet_taxi']);
        
        if (!$taxi->petTaxiProfile) {
            $taxi->petTaxiProfile()->create([
                'bio' => 'Traslados seguros y cómodos a cualquier punto de Lima.',
                'vehicle_type' => 'Van',
                'has_ac' => true,
                'provides_crate' => true,
                'district_id' => '150101'
            ]);
        }

        // Fotógrafo
        $photo = User::firstOrCreate(
            ['email' => 'photo@mascotas.pe'],
            [
                'name' => 'Fotonimal',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $photo->syncRoles(['pet_photographer']);
        
        if (!$photo->petPhotographerProfile) {
            $photo->petPhotographerProfile()->create([
                'bio' => 'Capturando la esencia de tu mejor amigo.',
                'specialty' => 'Retratos en Estudio',
                'has_studio' => true,
                'district_id' => '150101'
            ]);
        }

        // Cliente
        $client = User::factory()->create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@mascotas.pe',
            'password' => bcrypt('password'),
        ]);
        $client->assignRole('client');
    }
}
