<?php

namespace Tests\Feature;

use App\Enums\Gender;
use App\Models\Cycle;
use App\Models\Harvest;
use App\Models\Pond;
use App\Models\Species;
use App\Models\User;
use App\Reports\AssociationReport;
use Tests\TestCase;

final class ReportTest extends TestCase
{
    public function test_cycle_report_is_a_pdf(): void
    {
        $cycle = $this->openCycle();

        $response = $this->actingAs($cycle->pond->user)->get('/ciclos/'.$cycle->id.'/reporte');

        $response->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', (string) $response->getContent());
    }

    public function test_association_report_aggregates_open_cycles(): void
    {
        $species = Species::factory()->create(['name' => 'Tilapia roja', 'harvest_week' => 24, 'market_weight_grams' => 500]);
        $ana = User::factory()->create(['gender' => Gender::Female]);
        $luis = User::factory()->create(['gender' => Gender::Male]);
        $near = Cycle::factory()->for(Pond::factory()->for($ana))->for($species, 'species')
            ->create(['stocked_on' => now()->subWeeks(22)->toDateString(), 'stocked_fish' => 1000]);
        Cycle::factory()->for(Pond::factory()->for($luis))->for($species, 'species')
            ->create(['stocked_on' => now()->subWeeks(2)->toDateString(), 'stocked_fish' => 500]);
        Cycle::factory()->closed()->for(Pond::factory()->for($luis))->for($species, 'species')->create();
        Harvest::factory()->for($near)->create(['fish_count' => 200, 'kilograms' => 90]);

        $report = AssociationReport::build();

        $this->assertSame(2, $report->farmers);
        $this->assertSame(['Femenino' => 1, 'Masculino' => 1, 'Otro' => 0, 'Sin dato' => 0], $report->farmersByGender);
        $this->assertSame(3, $report->activePonds);
        $this->assertSame(2, $report->openCycles);
        $this->assertSame(1300, $report->fishInWater);
        $this->assertSame(['Cría' => 1, 'Levante' => 0, 'Ceba' => 1], $report->cyclesByStage);
        $this->assertEqualsWithDelta(400.0, $report->projectedKgWithin[4], 1e-9);
        $this->assertEqualsWithDelta(650.0, $report->projectedKgWithin[24], 1e-9);
        $this->assertSame('Tilapia roja', $report->bySpecies[0]['species']);
    }

    public function test_admin_dashboard_and_pdf_render(): void
    {
        $this->openCycle();
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Resumen de la asociación')->assertSee('1.000');
        $this->actingAs($admin)->get('/admin/reporte')->assertOk()->assertHeader('Content-Type', 'application/pdf');
    }
}
