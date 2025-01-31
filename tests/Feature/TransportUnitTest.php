<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\TransportUnit;

class TransportUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_fetch_all_transport_units()
    {
        TransportUnit::factory()->count(5)->create();

        $response = $this->getJson('/api/transport-units'); // Correct URL if using api.php

        $response->assertStatus(200)
                 ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_can_create_a_new_transport_unit()
    {
        $data = [
            'name' => 'Test Truck',
            'type' => 'truck',
        ];

        $response = $this->postJson('/api/transport-units', $data); // Correct URL

        $response->assertStatus(201)
                 ->assertJson([
                     'name' => 'Test Truck',
                     'type' => 'truck',
                 ]);

        $this->assertDatabaseHas('transport_units', $data);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_transport_unit()
    {
        $response = $this->postJson('/api/transport-units', []); // Correct URL

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'type']);
    }
}