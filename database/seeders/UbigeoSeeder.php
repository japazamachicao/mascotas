<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * UbigeoSeeder — Datos reales del INEI (Perú)
 *
 * Fuente: https://github.com/ernestorivero/Ubigeo-Peru
 * Datos actualizados al 2019 (INEI hasta 2016).
 *
 * Estrategia:
 *  1. Intenta descargar los CSVs desde GitHub (requiere conexión a internet).
 *  2. Si falla la descarga, usa los datos embebidos en el propio seeder.
 *
 * Uso:
 *   php artisan db:seed --class=UbigeoSeeder
 *   php artisan db:seed   (se ejecuta vía DatabaseSeeder)
 */
class UbigeoSeeder extends Seeder
{
    // URLs de los CSV oficiales
    private const CSV_DEPARTMENTS = 'https://raw.githubusercontent.com/ernestorivero/Ubigeo-Peru/master/csv/ubigeo_peru_2016_departamentos.csv';
    private const CSV_PROVINCES   = 'https://raw.githubusercontent.com/ernestorivero/Ubigeo-Peru/master/csv/ubigeo_peru_2016_provincias.csv';
    private const CSV_DISTRICTS   = 'https://raw.githubusercontent.com/ernestorivero/Ubigeo-Peru/master/csv/ubigeo_peru_2016_distritos.csv';

