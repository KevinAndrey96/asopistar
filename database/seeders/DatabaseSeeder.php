<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data.
     *
     * @return void
     */
    public function run()
    {
        // 1. Usuarios del Sistema
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Administrador',
            'lastname' => 'Asopistar',
            'email' => 'admin@asopistar.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('***REMOVED***'),
            'unit_number' => 'ADM-01',
            'code' => 'ADMIN-001',
            'gender' => 'Masculino',
            'estate' => 'Sede Administrativa Asopistar',
            'sidewalk' => 'Sector Principal',
            'phone' => '3105550100',
            'rol' => 'administrador',
            'is_enabled' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $producerId = DB::table('users')->insertGetId([
            'name' => 'Carlos',
            'lastname' => 'Mendoza',
            'email' => 'piscicultor@asopistar.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('***REMOVED***'),
            'unit_number' => 'LOTE-A4',
            'code' => 'PIS-042',
            'gender' => 'Masculino',
            'estate' => 'Hacienda Piscícola La Esperanza',
            'sidewalk' => 'Vereda El Carmen',
            'phone' => '3159876543',
            'rol' => 'piscicultor',
            'is_enabled' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2. Especies Acuícolas
        $species = [
            ['species' => 'Tilapia Roja', 'young_end' => 30, 'levante_start' => 31, 'levante_end' => 120, 'bait_start' => 121],
            ['species' => 'Cachama Negra', 'young_end' => 45, 'levante_start' => 46, 'levante_end' => 150, 'bait_start' => 151],
            ['species' => 'Trucha Arcoiris', 'young_end' => 40, 'levante_start' => 41, 'levante_end' => 130, 'bait_start' => 131],
            ['species' => 'Bocachico', 'young_end' => 60, 'levante_start' => 61, 'levante_end' => 180, 'bait_start' => 181],
        ];
        foreach ($species as $sp) {
            DB::table('species')->insert(array_merge($sp, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]));
        }

        // 3. Marcas de Alimento Balanceado
        $foodbrands = [
            ['name' => 'Italcol Mojarra 45%', 'protein' => 45.0],
            ['name' => 'Solla Tilapia Crecimiento 38%', 'protein' => 38.0],
            ['name' => 'Aginalv Engorde 32%', 'protein' => 32.0],
            ['name' => 'Contegral Acuicultura 28%', 'protein' => 28.0],
        ];
        foreach ($foodbrands as $fb) {
            DB::table('foodbrands')->insert(array_merge($fb, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]));
        }

        // 4. Proveedores de Insumos y Alevinos
        $providers = [
            ['name' => 'Alevinos y Genética del Llano', 'nit' => '900123456-1', 'city' => 'Villavicencio', 'phone' => 3112223344, 'representative' => 'Ing. Juan Carlos Morales'],
            ['name' => 'BioAcuícola del Magdalena', 'nit' => '800987654-2', 'city' => 'Honda', 'phone' => 3124445566, 'representative' => 'Dra. María Fernanda Gómez'],
            ['name' => 'AgroInsumos San Silvestre', 'nit' => '901345678-3', 'city' => 'Barrancabermeja', 'phone' => 3207778899, 'representative' => 'Dr. Andrés Silva'],
        ];
        foreach ($providers as $pr) {
            DB::table('providers')->insert(array_merge($pr, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]));
        }

        // 5. Estanques Piscícolas del Productor
        $pond1Id = DB::table('ponds')->insertGetId([
            'user_id' => $producerId,
            'pondcode' => 'EST-01',
            'pond_area' => 1200.0,
            'water' => 1800.0,
            'tools' => 'Red de muestreo, aireador splash 1.5HP, oxímetro digital, termómetro',
            'finish_date' => Carbon::now()->addMonths(6),
            'is_enabled' => 1,
            'created_at' => Carbon::now()->subMonths(2),
            'updated_at' => Carbon::now(),
        ]);

        $pond2Id = DB::table('ponds')->insertGetId([
            'user_id' => $producerId,
            'pondcode' => 'EST-02',
            'pond_area' => 800.0,
            'water' => 1200.0,
            'tools' => 'Geomembrana PEAD, soplador regenerativo, disco Secchi, kit pH',
            'finish_date' => Carbon::now()->addMonths(5),
            'is_enabled' => 1,
            'created_at' => Carbon::now()->subMonths(1),
            'updated_at' => Carbon::now(),
        ]);

        // 6. Siembra de Alevinos
        DB::table('alevins')->insert([
            'user_id' => $producerId,
            'pond_id' => $pond1Id,
            'species' => 'Tilapia Roja',
            'amount' => 5000.0,
            'source' => 'BioAcuícola del Magdalena',
            'date_of_entry' => Carbon::now()->subMonths(2)->toDateString(),
            'created_at' => Carbon::now()->subMonths(2),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('alevins')->insert([
            'user_id' => $producerId,
            'pond_id' => $pond2Id,
            'species' => 'Cachama Negra',
            'amount' => 3500.0,
            'source' => 'Alevinos y Genética del Llano',
            'date_of_entry' => Carbon::now()->subMonths(1)->toDateString(),
            'created_at' => Carbon::now()->subMonths(1),
            'updated_at' => Carbon::now(),
        ]);

        // 7. Registro de Alimentación Diaria
        DB::table('feedings')->insert([
            'user_id' => $producerId,
            'pond_id' => $pond1Id,
            'amount' => 15.5,
            'stage' => 'Crecimiento / Levante',
            'age' => '60 días',
            'number_of_fish' => 4920,
            'average_weight' => 185.0,
            'protein' => 38.0,
            'mark' => 'Solla Tilapia Crecimiento 38%',
            'date_of_entry' => Carbon::now()->subDays(2)->toDateString(),
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('feedings')->insert([
            'user_id' => $producerId,
            'pond_id' => $pond1Id,
            'amount' => 16.0,
            'stage' => 'Crecimiento / Levante',
            'age' => '61 días',
            'number_of_fish' => 4910,
            'average_weight' => 188.0,
            'protein' => 38.0,
            'mark' => 'Solla Tilapia Crecimiento 38%',
            'date_of_entry' => Carbon::now()->subDays(1)->toDateString(),
            'created_at' => Carbon::now()->subDays(1),
            'updated_at' => Carbon::now(),
        ]);

        // 8. Muestreos de Peso y Biometría
        DB::table('weights')->insert([
            'user_id' => $producerId,
            'pond_id' => $pond1Id,
            'agent' => 'Muestreo Quincenal de Biomasa',
            'total_weight' => 9.25,
            'fish_number' => 50,
            'average_weight' => 0.185,
            'created_at' => Carbon::now()->subDays(7),
            'updated_at' => Carbon::now(),
        ]);

        // 9. Controles Sanitarios y Bioseguridad
        DB::table('sanitaries')->insert([
            'user_id' => $producerId,
            'pond_id' => $pond1Id,
            'agent' => 'Aplicación de Sal Marina y Cal Agrícola',
            'description' => 'Desinfección preventiva y estabilización de alcalinidad total. Parámetros físico-químicos: pH 7.8, Oxígeno disuelto 6.2 mg/L, Temperatura 28.5 °C.',
            'created_at' => Carbon::now()->subDays(15),
            'updated_at' => Carbon::now(),
        ]);

        // 10. Cadena de Frío e Hielo
        DB::table('ices')->insert([
            'user_id' => $producerId,
            'pond_id' => $pond1Id,
            'species' => 'Tilapia Roja',
            'fishing_amount' => 120.0,
            'sacrifice_amount' => 118.0,
            'cooled_amount' => 118.0,
            'transport_amount' => 118.0,
            'date_of_entry' => Carbon::now()->subDays(5)->toDateString(),
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now(),
        ]);

        // 11. Cosecha Parcial
        DB::table('harvests')->insert([
            'user_id' => $producerId,
            'pond_id' => $pond1Id,
            'species' => 'Tilapia Roja',
            'amount' => 118.0,
            'fish_number' => 250,
            'average_weight' => 0.472,
            'destination' => 'Restaurante Turístico El Pescador',
            'date_of_entry' => Carbon::now()->subDays(5)->toDateString(),
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now(),
        ]);

        // 12. Productos, Recetas y Blog
        DB::table('products')->insert([
            'name' => 'Filete Fresco de Tilapia Roja (Empacado al Vacío)',
            'description' => 'Filete premium sin espinas, procesado bajo rigurosos estándares de bioseguridad y cadena de frío.',
            'price' => '18500',
            'image_url' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=600&q=80',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('recipes')->insert([
            'title' => 'Tilapia al Horno con Finas Hierbas y Cítricos',
            'description' => 'Receta gastronómica saludable elaborada con filetes frescos de tilapia de cultivo associativo.',
            'ingredients' => '4 filetes de tilapia fresca, 2 limones mandarinos, 3 dientes de ajo picados, romero fresco, aceite de oliva virgen, sal marina y pimienta.',
            'image_url' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('blogs')->insert([
            'title' => 'Mejores Prácticas de Aireación y Calidad de Agua en Estanques de Geomembrana',
            'date' => Carbon::now()->subDays(10)->toDateString(),
            'description' => 'Guía técnica para el control de parámetros físico-químicos del agua en piscicultura continental.',
            'content' => 'El monitoreo riguroso del oxígeno disuelto y la saturación durante las horas nocturnas es fundamental para maximizar la tasa de conversión alimenticia (FCR) y prevenir mortalidades por estrés anóxico en cultivos intensivos.',
            'image_url' => 'https://images.unsplash.com/photo-1524704654690-b56c05c78a00?auto=format&fit=crop&w=600&q=80',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
