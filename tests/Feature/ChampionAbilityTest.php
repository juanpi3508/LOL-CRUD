<?php

namespace Tests\Feature;

use App\Models\Ability;
use App\Models\Champion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChampionAbilityTest extends TestCase
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

    public function test_can_render_champion_detail_with_abilities(): void
    {
        $champion = $this->createChampion();

        $ability = Ability::create([
            'champion_id' => $champion->id,
            'slot' => 'R',
            'name' => '¡Supermegacohete mortal!',
            'description' => 'Jinx dispara un enorme cohete que recorre todo el mapa.',
            'cooldown' => '85 / 65 / 45 s',
            'cost' => '100 de maná',
            'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/spell/JinxR.png',
        ]);

        $response = $this->get(route('champions.show', $champion));

        $response->assertStatus(200);
        $response->assertSee('Kit de Habilidades');
        $response->assertSee('¡Supermegacohete mortal!');
        $response->assertSee('100 de maná');
        $response->assertSee('85 / 65 / 45 s');
    }

    public function test_can_store_ability_for_champion(): void
    {
        $champion = $this->createChampion();

        $abilityData = [
            'slot' => 'Q',
            'name' => '¡Cambio de armas!',
            'description' => 'Jinx modifica sus ataques básicos alternando entre Pium-Pium y Espinas.',
            'cooldown' => '1 s',
            'cost' => '20 de maná por tiro',
            'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/spell/JinxQ.png',
        ];

        $response = $this->post(route('champions.abilities.store', $champion), $abilityData);

        $response->assertRedirect(route('champions.show', $champion));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('abilities', [
            'champion_id' => $champion->id,
            'slot' => 'Q',
            'name' => '¡Cambio de armas!',
            'cooldown' => '1 s',
            'cost' => '20 de maná por tiro',
        ]);
    }

    public function test_cannot_store_ability_with_invalid_data(): void
    {
        $champion = $this->createChampion();

        $response = $this->post(route('champions.abilities.store', $champion), [
            'slot' => 'X', // Ranura inválida
            'name' => '',  // Requerido
            'description' => 'abc', // Menor a 5 caracteres
            'icon_url' => 'no-es-una-url',
        ]);

        $response->assertSessionHasErrors(['slot', 'name', 'description', 'icon_url']);
        $this->assertDatabaseCount('abilities', 0);
    }

    public function test_can_delete_ability(): void
    {
        $champion = $this->createChampion();

        $ability = Ability::create([
            'champion_id' => $champion->id,
            'slot' => 'W',
            'name' => '¡Chispas!',
            'description' => 'Jinx dispara un proyectil electrizante.',
            'cooldown' => '8 s',
            'cost' => '50 de maná',
        ]);

        $this->assertDatabaseCount('abilities', 1);

        $response = $this->delete(route('abilities.destroy', $ability));

        $response->assertRedirect(route('champions.show', $champion));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('abilities', ['id' => $ability->id]);
    }

    public function test_deleting_champion_deletes_associated_abilities_in_cascade(): void
    {
        $champion = $this->createChampion();

        $ability = Ability::create([
            'champion_id' => $champion->id,
            'slot' => 'E',
            'name' => '¡Mascafuegos masticadores!',
            'description' => 'Jinx lanza una hilera de granadas trampa.',
            'cooldown' => '24 s',
            'cost' => '90 de maná',
        ]);

        $this->assertDatabaseHas('abilities', ['id' => $ability->id]);

        $this->delete(route('champions.destroy', $champion));

        $this->assertDatabaseMissing('champions', ['id' => $champion->id]);
        $this->assertDatabaseMissing('abilities', ['id' => $ability->id]);
    }
}