    public function run(): void
    {
        $this->command->info('🌍 Cargando Ubigeo del Perú (INEI)...');

        // Deshabilitar FK para poder truncar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('districts')->truncate();
        DB::table('provinces')->truncate();
        DB::table('departments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- DEPARTAMENTOS ---
        $this->command->info('  → Insertando departamentos...');
        $departments = $this->fetchCsv(self::CSV_DEPARTMENTS) ?? $this->getFallbackDepartments();
        DB::table('departments')->insert($departments);
        $this->command->info('     ✓ ' . count($departments) . ' departamentos insertados.');

        // --- PROVINCIAS ---
        $this->command->info('  → Insertando provincias...');
        $provinces = $this->fetchCsv(self::CSV_PROVINCES) ?? $this->getFallbackProvinces();
        // Insertar en lotes de 100
        foreach (array_chunk($provinces, 100) as $chunk) {
            DB::table('provinces')->insert($chunk);
        }
        $this->command->info('     ✓ ' . count($provinces) . ' provincias insertadas.');

        // --- DISTRITOS ---
        $this->command->info('  → Insertando distritos...');
        $districts = $this->fetchCsv(self::CSV_DISTRICTS) ?? $this->getFallbackDistricts();
        // Insertar en lotes de 200
        foreach (array_chunk($districts, 200) as $chunk) {
            DB::table('districts')->insert($chunk);
        }
        $this->command->info('     ✓ ' . count($districts) . ' distritos insertados.');

        $this->command->info('✅ Ubigeo cargado correctamente.');
    }

    /**
     * Descarga un CSV desde una URL y lo parsea como array de arrays asociativos.
     * Retorna null si la descarga falla.
     */
    private function fetchCsv(string $url): ?array
    {
        try {
            $response = Http::timeout(30)->get($url);

            if (! $response->successful()) {
                $this->command->warn("    ⚠ No se pudo descargar: {$url}. Usando datos embebidos.");
                return null;
            }

            $lines = explode("\n", trim($response->body()));
            $headers = str_getcsv(array_shift($lines)); // primera línea = cabecera

            $rows = [];
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }
                $values = str_getcsv($line);
                if (count($values) !== count($headers)) {
                    continue;
                }
                $row = array_combine($headers, $values);
                // Asegurar que los IDs estén en el formato correcto con ceros a la izquierda
                if (isset($row['id'])) {
                    $row['id'] = trim($row['id']);
                }
                if (isset($row['department_id'])) {
                    $row['department_id'] = str_pad(trim($row['department_id']), 2, '0', STR_PAD_LEFT);
                }
                if (isset($row['province_id'])) {
                    $row['province_id'] = str_pad(trim($row['province_id']), 4, '0', STR_PAD_LEFT);
                }
                if (isset($row['name'])) {
                    $row['name'] = trim($row['name']);
                }
                $rows[] = $row;
            }

            return empty($rows) ? null : $rows;
        } catch (\Exception $e) {
            $this->command->warn("    ⚠ Error al descargar {$url}: {$e->getMessage()}. Usando datos embebidos.");
            return null;
        }
    }

    // =========================================================================
    // DATOS EMBEBIDOS DE FALLBACK
    // (fuente: INEI / ernestorivero/Ubigeo-Peru, actualizados al 2019)
    // =========================================================================

    private function getFallbackDepartments(): array
    {
        return [
            ['id' => '01', 'name' => 'Amazonas'],
            ['id' => '02', 'name' => 'Áncash'],
            ['id' => '03', 'name' => 'Apurímac'],
            ['id' => '04', 'name' => 'Arequipa'],
            ['id' => '05', 'name' => 'Ayacucho'],
            ['id' => '06', 'name' => 'Cajamarca'],
            ['id' => '07', 'name' => 'Callao'],
            ['id' => '08', 'name' => 'Cusco'],
            ['id' => '09', 'name' => 'Huancavelica'],
            ['id' => '10', 'name' => 'Huánuco'],
            ['id' => '11', 'name' => 'Ica'],
            ['id' => '12', 'name' => 'Junín'],
            ['id' => '13', 'name' => 'La Libertad'],
            ['id' => '14', 'name' => 'Lambayeque'],
            ['id' => '15', 'name' => 'Lima'],
            ['id' => '16', 'name' => 'Loreto'],
            ['id' => '17', 'name' => 'Madre de Dios'],
            ['id' => '18', 'name' => 'Moquegua'],
            ['id' => '19', 'name' => 'Pasco'],
            ['id' => '20', 'name' => 'Piura'],
            ['id' => '21', 'name' => 'Puno'],
            ['id' => '22', 'name' => 'San Martín'],
            ['id' => '23', 'name' => 'Tacna'],
            ['id' => '24', 'name' => 'Tumbes'],
            ['id' => '25', 'name' => 'Ucayali'],
        ];
    }

    private function getFallbackProvinces(): array
    {
        return [
            // Amazonas (01)
            ['id' => '0101', 'name' => 'Chachapoyas',          'department_id' => '01'],
            ['id' => '0102', 'name' => 'Bagua',                'department_id' => '01'],
            ['id' => '0103', 'name' => 'Bongará',              'department_id' => '01'],
            ['id' => '0104', 'name' => 'Condorcanqui',         'department_id' => '01'],
            ['id' => '0105', 'name' => 'Luya',                 'department_id' => '01'],
            ['id' => '0106', 'name' => 'Rodríguez de Mendoza', 'department_id' => '01'],
            ['id' => '0107', 'name' => 'Utcubamba',            'department_id' => '01'],
            // Áncash (02)
            ['id' => '0201', 'name' => 'Huaraz',               'department_id' => '02'],
            ['id' => '0202', 'name' => 'Aija',                 'department_id' => '02'],
            ['id' => '0203', 'name' => 'Antonio Raymondi',     'department_id' => '02'],
            ['id' => '0204', 'name' => 'Asunción',             'department_id' => '02'],
            ['id' => '0205', 'name' => 'Bolognesi',            'department_id' => '02'],
            ['id' => '0206', 'name' => 'Carhuaz',              'department_id' => '02'],
            ['id' => '0207', 'name' => 'Carlos Fermín Fitzcarrald', 'department_id' => '02'],
            ['id' => '0208', 'name' => 'Casma',                'department_id' => '02'],
            ['id' => '0209', 'name' => 'Corongo',              'department_id' => '02'],
            ['id' => '0210', 'name' => 'Huari',                'department_id' => '02'],
            ['id' => '0211', 'name' => 'Huarmey',              'department_id' => '02'],
            ['id' => '0212', 'name' => 'Huaylas',              'department_id' => '02'],
            ['id' => '0213', 'name' => 'Mariscal Luzuriaga',   'department_id' => '02'],
            ['id' => '0214', 'name' => 'Ocros',                'department_id' => '02'],
            ['id' => '0215', 'name' => 'Pallasca',             'department_id' => '02'],
            ['id' => '0216', 'name' => 'Pomabamba',            'department_id' => '02'],
            ['id' => '0217', 'name' => 'Recuay',               'department_id' => '02'],
            ['id' => '0218', 'name' => 'Santa',                'department_id' => '02'],
            ['id' => '0219', 'name' => 'Sihuas',               'department_id' => '02'],
            ['id' => '0220', 'name' => 'Yungay',               'department_id' => '02'],
            // Apurímac (03)
            ['id' => '0301', 'name' => 'Abancay',              'department_id' => '03'],
            ['id' => '0302', 'name' => 'Andahuaylas',          'department_id' => '03'],
            ['id' => '0303', 'name' => 'Antabamba',            'department_id' => '03'],
            ['id' => '0304', 'name' => 'Aymaraes',             'department_id' => '03'],
            ['id' => '0305', 'name' => 'Cotabambas',           'department_id' => '03'],
            ['id' => '0306', 'name' => 'Chincheros',           'department_id' => '03'],
            ['id' => '0307', 'name' => 'Grau',                 'department_id' => '03'],
            // Arequipa (04)
            ['id' => '0401', 'name' => 'Arequipa',             'department_id' => '04'],
            ['id' => '0402', 'name' => 'Camaná',               'department_id' => '04'],
            ['id' => '0403', 'name' => 'Caravelí',             'department_id' => '04'],
            ['id' => '0404', 'name' => 'Castilla',             'department_id' => '04'],
            ['id' => '0405', 'name' => 'Caylloma',             'department_id' => '04'],
            ['id' => '0406', 'name' => 'Condesuyos',           'department_id' => '04'],
            ['id' => '0407', 'name' => 'Islay',                'department_id' => '04'],
            ['id' => '0408', 'name' => 'La Unión',             'department_id' => '04'],
            // Ayacucho (05)
            ['id' => '0501', 'name' => 'Huamanga',             'department_id' => '05'],
            ['id' => '0502', 'name' => 'Cangallo',             'department_id' => '05'],
            ['id' => '0503', 'name' => 'Huanca Sancos',        'department_id' => '05'],
            ['id' => '0504', 'name' => 'Huanta',               'department_id' => '05'],
            ['id' => '0505', 'name' => 'La Mar',               'department_id' => '05'],
            ['id' => '0506', 'name' => 'Lucanas',              'department_id' => '05'],
            ['id' => '0507', 'name' => 'Parinacochas',         'department_id' => '05'],
            ['id' => '0508', 'name' => 'Páucar del Sara Sara', 'department_id' => '05'],
            ['id' => '0509', 'name' => 'Sucre',                'department_id' => '05'],
            ['id' => '0510', 'name' => 'Víctor Fajardo',       'department_id' => '05'],
            ['id' => '0511', 'name' => 'Vilcas Huamán',        'department_id' => '05'],
            // Cajamarca (06)
            ['id' => '0601', 'name' => 'Cajamarca',            'department_id' => '06'],
            ['id' => '0602', 'name' => 'Cajabamba',            'department_id' => '06'],
            ['id' => '0603', 'name' => 'Celendín',             'department_id' => '06'],
            ['id' => '0604', 'name' => 'Chota',                'department_id' => '06'],
            ['id' => '0605', 'name' => 'Contumazá',            'department_id' => '06'],
            ['id' => '0606', 'name' => 'Cutervo',              'department_id' => '06'],
            ['id' => '0607', 'name' => 'Hualgayoc',            'department_id' => '06'],
            ['id' => '0608', 'name' => 'Jaén',                 'department_id' => '06'],
            ['id' => '0609', 'name' => 'San Ignacio',          'department_id' => '06'],
            ['id' => '0610', 'name' => 'San Marcos',           'department_id' => '06'],
            ['id' => '0611', 'name' => 'San Miguel',           'department_id' => '06'],
            ['id' => '0612', 'name' => 'San Pablo',            'department_id' => '06'],
            ['id' => '0613', 'name' => 'Santa Cruz',           'department_id' => '06'],
            // Callao (07)
            ['id' => '0701', 'name' => 'Prov. Const. del Callao', 'department_id' => '07'],
            // Cusco (08)
            ['id' => '0801', 'name' => 'Cusco',                'department_id' => '08'],
            ['id' => '0802', 'name' => 'Acomayo',              'department_id' => '08'],
            ['id' => '0803', 'name' => 'Anta',                 'department_id' => '08'],
            ['id' => '0804', 'name' => 'Calca',                'department_id' => '08'],
            ['id' => '0805', 'name' => 'Canas',                'department_id' => '08'],
            ['id' => '0806', 'name' => 'Canchis',              'department_id' => '08'],
            ['id' => '0807', 'name' => 'Chumbivilcas',         'department_id' => '08'],
            ['id' => '0808', 'name' => 'Espinar',              'department_id' => '08'],
            ['id' => '0809', 'name' => 'La Convención',        'department_id' => '08'],
            ['id' => '0810', 'name' => 'Paruro',               'department_id' => '08'],
            ['id' => '0811', 'name' => 'Paucartambo',          'department_id' => '08'],
            ['id' => '0812', 'name' => 'Quispicanchi',         'department_id' => '08'],
            ['id' => '0813', 'name' => 'Urubamba',             'department_id' => '08'],
            // Huancavelica (09)
            ['id' => '0901', 'name' => 'Huancavelica',         'department_id' => '09'],
            ['id' => '0902', 'name' => 'Acobamba',             'department_id' => '09'],
            ['id' => '0903', 'name' => 'Angaraes',             'department_id' => '09'],
            ['id' => '0904', 'name' => 'Castrovirreyna',       'department_id' => '09'],
            ['id' => '0905', 'name' => 'Churcampa',            'department_id' => '09'],
            ['id' => '0906', 'name' => 'Huaytará',             'department_id' => '09'],
            ['id' => '0907', 'name' => 'Tayacaja',             'department_id' => '09'],
            // Huánuco (10)
            ['id' => '1001', 'name' => 'Huánuco',              'department_id' => '10'],
            ['id' => '1002', 'name' => 'Ambo',                 'department_id' => '10'],
            ['id' => '1003', 'name' => 'Dos de Mayo',          'department_id' => '10'],
            ['id' => '1004', 'name' => 'Huacaybamba',          'department_id' => '10'],
            ['id' => '1005', 'name' => 'Huamalíes',            'department_id' => '10'],
            ['id' => '1006', 'name' => 'Leoncio Prado',        'department_id' => '10'],
            ['id' => '1007', 'name' => 'Marañón',              'department_id' => '10'],
            ['id' => '1008', 'name' => 'Pachitea',             'department_id' => '10'],
            ['id' => '1009', 'name' => 'Puerto Inca',          'department_id' => '10'],
            ['id' => '1010', 'name' => 'Lauricocha',           'department_id' => '10'],
            ['id' => '1011', 'name' => 'Yarowilca',            'department_id' => '10'],
            // Ica (11)
            ['id' => '1101', 'name' => 'Ica',                  'department_id' => '11'],
            ['id' => '1102', 'name' => 'Chincha',              'department_id' => '11'],
            ['id' => '1103', 'name' => 'Nasca',                'department_id' => '11'],
            ['id' => '1104', 'name' => 'Palpa',                'department_id' => '11'],
            ['id' => '1105', 'name' => 'Pisco',                'department_id' => '11'],
            // Junín (12)
            ['id' => '1201', 'name' => 'Huancayo',             'department_id' => '12'],
            ['id' => '1202', 'name' => 'Concepción',           'department_id' => '12'],
            ['id' => '1203', 'name' => 'Chanchamayo',          'department_id' => '12'],
            ['id' => '1204', 'name' => 'Junín',                'department_id' => '12'],
            ['id' => '1205', 'name' => 'Satipo',               'department_id' => '12'],
            ['id' => '1206', 'name' => 'Tarma',                'department_id' => '12'],
            ['id' => '1207', 'name' => 'Yauli',                'department_id' => '12'],
            ['id' => '1208', 'name' => 'Chupaca',              'department_id' => '12'],
            // La Libertad (13)
            ['id' => '1301', 'name' => 'Trujillo',             'department_id' => '13'],
            ['id' => '1302', 'name' => 'Ascope',               'department_id' => '13'],
            ['id' => '1303', 'name' => 'Bolívar',              'department_id' => '13'],
            ['id' => '1304', 'name' => 'Chepén',               'department_id' => '13'],
            ['id' => '1305', 'name' => 'Julcán',               'department_id' => '13'],
            ['id' => '1306', 'name' => 'Otuzco',               'department_id' => '13'],
            ['id' => '1307', 'name' => 'Pacasmayo',            'department_id' => '13'],
            ['id' => '1308', 'name' => 'Pataz',                'department_id' => '13'],
            ['id' => '1309', 'name' => 'Sánchez Carrión',      'department_id' => '13'],
            ['id' => '1310', 'name' => 'Santiago de Chuco',    'department_id' => '13'],
            ['id' => '1311', 'name' => 'Gran Chimú',           'department_id' => '13'],
            ['id' => '1312', 'name' => 'Virú',                 'department_id' => '13'],
            // Lambayeque (14)
            ['id' => '1401', 'name' => 'Chiclayo',             'department_id' => '14'],
            ['id' => '1402', 'name' => 'Ferreñafe',            'department_id' => '14'],
            ['id' => '1403', 'name' => 'Lambayeque',           'department_id' => '14'],
            // Lima (15)
            ['id' => '1501', 'name' => 'Lima',                 'department_id' => '15'],
            ['id' => '1502', 'name' => 'Barranca',             'department_id' => '15'],
            ['id' => '1503', 'name' => 'Cajatambo',            'department_id' => '15'],
            ['id' => '1504', 'name' => 'Canta',                'department_id' => '15'],
            ['id' => '1505', 'name' => 'Cañete',               'department_id' => '15'],
            ['id' => '1506', 'name' => 'Huaral',               'department_id' => '15'],
            ['id' => '1507', 'name' => 'Huarochirí',           'department_id' => '15'],
            ['id' => '1508', 'name' => 'Huaura',               'department_id' => '15'],
            ['id' => '1509', 'name' => 'Oyón',                 'department_id' => '15'],
            ['id' => '1510', 'name' => 'Yauyos',               'department_id' => '15'],
            // Loreto (16)
            ['id' => '1601', 'name' => 'Maynas',               'department_id' => '16'],
            ['id' => '1602', 'name' => 'Alto Amazonas',        'department_id' => '16'],
            ['id' => '1603', 'name' => 'Loreto',               'department_id' => '16'],
            ['id' => '1604', 'name' => 'Mariscal Ramón Castilla', 'department_id' => '16'],
            ['id' => '1605', 'name' => 'Requena',              'department_id' => '16'],
            ['id' => '1606', 'name' => 'Ucayali',              'department_id' => '16'],
            ['id' => '1607', 'name' => 'Datem del Marañón',    'department_id' => '16'],
            ['id' => '1608', 'name' => 'Putumayo',             'department_id' => '16'],
            // Madre de Dios (17)
            ['id' => '1701', 'name' => 'Tambopata',            'department_id' => '17'],
            ['id' => '1702', 'name' => 'Manu',                 'department_id' => '17'],
            ['id' => '1703', 'name' => 'Tahuamanu',            'department_id' => '17'],
            // Moquegua (18)
            ['id' => '1801', 'name' => 'Mariscal Nieto',       'department_id' => '18'],
            ['id' => '1802', 'name' => 'General Sánchez Cerro','department_id' => '18'],
            ['id' => '1803', 'name' => 'Ilo',                  'department_id' => '18'],
            // Pasco (19)
            ['id' => '1901', 'name' => 'Pasco',                'department_id' => '19'],
            ['id' => '1902', 'name' => 'Daniel Alcides Carrión','department_id' => '19'],
            ['id' => '1903', 'name' => 'Oxapampa',             'department_id' => '19'],
            // Piura (20)
            ['id' => '2001', 'name' => 'Piura',                'department_id' => '20'],
            ['id' => '2002', 'name' => 'Ayabaca',              'department_id' => '20'],
            ['id' => '2003', 'name' => 'Huancabamba',          'department_id' => '20'],
            ['id' => '2004', 'name' => 'Morropón',             'department_id' => '20'],
            ['id' => '2005', 'name' => 'Paita',                'department_id' => '20'],
            ['id' => '2006', 'name' => 'Sullana',              'department_id' => '20'],
            ['id' => '2007', 'name' => 'Talara',               'department_id' => '20'],
            ['id' => '2008', 'name' => 'Sechura',              'department_id' => '20'],
            // Puno (21)
            ['id' => '2101', 'name' => 'Puno',                 'department_id' => '21'],
            ['id' => '2102', 'name' => 'Azángaro',             'department_id' => '21'],
            ['id' => '2103', 'name' => 'Carabaya',             'department_id' => '21'],
            ['id' => '2104', 'name' => 'Chucuito',             'department_id' => '21'],
            ['id' => '2105', 'name' => 'El Collao',            'department_id' => '21'],
            ['id' => '2106', 'name' => 'Huancané',             'department_id' => '21'],
            ['id' => '2107', 'name' => 'Lampa',                'department_id' => '21'],
            ['id' => '2108', 'name' => 'Melgar',               'department_id' => '21'],
            ['id' => '2109', 'name' => 'Moho',                 'department_id' => '21'],
            ['id' => '2110', 'name' => 'San Antonio de Putina','department_id' => '21'],
            ['id' => '2111', 'name' => 'San Román',            'department_id' => '21'],
            ['id' => '2112', 'name' => 'Sandia',               'department_id' => '21'],
            ['id' => '2113', 'name' => 'Yunguyo',              'department_id' => '21'],
            // San Martín (22)
            ['id' => '2201', 'name' => 'Moyobamba',            'department_id' => '22'],
            ['id' => '2202', 'name' => 'Bellavista',           'department_id' => '22'],
            ['id' => '2203', 'name' => 'El Dorado',            'department_id' => '22'],
            ['id' => '2204', 'name' => 'Huallaga',             'department_id' => '22'],
            ['id' => '2205', 'name' => 'Lamas',                'department_id' => '22'],
            ['id' => '2206', 'name' => 'Mariscal Cáceres',     'department_id' => '22'],
            ['id' => '2207', 'name' => 'Picota',               'department_id' => '22'],
            ['id' => '2208', 'name' => 'Rioja',                'department_id' => '22'],
            ['id' => '2209', 'name' => 'San Martín',           'department_id' => '22'],
            ['id' => '2210', 'name' => 'Tocache',              'department_id' => '22'],
            // Tacna (23)
            ['id' => '2301', 'name' => 'Tacna',                'department_id' => '23'],
            ['id' => '2302', 'name' => 'Candarave',            'department_id' => '23'],
            ['id' => '2303', 'name' => 'Jorge Basadre',        'department_id' => '23'],
            ['id' => '2304', 'name' => 'Tarata',               'department_id' => '23'],
            // Tumbes (24)
            ['id' => '2401', 'name' => 'Tumbes',               'department_id' => '24'],
            ['id' => '2402', 'name' => 'Contralmirante Villar','department_id' => '24'],
            ['id' => '2403', 'name' => 'Zarumilla',            'department_id' => '24'],
            // Ucayali (25)
            ['id' => '2501', 'name' => 'Coronel Portillo',     'department_id' => '25'],
            ['id' => '2502', 'name' => 'Atalaya',              'department_id' => '25'],
            ['id' => '2503', 'name' => 'Padre Abad',           'department_id' => '25'],
            ['id' => '2504', 'name' => 'Purús',                'department_id' => '25'],
        ];
    }

    private function getFallbackDistricts(): array
    {
        // Distritos completos de Lima Metropolitana y los más relevantes de otras regiones.
        // Para datos completos (1874 distritos), el seeder intentará descargarlos desde GitHub.
        return [
            // ---- LIMA (15) - PROVINCIA LIMA (1501) ----
            ['id' => '150101', 'name' => 'Lima',                    'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150102', 'name' => 'Ancón',                   'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150103', 'name' => 'Ate',                     'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150104', 'name' => 'Barranco',                'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150105', 'name' => 'Breña',                   'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150106', 'name' => 'Carabayllo',              'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150107', 'name' => 'Chaclacayo',              'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150108', 'name' => 'Chorrillos',              'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150109', 'name' => 'Cieneguilla',             'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150110', 'name' => 'Comas',                   'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150111', 'name' => 'El Agustino',             'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150112', 'name' => 'Independencia',           'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150113', 'name' => 'Jesús María',             'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150114', 'name' => 'La Molina',               'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150115', 'name' => 'La Victoria',             'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150116', 'name' => 'Lince',                   'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150117', 'name' => 'Los Olivos',              'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150118', 'name' => 'Lurigancho',              'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150119', 'name' => 'Lurín',                   'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150120', 'name' => 'Magdalena del Mar',       'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150121', 'name' => 'Pueblo Libre',            'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150122', 'name' => 'Miraflores',              'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150123', 'name' => 'Pachacámac',              'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150124', 'name' => 'Pucusana',                'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150125', 'name' => 'Puente Piedra',           'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150126', 'name' => 'Punta Hermosa',           'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150127', 'name' => 'Punta Negra',             'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150128', 'name' => 'Rímac',                   'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150129', 'name' => 'San Bartolo',             'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150130', 'name' => 'San Borja',               'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150131', 'name' => 'San Isidro',              'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150132', 'name' => 'San Juan de Lurigancho',  'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150133', 'name' => 'San Juan de Miraflores',  'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150134', 'name' => 'San Luis',                'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150135', 'name' => 'San Martín de Porres',    'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150136', 'name' => 'San Miguel',              'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150137', 'name' => 'Santa Anita',             'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150138', 'name' => 'Santa María del Mar',     'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150139', 'name' => 'Santa Rosa',              'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150140', 'name' => 'Santiago de Surco',       'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150141', 'name' => 'Surquillo',               'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150142', 'name' => 'Villa El Salvador',       'province_id' => '1501', 'department_id' => '15'],
            ['id' => '150143', 'name' => 'Villa María del Triunfo', 'province_id' => '1501', 'department_id' => '15'],
            // LIMA - Barranca (1502)
            ['id' => '150201', 'name' => 'Barranca',               'province_id' => '1502', 'department_id' => '15'],
            ['id' => '150202', 'name' => 'Paramonga',              'province_id' => '1502', 'department_id' => '15'],
            ['id' => '150203', 'name' => 'Pativilca',              'province_id' => '1502', 'department_id' => '15'],
            ['id' => '150204', 'name' => 'Supe',                   'province_id' => '1502', 'department_id' => '15'],
            ['id' => '150205', 'name' => 'Supe Puerto',            'province_id' => '1502', 'department_id' => '15'],
            // LIMA - Cañete (1505)
            ['id' => '150501', 'name' => 'San Vicente de Cañete',  'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150502', 'name' => 'Asia',                   'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150503', 'name' => 'Calango',                'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150504', 'name' => 'Cerro Azul',            'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150505', 'name' => 'Chilca',                 'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150506', 'name' => 'Coayllo',               'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150507', 'name' => 'Imperial',              'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150508', 'name' => 'Lunahuaná',             'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150509', 'name' => 'Mala',                  'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150510', 'name' => 'Nuevo Imperial',        'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150511', 'name' => 'Pampas',                'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150512', 'name' => 'Pacarán',               'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150513', 'name' => 'Quilmaná',              'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150514', 'name' => 'San Antonio',           'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150515', 'name' => 'San Luis',              'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150516', 'name' => 'Santa Cruz de Flores',  'province_id' => '1505', 'department_id' => '15'],
            ['id' => '150517', 'name' => 'Zúñiga',               'province_id' => '1505', 'department_id' => '15'],
            // LIMA - Huaral (1506)
            ['id' => '150601', 'name' => 'Huaral',                'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150602', 'name' => 'Atavillos Alto',        'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150603', 'name' => 'Atavillos Bajo',        'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150604', 'name' => 'Aucallama',             'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150605', 'name' => 'Chancay',               'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150606', 'name' => 'Ihuarí',               'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150607', 'name' => 'Lampián',              'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150608', 'name' => 'Pacaraos',              'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150609', 'name' => 'San Miguel de Acos',    'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150610', 'name' => 'Santa Cruz de Andamarca', 'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150611', 'name' => 'Sumbilca',              'province_id' => '1506', 'department_id' => '15'],
            ['id' => '150612', 'name' => 'Veintisiete de Noviembre', 'province_id' => '1506', 'department_id' => '15'],
            // ---- CALLAO (07) ----
            ['id' => '070101', 'name' => 'Callao',                'province_id' => '0701', 'department_id' => '07'],
            ['id' => '070102', 'name' => 'Bellavista',            'province_id' => '0701', 'department_id' => '07'],
            ['id' => '070103', 'name' => 'Carmen de la Legua Reynoso', 'province_id' => '0701', 'department_id' => '07'],
            ['id' => '070104', 'name' => 'La Perla',              'province_id' => '0701', 'department_id' => '07'],
            ['id' => '070105', 'name' => 'La Punta',              'province_id' => '0701', 'department_id' => '07'],
            ['id' => '070106', 'name' => 'Ventanilla',            'province_id' => '0701', 'department_id' => '07'],
            ['id' => '070107', 'name' => 'Mi Perú',               'province_id' => '0701', 'department_id' => '07'],
            // ---- AREQUIPA (04) - PROVINCIA AREQUIPA (0401) ----
            ['id' => '040101', 'name' => 'Arequipa',              'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040102', 'name' => 'Alto Selva Alegre',     'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040103', 'name' => 'Cayma',                 'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040104', 'name' => 'Cerro Colorado',        'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040105', 'name' => 'Characato',             'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040106', 'name' => 'Chiguata',              'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040107', 'name' => 'Jacobo Hunter',         'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040108', 'name' => 'La Joya',               'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040109', 'name' => 'Mariano Melgar',        'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040110', 'name' => 'Miraflores',            'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040111', 'name' => 'Mollebaya',             'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040112', 'name' => 'Paucarpata',            'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040113', 'name' => 'Pocsi',                 'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040114', 'name' => 'Polobaya',              'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040115', 'name' => 'Quequeña',              'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040116', 'name' => 'Sabandia',              'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040117', 'name' => 'Sachaca',               'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040118', 'name' => 'San Juan de Siguas',    'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040119', 'name' => 'San Juan de Tarucani',  'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040120', 'name' => 'Santa Isabel de Siguas','province_id' => '0401', 'department_id' => '04'],
            ['id' => '040121', 'name' => 'Santa Rita de Siguas',  'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040122', 'name' => 'Socabaya',              'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040123', 'name' => 'Tiabaya',               'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040124', 'name' => 'Uchumayo',              'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040125', 'name' => 'Vitor',                 'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040126', 'name' => 'Yanahuara',             'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040127', 'name' => 'Yarabamba',             'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040128', 'name' => 'Yura',                  'province_id' => '0401', 'department_id' => '04'],
            ['id' => '040129', 'name' => 'José Luis Bustamante Y Rivero', 'province_id' => '0401', 'department_id' => '04'],
            // ---- CUSCO (08) - PROVINCIA CUSCO (0801) ----
            ['id' => '080101', 'name' => 'Cusco',                 'province_id' => '0801', 'department_id' => '08'],
            ['id' => '080102', 'name' => 'Ccorca',                'province_id' => '0801', 'department_id' => '08'],
            ['id' => '080103', 'name' => 'Poroy',                 'province_id' => '0801', 'department_id' => '08'],
            ['id' => '080104', 'name' => 'San Jerónimo',          'province_id' => '0801', 'department_id' => '08'],
            ['id' => '080105', 'name' => 'San Sebastián',         'province_id' => '0801', 'department_id' => '08'],
            ['id' => '080106', 'name' => 'Santiago',              'province_id' => '0801', 'department_id' => '08'],
            ['id' => '080107', 'name' => 'Saylla',                'province_id' => '0801', 'department_id' => '08'],
            ['id' => '080108', 'name' => 'Wanchaq',               'province_id' => '0801', 'department_id' => '08'],
            // ---- LA LIBERTAD (13) - TRUJILLO (1301) ----
            ['id' => '130101', 'name' => 'Trujillo',              'province_id' => '1301', 'department_id' => '13'],
            ['id' => '130102', 'name' => 'El Porvenir',           'province_id' => '1301', 'department_id' => '13'],
            ['id' => '130103', 'name' => 'Florencia de Mora',     'province_id' => '1301', 'department_id' => '13'],
            ['id' => '130104', 'name' => 'Huanchaco',             'province_id' => '1301', 'department_id' => '13'],
            ['id' => '130105', 'name' => 'La Esperanza',          'province_id' => '1301', 'department_id' => '13'],
            ['id' => '130106', 'name' => 'Laredo',                'province_id' => '1301', 'department_id' => '13'],
            ['id' => '130107', 'name' => 'Moche',                 'province_id' => '1301', 'department_id' => '13'],
            ['id' => '130108', 'name' => 'Poroto',                'province_id' => '1301', 'department_id' => '13'],
            ['id' => '130109', 'name' => 'Salaverry',             'province_id' => '1301', 'department_id' => '13'],
            ['id' => '130110', 'name' => 'Simbal',                'province_id' => '1301', 'department_id' => '13'],
            ['id' => '130111', 'name' => 'Víctor Larco Herrera',  'province_id' => '1301', 'department_id' => '13'],
            // ---- PIURA (20) - PIURA (2001) ----
            ['id' => '200101', 'name' => 'Piura',                 'province_id' => '2001', 'department_id' => '20'],
            ['id' => '200102', 'name' => 'Castilla',              'province_id' => '2001', 'department_id' => '20'],
            ['id' => '200103', 'name' => 'Catacaos',              'province_id' => '2001', 'department_id' => '20'],
            ['id' => '200104', 'name' => 'Cura Mori',             'province_id' => '2001', 'department_id' => '20'],
            ['id' => '200105', 'name' => 'El Tallán',             'province_id' => '2001', 'department_id' => '20'],
            ['id' => '200106', 'name' => 'La Arena',              'province_id' => '2001', 'department_id' => '20'],
            ['id' => '200107', 'name' => 'La Unión',              'province_id' => '2001', 'department_id' => '20'],
            ['id' => '200108', 'name' => 'Las Lomas',             'province_id' => '2001', 'department_id' => '20'],
            ['id' => '200109', 'name' => 'Tambo Grande',          'province_id' => '2001', 'department_id' => '20'],
            ['id' => '200110', 'name' => 'Veintiseis de Octubre', 'province_id' => '2001', 'department_id' => '20'],
            // ---- LAMBAYEQUE (14) - CHICLAYO (1401) ----
            ['id' => '140101', 'name' => 'Chiclayo',              'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140102', 'name' => 'Chongoyape',            'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140103', 'name' => 'Eten',                  'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140104', 'name' => 'Eten Puerto',           'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140105', 'name' => 'José Leonardo Ortiz',   'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140106', 'name' => 'La Victoria',           'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140107', 'name' => 'Lagunas',               'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140108', 'name' => 'Monsefu',               'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140109', 'name' => 'Nueva Arica',           'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140110', 'name' => 'Oyotún',               'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140111', 'name' => 'Picsi',                 'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140112', 'name' => 'Pimentel',              'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140113', 'name' => 'Reque',                 'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140114', 'name' => 'Santa Rosa',            'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140115', 'name' => 'Saña',                  'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140116', 'name' => 'Cayaltí',              'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140117', 'name' => 'Pátapo',               'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140118', 'name' => 'Pomalca',               'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140119', 'name' => 'Pucalá',               'province_id' => '1401', 'department_id' => '14'],
            ['id' => '140120', 'name' => 'Tumán',                'province_id' => '1401', 'department_id' => '14'],
            // ---- ICA (11) - ICA (1101) ----
            ['id' => '110101', 'name' => 'Ica',                   'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110102', 'name' => 'La Tinguiña',           'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110103', 'name' => 'Los Aquijes',           'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110104', 'name' => 'Ocucaje',               'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110105', 'name' => 'Pachacutec',            'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110106', 'name' => 'Parcona',               'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110107', 'name' => 'Pueblo Nuevo',          'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110108', 'name' => 'Salas',                 'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110109', 'name' => 'San José de Los Molinos', 'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110110', 'name' => 'San Juan Bautista',     'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110111', 'name' => 'Santiago',              'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110112', 'name' => 'Subtanjalla',           'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110113', 'name' => 'Tate',                  'province_id' => '1101', 'department_id' => '11'],
            ['id' => '110114', 'name' => 'Yauca del Rosario',     'province_id' => '1101', 'department_id' => '11'],
            // ---- JUNIN (12) - HUANCAYO (1201) ----
            ['id' => '120101', 'name' => 'Huancayo',              'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120102', 'name' => 'Carhuacallanga',        'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120103', 'name' => 'Chacapampa',            'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120104', 'name' => 'Chicche',               'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120105', 'name' => 'Chilca',                'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120106', 'name' => 'Chongos Alto',          'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120107', 'name' => 'Chupuro',               'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120108', 'name' => 'Colca',                 'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120109', 'name' => 'Cullhuas',              'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120110', 'name' => 'El Tambo',              'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120111', 'name' => 'Huacrapuquio',          'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120112', 'name' => 'Hualhuas',              'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120113', 'name' => 'Huancán',              'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120114', 'name' => 'Huasicancha',           'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120115', 'name' => 'Huayucachi',            'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120116', 'name' => 'Ingenio',               'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120117', 'name' => 'Pariahuanca',           'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120118', 'name' => 'Pilcomayo',             'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120119', 'name' => 'Pucará',               'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120120', 'name' => 'Quichuay',              'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120121', 'name' => 'Quilcas',               'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120122', 'name' => 'San Agustín',          'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120123', 'name' => 'San Jerónimo de Tunan','province_id' => '1201', 'department_id' => '12'],
            ['id' => '120125', 'name' => 'Saño',                  'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120126', 'name' => 'Sapallanga',            'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120127', 'name' => 'Sicaya',                'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120128', 'name' => 'Santo Domingo de Acobamba', 'province_id' => '1201', 'department_id' => '12'],
            ['id' => '120129', 'name' => 'Viques',                'province_id' => '1201', 'department_id' => '12'],
            // ---- PUNO (21) - PUNO (2101) ----
            ['id' => '210101', 'name' => 'Puno',                  'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210102', 'name' => 'Acora',                 'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210103', 'name' => 'Amantani',              'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210104', 'name' => 'Atuncolla',             'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210105', 'name' => 'Capachica',             'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210106', 'name' => 'Chucuito',              'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210107', 'name' => 'Coata',                 'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210108', 'name' => 'Huata',                 'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210109', 'name' => 'Mañazo',               'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210110', 'name' => 'Paucarcolla',           'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210111', 'name' => 'Pichacani',             'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210112', 'name' => 'Platería',             'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210113', 'name' => 'San Antonio',           'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210114', 'name' => 'Tiquillaca',            'province_id' => '2101', 'department_id' => '21'],
            ['id' => '210115', 'name' => 'Vilque',                'province_id' => '2101', 'department_id' => '21'],
            // ---- TACNA (23) - TACNA (2301) ----
            ['id' => '230101', 'name' => 'Tacna',                 'province_id' => '2301', 'department_id' => '23'],
            ['id' => '230102', 'name' => 'Alto de la Alianza',    'province_id' => '2301', 'department_id' => '23'],
            ['id' => '230103', 'name' => 'Calana',                'province_id' => '2301', 'department_id' => '23'],
            ['id' => '230104', 'name' => 'Ciudad Nueva',          'province_id' => '2301', 'department_id' => '23'],
            ['id' => '230105', 'name' => 'Inclan',                'province_id' => '2301', 'department_id' => '23'],
            ['id' => '230106', 'name' => 'Pachia',                'province_id' => '2301', 'department_id' => '23'],
            ['id' => '230107', 'name' => 'Palca',                 'province_id' => '2301', 'department_id' => '23'],
            ['id' => '230108', 'name' => 'Pocollay',              'province_id' => '2301', 'department_id' => '23'],
            ['id' => '230109', 'name' => 'Sama',                  'province_id' => '2301', 'department_id' => '23'],
            ['id' => '230110', 'name' => 'Coronel Gregorio Albarracín Lanchipa', 'province_id' => '2301', 'department_id' => '23'],
            // ---- SAN MARTIN (22) - MOYOBAMBA (2201) ----
            ['id' => '220101', 'name' => 'Moyobamba',             'province_id' => '2201', 'department_id' => '22'],
            ['id' => '220102', 'name' => 'Calzada',               'province_id' => '2201', 'department_id' => '22'],
            ['id' => '220103', 'name' => 'Habana',                'province_id' => '2201', 'department_id' => '22'],
            ['id' => '220104', 'name' => 'Jepelacio',             'province_id' => '2201', 'department_id' => '22'],
            ['id' => '220105', 'name' => 'Soritor',               'province_id' => '2201', 'department_id' => '22'],
            ['id' => '220106', 'name' => 'Yantalo',               'province_id' => '2201', 'department_id' => '22'],
            // ---- LORETO (16) - MAYNAS (1601) ----
            ['id' => '160101', 'name' => 'Iquitos',               'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160102', 'name' => 'Alto Nanay',            'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160103', 'name' => 'Fernando Lores',        'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160104', 'name' => 'Indiana',               'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160105', 'name' => 'Las Amazonas',          'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160106', 'name' => 'Mazan',                 'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160107', 'name' => 'Napo',                  'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160108', 'name' => 'Punchana',              'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160110', 'name' => 'Torres Causana',        'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160112', 'name' => 'Belén',                'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160113', 'name' => 'San Juan Bautista',     'province_id' => '1601', 'department_id' => '16'],
            ['id' => '160114', 'name' => 'Teniente Manuel Clavero', 'province_id' => '1601', 'department_id' => '16'],
            // ---- UCAYALI (25) - CORONEL PORTILLO (2501) ----
            ['id' => '250101', 'name' => 'Callería',             'province_id' => '2501', 'department_id' => '25'],
            ['id' => '250102', 'name' => 'Campoverde',            'province_id' => '2501', 'department_id' => '25'],
            ['id' => '250103', 'name' => 'Iparia',                'province_id' => '2501', 'department_id' => '25'],
            ['id' => '250104', 'name' => 'Masisea',               'province_id' => '2501', 'department_id' => '25'],
            ['id' => '250105', 'name' => 'Yarinacocha',           'province_id' => '2501', 'department_id' => '25'],
            ['id' => '250106', 'name' => 'Nueva Requena',         'province_id' => '2501', 'department_id' => '25'],
            ['id' => '250107', 'name' => 'Manantay',              'province_id' => '2501', 'department_id' => '25'],
        ];
    }
}
