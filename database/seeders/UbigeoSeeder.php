<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Province;
use App\Models\District;
use Illuminate\Support\Facades\DB;

class UbigeoSeeder extends Seeder
{
    public function run(): void
    {
        // Desactivar fk checks para velocidad
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Department::truncate();
        Province::truncate();
        District::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Cargar Departamentos (Ejemplo parcial, se debe usar un CSV completo en prod)
        $departments = [
            ['id' => '15', 'name' => 'LIMA'],
            ['id' => '01', 'name' => 'AMAZONAS'],
            ['id' => '02', 'name' => 'ANCASH'],
            ['id' => '04', 'name' => 'AREQUIPA'],
            ['id' => '07', 'name' => 'CUSCO'],
            // ... agregar más según necesidad
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // Cargar Provincias (Ejemplo Lima)
        $provinces = [
            ['id' => '1501', 'name' => 'LIMA', 'department_id' => '15'],
            ['id' => '0401', 'name' => 'AREQUIPA', 'department_id' => '04'],
        ];

        foreach ($provinces as $prov) {
            Province::create($prov);
        }

        // Cargar Distritos (Ejemplo Lima)
        $districts = [
            ['id' => '150101', 'name' => 'LIMA', 'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150112', 'name' => 'INDEPENDENCIA', 'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150115', 'name' => 'LA VICTORIA', 'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150122', 'name' => 'MIRAFLORES', 'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150130', 'name' => 'SAN BORJA', 'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150131', 'name' => 'SAN ISIDRO', 'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150140', 'name' => 'SANTIAGO DE SURCO', 'province_id' => '1501', 'department_id' => '15'],
        ];

        foreach ($districts as $dist) {
            District::create($dist);
        }
    }
}
