<?php

namespace Tests;

use App\Models\Cycle;
use App\Models\Pond;
use App\Models\Species;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    protected function farmer(): User
    {
        return User::factory()->create();
    }

    protected function admin(): User
    {
        return User::factory()->admin()->create();
    }

    /**
     * Open cycle of 1000 tilapias stocked ten weeks ago in a pond of the given farmer.
     */
    protected function openCycle(?User $farmer = null): Cycle
    {
        $pond = Pond::factory()->for($farmer ?? $this->farmer())->create();

        return Cycle::factory()->for($pond)->for(Species::factory()->create(), 'species')->create();
    }
}
