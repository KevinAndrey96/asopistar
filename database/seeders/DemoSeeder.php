<?php

namespace Database\Seeders;

use App\Domain\Aquaculture\GrowthStage;
use App\Enums\Gender;
use App\Enums\Role;
use App\Models\Cycle;
use App\Models\FeedBrand;
use App\Models\Pond;
use App\Models\Post;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Recipe;
use App\Models\Species;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Demo association with farmers, cycles in every stage and public content.
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DatabaseSeeder::class);

        $password = config('demo.password');
        if (! is_string($password) || $password === '') {
            $password = Str::password(16, symbols: false);
            $this->command?->warn('DEMO_PASSWORD vacío: se generó una contraseña para las cuentas demo.');
        }

        User::query()->updateOrCreate(['email' => 'admin@asopistar.test'], [
            'name' => 'Marcela', 'last_name' => 'Ortiz', 'password' => $password, 'role' => Role::Admin,
            'document' => '52000001', 'active' => true,
        ]);
        $carlos = $this->farmer('piscicultor@asopistar.test', 'Carlos', 'Mendoza', '79000002', Gender::Male, 'Finca La Esperanza', 'El Carmen', $password);
        $luz = $this->farmer('luz.rojas@asopistar.test', 'Luz Dary', 'Rojas', '40000003', Gender::Female, 'Finca Los Guaduales', 'San Isidro', $password);
        $ana = $this->farmer('ana.quintero@asopistar.test', 'Ana María', 'Quintero', '1100000004', Gender::Female, 'Parcela El Diviso', 'Buenos Aires', $password);

        $today = CarbonImmutable::today();
        $tilapia = Species::query()->where('name', 'Tilapia roja')->firstOrFail();
        $cachama = Species::query()->where('name', 'Cachama blanca')->firstOrFail();
        $trucha = Species::query()->where('name', 'Trucha arcoíris')->firstOrFail();
        $provider = Provider::query()->orderBy('id')->firstOrFail();

        if (! $carlos->ponds()->exists()) {
            $this->cycle($this->pond($carlos, 'E-01', 600, 4.5), $tilapia, $provider, 3000, 18, $today);
            $this->cycle($this->pond($carlos, 'E-02', 400, 3.0), $cachama, $provider, 1500, 3, $today);
            $this->pond($carlos, 'E-03', 250, 2.0);
        }
        if (! $luz->ponds()->exists()) {
            $pond = $this->pond($luz, 'T-1', 300, 12.0);
            $this->cycle($pond, $trucha, null, 2000, 40, $today->subWeeks(5), finished: true);
            $this->cycle($pond, $trucha, $provider, 2200, 4, $today);
        }
        if (! $ana->ponds()->exists()) {
            $this->cycle($this->pond($ana, 'A-1', 500, 3.5), $tilapia, $provider, 2500, 25, $today);
        }

        $this->content();
        $this->command?->info('Cuentas demo: admin@asopistar.test y piscicultor@asopistar.test con la contraseña '.$password);
    }

    private function farmer(string $email, string $name, string $lastName, string $document, Gender $gender, string $estate, string $vereda, string $password): User
    {
        return User::query()->updateOrCreate(['email' => $email], [
            'name' => $name, 'last_name' => $lastName, 'password' => $password, 'role' => Role::Farmer,
            'document' => $document, 'member_code' => 'ASO-'.substr($document, -3), 'gender' => $gender,
            'phone' => '310'.substr($document, -7), 'estate' => $estate, 'vereda' => $vereda, 'active' => true,
        ]);
    }

    private function pond(User $farmer, string $code, int $area, float $flow): Pond
    {
        return $farmer->ponds()->create([
            'code' => $code, 'area_m2' => $area, 'water_flow_lps' => $flow,
            'equipment' => 'Aireador de paletas, comedero manual', 'active' => true,
        ]);
    }

    /**
     * Writes a plausible logbook: weekly feeding, a sampling every two weeks and
     * the final harvest when the cycle is finished.
     */
    private function cycle(Pond $pond, Species $species, ?Provider $provider, int $fish, int $weeks, CarbonImmutable $asOf, bool $finished = false): Cycle
    {
        $stockedOn = $asOf->subWeeks($weeks);
        $initial = 2.0;
        $cycle = $pond->cycles()->create([
            'species_id' => $species->id, 'provider_id' => $provider?->id, 'stocked_fish' => $fish,
            'initial_weight_grams' => $initial, 'stocked_on' => $stockedOn->toDateString(),
        ]);

        $weight = fn (int $week): float => $initial + ($species->market_weight_grams - $initial) * min(1, ($week / $species->harvest_week) ** 1.4);
        $alive = $fish;
        $feeds = FeedBrand::query()->orderByDesc('protein_percent')->get();
        for ($week = 1; $week <= $weeks; $week++) {
            $stage = $species->profile()->stageAt($week);
            $brand = $feeds->skip(min($feeds->count() - 1, match ($stage) {
                GrowthStage::Fry => 0,
                GrowthStage::GrowOut => 1,
                GrowthStage::Fattening => 2,
            }))->firstOrFail();
            $gainKg = max(0.5, $alive * ($weight($week) - $weight($week - 1)) / 1000);
            $cycle->feedings()->create([
                'feed_brand_id' => $brand->id, 'fed_on' => $stockedOn->addWeeks($week)->toDateString(),
                'kilograms' => round($gainKg * 1.55, 1), 'protein_percent' => $brand->protein_percent,
            ]);
            if ($week % 2 === 0) {
                $dead = (int) round($alive * 0.012);
                $alive -= $dead;
                $cycle->samplings()->create([
                    'sampled_on' => $stockedOn->addWeeks($week)->toDateString(), 'sample_size' => 30,
                    'sample_weight_grams' => round($weight($week) * 30, 1), 'mortality' => $dead,
                ]);
            }
            if ($week === 6) {
                $cycle->treatments()->create([
                    'applied_on' => $stockedOn->addWeeks($week)->toDateString(),
                    'agent' => 'Sal marina (baño de 3 g/L)', 'reason' => 'Prevención de hongos tras el muestreo',
                ]);
            }
        }

        if ($finished) {
            $kg = round($alive * $weight($weeks) / 1000, 1);
            $cycle->harvests()->create([
                'harvested_on' => $asOf->toDateString(), 'fish_count' => $alive, 'kilograms' => $kg,
                'destination' => 'Plaza de mercado de Neiva', 'ice_slaughter_kg' => round($kg * 0.5),
                'ice_cooling_kg' => round($kg * 0.6), 'ice_transport_kg' => round($kg * 0.8),
            ]);
            $cycle->update(['closed_on' => $asOf->toDateString()]);
        }

        return $cycle;
    }

    private function content(): void
    {
        foreach ([
            ['Tilapia roja entera', 'Fresca, eviscerada y con escamas retiradas, de 400 a 500 g por pieza.', 16000, 'kg'],
            ['Filete de tilapia', 'Filete sin piel ni espinas, empacado al vacío.', 32000, 'kg'],
            ['Cachama blanca', 'Entera y eviscerada, ideal para sudados y asados.', 14000, 'kg'],
            ['Trucha arcoíris', 'Mariposa lista para la plancha, de 300 a 350 g.', 26000, 'kg'],
        ] as [$name, $description, $price, $unit]) {
            Product::query()->firstOrCreate(['name' => $name], ['description' => $description, 'price_cop' => $price, 'unit' => $unit, 'available' => true]);
        }

        Recipe::query()->firstOrCreate(['title' => 'Tilapia frita con patacón'], [
            'summary' => 'El clásico de la costa y los Llanos: tilapia crocante con patacón y ensalada.',
            'ingredients' => "1 tilapia roja de 450 g\n2 limones\n2 dientes de ajo machacados\nSal y pimienta\nAceite para freír\n1 plátano verde",
            'steps' => "Haz tres cortes diagonales a cada lado del pescado.\nAdoba con limón, ajo, sal y pimienta por 20 minutos.\nFríe en aceite bien caliente de 6 a 8 minutos por lado.\nFríe el plátano en trozos, aplánalos y vuelve a freír.\nSirve con ensalada de tomate y cebolla.",
        ]);
        Recipe::query()->firstOrCreate(['title' => 'Sudado de cachama'], [
            'summary' => 'Cachama en hogao con papa criolla, lista en 40 minutos.',
            'ingredients' => "1 cachama de 600 g en postas\n2 tomates maduros\n1 cebolla larga\n500 g de papa criolla\nComino y sal\n1 taza de agua",
            'steps' => "Prepara un hogao con tomate y cebolla picados.\nAgrega la papa y el agua; cocina 15 minutos.\nIncorpora las postas de cachama con comino y sal.\nTapa y cocina a fuego bajo 15 minutos más.",
        ]);

        $today = CarbonImmutable::today();
        Post::query()->firstOrCreate(['title' => 'Cosecha de trucha en San Isidro'], [
            'excerpt' => 'Las familias de la vereda entregaron su lote de trucha con cadena de frío hasta la plaza.',
            'body' => "Las familias piscicultoras de la vereda San Isidro completaron la cosecha de su lote de trucha arcoíris.\n\nEl sacrificio se hizo por choque térmico con hielo y el pescado llegó a la plaza de mercado en menos de tres horas.",
            'published_on' => $today->subWeeks(5)->toDateString(),
        ]);
        Post::query()->firstOrCreate(['title' => 'Taller de biometrías y conversión alimenticia'], [
            'excerpt' => 'Aprendimos a muestrear los estanques y a leer la conversión alimenticia en la bitácora.',
            'body' => "Durante la jornada practicamos el muestreo de peces con atarraya y el pesaje en grupo.\n\nCon esos datos la bitácora calcula el peso promedio, la biomasa y cuántos kilos de alimento se necesitan por cada kilo de pescado.",
            'published_on' => $today->subWeeks(2)->toDateString(),
        ]);
    }
}
