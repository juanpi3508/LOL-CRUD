<?php

namespace Tests\Feature;

use App\Models\Champion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChampionCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_champion_create_page(): void
    {
        $response = $this->get(route('champions.create'));

        $response->assertStatus(200);
        $response->assertSee('Registrar Nuevo Campeón');
        $response->assertSee('Invocar Campeón (Guardar)');
    }

    public function test_validation_errors_when_required_fields_missing(): void
    {
        $response = $this->post(route('champions.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'title',
            'role',
            'resource_type',
            'difficulty',
            'lore',
        ]);
    }

    public function test_validation_error_for_duplicate_name(): void
    {
        Champion::create([
            'name' => 'Ahri',
            'title' => 'La Zorra de Nueve Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una vastaaya mágica y misteriosa.',
        ]);

        $response = $this->post(route('champions.store'), [
            'name' => 'Ahri',
            'title' => 'Otro Título',
            'role' => 'Assassin',
            'resource_type' => 'Maná',
            'difficulty' => 'Alta',
            'lore' => 'Otra biografía descriptiva.',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_can_store_champion_successfully(): void
    {
        $payload = [
            'name' => 'Jinx',
            'title' => 'La Bala Perdida',
            'role' => 'Marksman',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una criminal impulsiva y maniática de Zaun que adora el caos.',
            'image_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_0.jpg',
        ];

        $response = $this->post(route('champions.store'), $payload);

        $response->assertRedirect(route('champions.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('champions', [
            'name' => 'Jinx',
            'title' => 'La Bala Perdida',
            'role' => 'Marksman',
        ]);
    }
}
