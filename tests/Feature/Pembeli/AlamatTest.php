<?php

namespace Tests\Feature\Pembeli;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AlamatTest extends TestCase
{
    use RefreshDatabase;

    public function test_pembeli_can_access_create_address_page()
    {
        $user = User::factory()->create([
            'role' => 'pembeli',
        ]);

        $response = $this->actingAs($user)
            ->get(route('pembeli.alamat.create'));

        $response->assertStatus(200);
        $response->assertViewIs('pembeli.alamat.create');
    }

    public function test_pembeli_can_store_new_address()
    {
        $user = User::factory()->create([
            'role' => 'pembeli',
        ]);

        $data = [
            'label'           => 'Rumah',
            'recipient_name'  => 'John Doe',
            'recipient_phone' => '08123456789',
            'province_id'     => 1,
            'province_name'   => 'Jawa Barat',
            'city_id'         => 1,
            'city_name'       => 'Bandung',
            'city_type'       => 'Kota',
            'postal_code'     => '40123',
            'full_address'    => 'Jl. Percobaan No. 1',
            'is_default'      => true,
        ];

        $response = $this->actingAs($user)
            ->post(route('pembeli.alamat.store'), $data);

        $response->assertRedirect(route('pembeli.alamat.index'));
        
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'label'   => 'Rumah',
            'recipient_name' => 'John Doe',
        ]);
    }
}
