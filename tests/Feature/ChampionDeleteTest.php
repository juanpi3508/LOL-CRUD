<?php

namespace Tests\Feature;

use App\Models\Champion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChampionDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_delete_champion_successfully(): void
    {
        $champion = Champion::create([
            'name' => 'Teemo',
            'title' => 'El Explorador Veloz',
            'role' => 'Marksman',
            'resource_type' => 'Maná',
            'difficulty' => 'Baja',
            'lore' => 'Un yordle entusiasta y explorador de Bandle.',
        ]);

        $response = $this->delete(route('champions.destroy', $champion));

        $response->assertRedirect(route('champions.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('champions', [
            'id' => $champion->id,
            'name' => 'Teemo',
        ]);
    }

    public function test_returns_404_when_deleting_non_existent_champion(): void
    {
        $response = $this->delete('/champions/999999');

        $response->assertStatus(404);
    }

    public function test_delete_confirmation_modal_present_on_detail_page(): void
    {
        $champion = Champion::create([
            'name' => 'Darius',
            'title' => 'La Mano de Noxus',
            'role' => 'Fighter',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'El líder militar más temido y experimentado de Noxus.',
        ]);

        $response = $this->get(route('champions.show', $champion));

        $response->assertStatus(200);
        $response->assertSee('¿Desterrar a Darius?');
        $response->assertSee('Confirmar Eliminación');
    }

    public function test_delete_modal_elements_present_on_catalog_page(): void
    {
        Champion::create([
            'name' => 'Lux',
            'title' => 'La Dama Luminosa',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Baja',
            'lore' => 'Una joven maga de Demacia que domina la luz.',
        ]);

        $response = $this->get(route('champions.index'));

        $response->assertStatus(200);
        $response->assertSee('delete-modal');
        $response->assertSee('Confirmar Eliminación');
    }
}
