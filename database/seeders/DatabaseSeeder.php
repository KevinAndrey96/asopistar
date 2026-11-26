<?php

namespace Database\Seeders;

use App\Domain\Tax\Nit;
use App\Models\FeedBrand;
use App\Models\Provider;
use App\Models\Species;
use Illuminate\Database\Seeder;

/**
 * Reference catalogs every installation starts with. Safe to run again.
 */
class DatabaseSeeder extends Seeder
{
    public const array SPECIES = [
        ['name' => 'Tilapia roja', 'scientific_name' => 'Oreochromis sp.', 'fry_until_week' => 4, 'grow_out_until_week' => 12, 'harvest_week' => 24, 'market_weight_grams' => 450],
        ['name' => 'Cachama blanca', 'scientific_name' => 'Piaractus brachypomus', 'fry_until_week' => 6, 'grow_out_until_week' => 16, 'harvest_week' => 28, 'market_weight_grams' => 600],
        ['name' => 'Trucha arcoíris', 'scientific_name' => 'Oncorhynchus mykiss', 'fry_until_week' => 8, 'grow_out_until_week' => 20, 'harvest_week' => 36, 'market_weight_grams' => 350],
        ['name' => 'Bocachico', 'scientific_name' => 'Prochilodus magdalenae', 'fry_until_week' => 6, 'grow_out_until_week' => 18, 'harvest_week' => 32, 'market_weight_grams' => 400],
    ];

    public const array FEEDS = [
        ['name' => 'Concentrado Inicio', 'protein_percent' => 45],
        ['name' => 'Concentrado Levante', 'protein_percent' => 38],
        ['name' => 'Concentrado Engorde', 'protein_percent' => 32],
        ['name' => 'Concentrado Finalización', 'protein_percent' => 28],
    ];

    public function run(): void
    {
        foreach (self::SPECIES as $species) {
            Species::query()->updateOrCreate(['name' => $species['name']], $species);
        }
        foreach (self::FEEDS as $feed) {
            FeedBrand::query()->updateOrCreate(['name' => $feed['name']], $feed);
        }
        foreach ([['Estación Piscícola Alto Magdalena', '900456781', 'Neiva'], ['Alevinos del Llano', '901234560', 'Villavicencio']] as [$name, $number, $city]) {
            Provider::query()->updateOrCreate(
                ['nit' => (string) Nit::parse($number)],
                ['name' => $name, 'city' => $city, 'contact_name' => 'Ventas', 'phone' => null],
            );
        }
    }
}
