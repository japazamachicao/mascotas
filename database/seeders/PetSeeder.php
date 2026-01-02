<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        // Crear un Dueño de prueba
        $owner = User::firstOrCreate(
            ['email' => 'cliente@mascotas.pe'],
            [
                'name' => 'Ana Dueña',
                'password' => Hash::make('password'),
            ]
        );
        
        // Asegurar rol
        if (!$owner->hasRole('client')) {
            $owner->assignRole('client');
        }

        // Crear una Mascota de prueba (Firulais)
        // Usamos un UUID fijo o generado solo si no existe, para pruebas consistentes
        $pet = Pet::where('user_id', $owner->id)->where('name', 'Firulais')->first();

        if (!$pet) {
            $uuid = Str::uuid();
            $pet = Pet::create([
                'user_id' => $owner->id,
                'name' => 'Firulais',
                'species' => 'Perro',
                'breed' => 'Golden Retriever',
                'birth_date' => '2023-01-15',
                'gender' => 'M',
                'weight' => 28.5,
                'medical_notes' => 'Alérgico al pollo. Tiene todas sus vacunas al día. Chip ID: 987654321.',
                'uuid' => $uuid,
            ]);
            $this->command->info("¡Mascota creada!");
        } else {
            $this->command->info("La mascota ya existía. Usando datos existentes.");
        }
        
        $url = route('pet.profile', ['uuid' => $pet->uuid]);
        
        $this->command->info("------------------------------------------------");
        $this->command->info("NOMBRE: " . $pet->name);
        $this->command->info("UUID:   " . $pet->uuid);
        $this->command->info("URL:    " . $url);
        $this->command->info("------------------------------------------------");
        $this->command->info("Prueba abrir esa URL en tu navegador.");
    }
}
