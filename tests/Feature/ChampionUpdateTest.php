<?php

namespace Tests\Feature;

use App\Models\Champion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChampionUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_champion_edit_page(): void
    {
        $champion = Champion::create([
            'name' => 'Ahri',
            'title' => 'La Zorra de Nueve Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una vastaaya conectada a la magia innata de Jonia.',
        ]);

        $response = $this->get(route('champions.edit', $champion));

        $response->assertStatus(200);
        $response->assertSee('Modificar Atributos de Ahri');
        $response->assertSee('La Zorra de Nueve Colas');
        $response->assertSee('Guardar Modificaciones');
    }

    public function test_validation_errors_when_updating_with_empty_fields(): void
    {
        $champion = Champion::create([
            'name' => 'Ahri',
            'title' => 'La Zorra de Nueve Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una vastaaya mágica y misteriosa.',
        ]);

        $response = $this->put(route('champions.update', $champion), []);

        $response->assertSessionHasErrors([
            'name',
            'title',
            'role',
            'resource_type',
            'difficulty',
            'lore',
        ]);
    }

    public function test_can_update_champion_keeping_same_name(): void
    {
        $champion = Champion::create([
            'name' => 'Ahri',
            'title' => 'La Zorra de Nueve Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una vastaaya mágica y misteriosa.',
        ]);

        $response = $this->put(route('champions.update', $champion), [
            'name' => 'Ahri',
            'title' => 'Espíritu de las Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Alta',
            'lore' => 'Lore actualizado de la campeona Ahri.',
        ]);

        $response->assertRedirect(route('champions.show', $champion));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('champions', [
            'id' => $champion->id,
            'name' => 'Ahri',
            'title' => 'Espíritu de las Colas',
            'difficulty' => 'Alta',
        ]);
    }

    public function test_validation_error_when_updating_to_another_champion_name(): void
    {
        Champion::create([
            'name' => 'Ahri',
            'title' => 'La Zorra de Nueve Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una vastaaya mágica y misteriosa.',
        ]);

        $jinx = Champion::create([
            'name' => 'Jinx',
            'title' => 'La Bala Perdida',
            'role' => 'Marksman',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una criminal impulsiva y maniática de Zaun.',
        ]);

        $response = $this->put(route('champions.update', $jinx), [
            'name' => 'Ahri', // Ya existe
            'title' => 'Nuevo Título',
            'role' => 'Marksman',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Lore descriptivo nuevo.',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_can_update_champion_successfully(): void
    {
        $champion = Champion::create([
            'name' => 'Zed',
            'title' => 'El Maestro de las Sombras',
            'role' => 'Assassin',
            'resource_type' => 'Energía',
            'difficulty' => 'Alta',
            'lore' => 'Líder despiadado de la Orden de la Sombra.',
            'image_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Zed_0.jpg',
        ]);

        $payload = [
            'name' => 'Zed el Sombra',
            'title' => 'Líder de la Orden Tenebrosa',
            'role' => 'Assassin',
            'resource_type' => 'Energía',
            'difficulty' => 'Alta',
            'lore' => 'Trasfondo extendido sobre el dominio de las sombras prohibidas de Jonia.',
            'image_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Zed_1.jpg',
        ];

        $response = $this->put(route('champions.update', $champion), $payload);

        $response->assertRedirect(route('champions.show', $champion));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('champions', [
            'id' => $champion->id,
            'name' => 'Zed el Sombra',
            'title' => 'Líder de la Orden Tenebrosa',
            'lore' => 'Trasfondo extendido sobre el dominio de las sombras prohibidas de Jonia.',
        ]);
    }

    public function test_cannot_update_with_invalid_role_or_difficulty(): void
    {
        $champion = Champion::create([
            'name' => 'Garen',
            'title' => 'El Poder de Demacia',
            'role' => 'Fighter',
            'resource_type' => 'Sin maná',
            'difficulty' => 'Baja',
            'lore' => 'Orgulloso y noble guerrero demaciano.',
        ]);

        $response = $this->put(route('champions.update', $champion), [
            'name' => 'Garen',
            'title' => 'El Poder de Demacia',
            'role' => 'SuperHeroeInvalido',
            'resource_type' => 'PoderCosmico',
            'difficulty' => 'Extrema',
            'lore' => 'Biografía de prueba con valores no permitidos.',
        ]);

        $response->assertSessionHasErrors(['role', 'resource_type', 'difficulty']);
    }
}
