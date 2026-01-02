<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos básicos (Expandiremos esto luego)
        // Permission::create(['name' => 'edit articles']);

        // Crear Roles (usando firstOrCreate para evitar errores si ya existen)
        $roleClient = Role::firstOrCreate(['name' => 'client']);
        $roleVet = Role::firstOrCreate(['name' => 'veterinarian']);
        $roleWalker = Role::firstOrCreate(['name' => 'walker']); // Paseador
        $roleHotel = Role::firstOrCreate(['name' => 'hotel']);
        $roleGroomer = Role::firstOrCreate(['name' => 'groomer']); // Peluquería
        $roleShelter = Role::firstOrCreate(['name' => 'shelter']); // Albergue
        $roleTrainer = Role::firstOrCreate(['name' => 'trainer']); // Adiestrador
        $roleSitter = Role::firstOrCreate(['name' => 'pet_sitter']); // Cuidador
        $roleTaxi = Role::firstOrCreate(['name' => 'pet_taxi']); // Taxi
        $rolePhotographer = Role::firstOrCreate(['name' => 'pet_photographer']); // Fotógrafo
        $roleAdmin = Role::firstOrCreate(['name' => 'super-admin']);

        // Asignar permisos a roles (ejemplo)
        // $roleVet->givePermissionTo('manage medical records');
    }
}
