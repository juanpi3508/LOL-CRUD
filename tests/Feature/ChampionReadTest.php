<?php

namespace Tests\Feature;

use App\Models\Champion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChampionReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_champion_catalog_page(): void
    {
        $response = $this->get(route('champions.index'));

        $response->assertStatus(200);
        $response->assertSee('Catálogo de Campeones');
        $response->assertSee('Grieta del Invocador');
    }

    public function test_catalog_displays_empty_state_when_no_champions(): void
    {
        $response = $this->get(route('champions.index'));

        $response->assertStatus(200);
        $response->assertSee('Aún no hay campeones invocados');
    }

    public function test_catalog_displays_champions_list(): void
    {
        $champion = Champion::create([
            'name' => 'Ahri',
            'title' => 'La Zorra de Nueve Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una vastaaya conectada a la magia de Jonia.',
        ]);

        $response = $this->get(route('champions.index'));

        $response->assertStatus(200);
        $response->assertSee('Ahri');
        $response->assertSee('La Zorra de Nueve Colas');
        $response->assertSee('Mago');
        $response->assertSee(route('champions.show', $champion));
    }

    public function test_catalog_can_filter_by_role(): void
    {
        Champion::create([
            'name' => 'Ahri',
            'title' => 'La Zorra de Nueve Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una vastaaya maga.',
        ]);

        Champion::create([
            'name' => 'Zed',
            'title' => 'El Maestro de las Sombras',
            'role' => 'Assassin',
            'resource_type' => 'Energía',
            'difficulty' => 'Alta',
            'lore' => 'Líder de la Orden de la Sombra.',
        ]);

        $response = $this->get(route('champions.index', ['role' => 'Mage']));

        $response->assertStatus(200);
        $response->assertSee('Ahri');
        $response->assertDontSee('Zed');
    }

    public function test_catalog_can_filter_by_difficulty(): void
    {
        Champion::create([
            'name' => 'Garen',
            'title' => 'El Poder de Demacia',
            'role' => 'Fighter',
            'resource_type' => 'Sin maná',
            'difficulty' => 'Baja',
            'lore' => 'Orgulloso guerrero noble de Demacia.',
        ]);

        Champion::create([
            'name' => 'Yasuo',
            'title' => 'La Espada sin Honor',
            'role' => 'Fighter',
            'resource_type' => 'Sin maná',
            'difficulty' => 'Alta',
            'lore' => 'Un jonio diestro y ágil espadachín.',
        ]);

        $response = $this->get(route('champions.index', ['difficulty' => 'Alta']));

        $response->assertStatus(200);
        $response->assertSee('Yasuo');
        $response->assertDontSee('Garen');
    }

    public function test_catalog_can_search_by_name_or_title(): void
    {
        Champion::create([
            'name' => 'Jinx',
            'title' => 'La Bala Perdida',
            'role' => 'Marksman',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una criminal impulsiva y maniática de Zaun.',
        ]);

        Champion::create([
            'name' => 'Vi',
            'title' => 'La Defensora de Piltóver',
            'role' => 'Fighter',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una mujer impetuosa de Zaun que ahora defiende la ley.',
        ]);

        $response = $this->get(route('champions.index', ['search' => 'Bala Perdida']));

        $response->assertStatus(200);
        $response->assertSee('Jinx');
        $response->assertDontSee('La Defensora de Piltóver');
    }

    public function test_can_render_champion_detail_page(): void
    {
        $champion = Champion::create([
            'name' => 'Thresh',
            'title' => 'El Carcelero de las Cadenas',
            'role' => 'Support',
            'resource_type' => 'Maná',
            'difficulty' => 'Alta',
            'lore' => 'Sádico y astuto, Thresh es un espíritu atormentado de las Islas de la Sombra.',
            'image_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Thresh_0.jpg',
        ]);

        $response = $this->get(route('champions.show', $champion));

        $response->assertStatus(200);
        $response->assertSee('Thresh');
        $response->assertSee('El Carcelero de las Cadenas');
        $response->assertSee('Soporte');
        $response->assertSee('Islas de la Sombra');
        $response->assertSee('Dificultad: Alta');
    }

    public function test_detail_page_returns_404_for_non_existent_champion(): void
    {
        $response = $this->get('/champions/99999');

        $response->assertStatus(404);
    }
}
