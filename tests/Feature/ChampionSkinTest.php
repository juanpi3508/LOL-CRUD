<?php

namespace Tests\Feature;

use App\Models\Champion;
use App\Models\Skin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChampionSkinTest extends TestCase
{
    use RefreshDatabase;

    private function createChampion(array $attributes = []): Champion
    {
        return Champion::create(array_merge([
            'name' => 'Jinx',
            'title' => 'La Bala Perdida',
            'role' => 'Marksman',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una criminal impulsiva y maniática de Zaun.',
            'image_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_0.jpg',
        ], $attributes));
    }

    public function test_can_render_champion_detail_with_skins(): void
    {
        $champion = $this->createChampion();

        $skin = Skin::create([
            'champion_id' => $champion->id,
            'name' => 'Jinx Guardiana Estelar',
            'tier' => 'Legendaria',
            'price_rp' => 1820,
            'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_4.jpg',
        ]);

        $response = $this->get(route('champions.show', $champion));

        $response->assertStatus(200);
        $response->assertSee('Colección de Aspectos');
        $response->assertSee('Jinx Guardiana Estelar');
        $response->assertSee('1,820 RP');
        $response->assertSee('Legendaria');
        $response->assertSee('Invocar Aspecto');
    }

    public function test_can_store_skin_for_champion(): void
    {
        $champion = $this->createChampion();

        $skinData = [
            'name' => 'Jinx Arcane',
            'tier' => 'Mítica',
            'price_rp' => 975,
            'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_37.jpg',
        ];

        $response = $this->post(route('champions.skins.store', $champion), $skinData);

        $response->assertRedirect(route('champions.show', $champion));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('skins', [
            'champion_id' => $champion->id,
            'name' => 'Jinx Arcane',
            'tier' => 'Mítica',
            'price_rp' => 975,
        ]);
    }

    public function test_cannot_store_skin_with_invalid_data(): void
    {
        $champion = $this->createChampion();

        $response = $this->post(route('champions.skins.store', $champion), [
            'name' => '',
            'tier' => 'TierInvalido',
            'price_rp' => -100,
            'splash_art_url' => 'no-es-una-url',
        ]);

        $response->assertSessionHasErrors(['name', 'tier', 'price_rp', 'splash_art_url']);
        $this->assertDatabaseCount('skins', 0);
    }

    public function test_can_delete_skin(): void
    {
        $champion = $this->createChampion();

        $skin = Skin::create([
            'champion_id' => $champion->id,
            'name' => 'Jinx Mafiosa',
            'tier' => 'Épica',
            'price_rp' => 975,
        ]);

        $this->assertDatabaseCount('skins', 1);

        $response = $this->delete(route('skins.destroy', $skin));

        $response->assertRedirect(route('champions.show', $champion));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('skins', ['id' => $skin->id]);
    }

    public function test_deleting_champion_deletes_associated_skins_in_cascade(): void
    {
        $champion = $this->createChampion();

        $skin = Skin::create([
            'champion_id' => $champion->id,
            'name' => 'Jinx Artificiera',
            'tier' => 'Épica',
            'price_rp' => 1350,
        ]);

        $this->assertDatabaseHas('skins', ['id' => $skin->id]);

        $this->delete(route('champions.destroy', $champion));

        $this->assertDatabaseMissing('champions', ['id' => $champion->id]);
        $this->assertDatabaseMissing('skins', ['id' => $skin->id]);
    }
}
